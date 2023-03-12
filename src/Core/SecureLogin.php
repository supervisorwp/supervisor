<?php
namespace SUPV\Core;

/**
 * The SecureLogin class.
 *
 * @package supervisor
 * @since {VERSION}
 */
class SecureLogin {

	/**
	 * The default Secure Login settings.
	 *
	 * @since {VERSION}
	 *
	 * @var array
	 */
	const DEFAULT_SETTINGS = [
		'max-retries'      => 5,
		'lockout-time'     => 10,
		'max-lockouts'     => 3,
		'extended-lockout' => 24,
		'reset-retries'    => 24,
	];

	/**
	 * Option to store the log of login attempts.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const LOGIN_ATTEMPTS_LOG_OPTION = 'supv_secure_login_attempts_log';

	/**
	 * Option to store the Secure Login settings.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const SETTINGS_OPTION = 'supv_secure_login_settings';

	/**
	 * Constructor.
	 *
	 * @since {VERSION}
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * WordPress actions and filters.
	 *
	 * @since {VERSION}
	 */
	public function hooks() {

		add_action( 'login_init', [ $this, 'cleanup_expired_login_attempts' ] );

		add_filter( 'authenticate', [ $this, 'check_login_attempt' ], 21, 3 );
		add_filter( 'authenticate', [ $this, 'maybe_replace_invalid_username_error' ], 21, 3 );

		add_filter( 'shake_error_codes', [ $this, 'add_error_to_login_shake_codes' ] );
	}

	/**
	 * Adds Supervisor's error codes to the list of 'shake_error_codes'.
	 *
	 * @since {VERSION}
	 *
	 * @param string[] $error_codes The error codes that shake the login form.
	 *
	 * @return string[]
	 */
	public function add_error_to_login_shake_codes( $error_codes ) {

		$error_codes[] = 'supv_too_many_attempts';

		return $error_codes;
	}

	/**
	 * Checks the login attempt.
	 *
	 * @since {VERSION}
	 *
	 * @param WP_User|WP_Error|null $user     WP_User or WP_Error object from a previous callback. Default null.
	 * @param string                $username Username. If not empty, cancels the cookie authentication.
	 * @param string                $password Password. If not empty, cancels the cookie authentication.
	 *
	 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
	 */
	public function check_login_attempt( $user, $username, $password ) {

		$user_ip  = supv_get_user_ip();
		$username = strtolower( $username );

		if ( $this->is_limit_reached( $user_ip, $username ) ) {
			$error = supv_prepare_wp_error(
				'supv_too_many_attempts',
				esc_html__( 'You have exceeded the maximum number of login attempts. Please try again later.', 'supervisor' )
			);
		}

		$this->log_login_attempt( $user_ip, $username );

		return ! empty( $error ) ? $error : $user;
	}

	/**
	 * Cleans up the expired login attempts.
	 *
	 * @since {VERSION}
	 */
	public function cleanup_expired_login_attempts() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh,Generic.Metrics.NestingLevel.MaxExceeded

		$log = get_option( self::LOGIN_ATTEMPTS_LOG_OPTION );

		if ( ! $log ) {
			return;
		}

		$fields = [
			'ips',
			'usernames',
		];

		$limit_timestamp = strtotime( '-5 minutes' );

		foreach ( $fields as $field ) {
			foreach ( $log[ $field ] as $key => $data ) {
				foreach ( $data['timestamps'] as $index => $timestamp ) {
					if ( $timestamp < $limit_timestamp ) {
						unset( $log[ $field ][ $key ]['timestamps'][ $index ] );

						--$log[ $field ][ $key ]['count'];
					}
				}

				if ( empty( $log[ $field ][ $key ]['timestamps'] ) ) {
					unset( $log[ $field ][ $key ] );
				}
			}
		}

		update_option( self::LOGIN_ATTEMPTS_LOG_OPTION, $log );
	}

	/**
	 * Maybe replaces the invalid_username error message. By default, this error message indicates whether the user
	 * exists in the database or not.
	 *
	 * @since {VERSION}
	 *
	 * @param WP_User|WP_Error|null $user     WP_User or WP_Error object from a previous callback. Default null.
	 * @param string                $username Username. If not empty, cancels the cookie authentication.
	 * @param string                $password Password. If not empty, cancels the cookie authentication.
	 *
	 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
	 */
	public function maybe_replace_invalid_username_error( $user, $username, $password ) {

		if ( is_wp_error( $user ) && $user->get_error_code() === 'invalid_username' ) {
			$message = sprintf(
				/* translators: %s is the username. */
				esc_html__( 'The password you entered for the username %s is incorrect.', 'supervisor' ),
				$username
			);

			$error = supv_prepare_wp_error(
				'invalid_username',
				$message
			);
		}

		return ! empty( $error ) ? $error : $user;
	}

	/**
	 * Retrieves all settings, or the value from a given setting.
	 *
	 * @since {VERSION}
	 *
	 * @param string|false $name The name.
	 *
	 * @return string
	 */
	public function get_settings( $name = false ) {

		$settings = get_option( self::SETTINGS_OPTION, [] );

		return $name && isset( $settings[ $name ] ) ? $settings[ $name ] : $settings;
	}

	/**
	 * Updates the settings.
	 *
	 * @since {VERSION}
	 *
	 * @param array $new_settings The new settings.
	 */
	public function update_settings( $new_settings ) {

		// Populates the settings.
		$settings = get_option( self::SETTINGS_OPTION, [] );

		foreach ( $new_settings as $key => $value ) {
			$settings[ $key ] = $value;
		}

		// Populates the settings with the default values if needed.
		foreach ( self::DEFAULT_SETTINGS as $key => $value ) {
			if ( empty( $settings[ $key ] ) ) {
				$settings[ $key ] = $value;
			}
		}

		// Updates the settings.
		update_option( self::SETTINGS_OPTION, $settings );
	}

	/**
	 * Get the login attempts.
	 *
	 * @since {VERSION}
	 *
	 * @param string $user_ip  The user IP address.
	 * @param string $username The username.
	 */
	private function get_login_attempts( $user_ip, $username ) {

		$response = [];

		$log = get_option( self::LOGIN_ATTEMPTS_LOG_OPTION );

		if ( $log ) {
			if ( ! empty( $user_ip ) && ! empty( $log['ips'][ $user_ip ] ) ) {
				$response['ip'] = $log['ips'][ $user_ip ];
			}

			if ( ! empty( $log['usernames'][ $username ] ) ) {
				$response['username'] = $log['usernames'][ $username ];
			}
		}

		return $response;
	}

	/**
	 * Confirms if a given user and IP address have reached the limit.
	 *
	 * @since {VERSION}
	 *
	 * @param string $user_ip  The user IP address.
	 * @param string $username The username.
	 *
	 * @return bool
	 */
	private function is_limit_reached( $user_ip, $username ) {

		$attempts = $this->get_login_attempts( $user_ip, $username );

		return ! empty( $attempts['ip']['count'] ) && (int) $attempts['ip']['count'] > 20;
	}

	/**
	 * Logs the login attempt to the option.
	 *
	 * @since {VERSION}
	 *
	 * @param string $user_ip  The user IP address.
	 * @param string $username The username.
	 */
	private function log_login_attempt( $user_ip, $username ) {

		$log = get_option( self::LOGIN_ATTEMPTS_LOG_OPTION );

		$fields = [
			'ips'       => $user_ip,
			'usernames' => $username,
		];

		foreach ( $fields as $field => $value ) {
			$count      = ! empty( $log[ $field ][ $value ]['count'] ) ? (int) $log[ $field ][ $value ]['count'] : 0;
			$timestamps = ! empty( $log[ $field ][ $value ]['timestamps'] ) ? $log[ $field ][ $value ]['timestamps'] : [];

			array_unshift( $timestamps, time() );

			$log[ $field ][ $value ] = [
				'count'      => ++$count,
				'timestamps' => $timestamps,
			];
		}

		update_option( self::LOGIN_ATTEMPTS_LOG_OPTION, $log );
	}
}
