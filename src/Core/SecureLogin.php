<?php
namespace SUPV\Core;

use WP_Error;

/**
 * The SecureLogin class.
 *
 * @package supervisor
 * @since {VERSION}
 */
class SecureLogin {

	/**
	 * Option to store the log of login attempts.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const LOGIN_ATTEMPTS_LOG = 'supv_secure_login_attempts_log';

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
		add_filter( 'authenticate', [ $this, 'check_login_attempt' ], 20, 3 );
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

		if ( empty( $username ) ) {
			return $user;
		}

		$user_ip  = supv_get_user_ip();
		$username = strtolower( $username );

		if ( $this->is_limit_reached( $user_ip, $username ) ) {
			$error = new WP_Error();

			$message = sprintf(
				'<strong>%s</strong>: %s',
				esc_html__( 'Error', 'supervisor' ),
				esc_html__( 'You have exceeded the maximum number of login attempts. Please try again later.', 'supervisor' )
			);

			$error->add( 'supv_too_many_attempts', $message );
		}

		$this->log_login_attempt( $user_ip, $username );

		return ! empty( $error ) ? $error : $user;
	}

	/**
	 * Cleans up the expired login attempts.
	 *
	 * @since {VERSION}
	 */
	public function cleanup_expired_login_attempts() {

		$log = get_option( self::LOGIN_ATTEMPTS_LOG );

		if ( ! $log ) {
			return;
		}

		$fields = [
			'ips',
			'usernames',
		];

		$limit_timestamp = strtotime( '-2 minutes' );

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

		update_option( self::LOGIN_ATTEMPTS_LOG, $log );
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

		$log = get_option( self::LOGIN_ATTEMPTS_LOG );

		if ( $log ) {
			if ( ! empty( $user_ip ) && ! empty( $log['ips'][ $user_ip ] ) ) {
				$response['ip'] = $log['ips'][ $user_ip ];
			}

			if ( ! empty( $username ) && ! empty( $log['usernames'][ $username ] ) ) {
				$response['username'] = $log['usernames'][ $username ];
			}
		}

		return $response;
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

		$log = get_option( self::LOGIN_ATTEMPTS_LOG );

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

		update_option( self::LOGIN_ATTEMPTS_LOG, $log );
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
}
