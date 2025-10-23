<?php
namespace SUPV\Admin;

use SUPV\Admin\Views\Cards\AutoloadCardView;
use SUPV\Admin\Views\Cards\SecureLoginCardView;
use SUPV\Admin\Views\Cards\TransientsCardView;
use SUPV\Admin\Views\Cards\WordPressCardView;

/**
 * The AJAX class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class AJAX {
	/**
	 * Stores all the AJAX hooks.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $ajax_actions = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->ajax_actions = [
			'hide_admin_notice',
			'transients_cleanup',
			'autoload_options_list',
			'autoload_options_history',
			'autoload_update_option',
			'wordpress_auto_update_policy',
			'secure_login_settings_output',
			'secure_login_settings_save',
		];

		$this->hooks();
	}

	/**
	 * Initialize the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		foreach ( $this->ajax_actions as $action ) {
			add_action( 'wp_ajax_supv_' . $action, [ $this, $action ] );
		}

		add_action( 'admin_footer', [ $this, 'add_wp_nonces' ] );
	}

	/**
	 * Create a WP nonce for each hook.
	 *
	 * @since 1.0.0
	 */
	public function add_wp_nonces() {

		foreach ( $this->ajax_actions as $action ) {
			wp_nonce_field( 'supv_' . $action, 'supv_' . $action . '_wpnonce' );
		}
	}

	/**
	 * Determines if current request is WordPress AJAX request.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if it's an WordPress AJAX request.
	 */
	public function is_doing_ajax() {

		return ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	}

	/**
	 * Hides an admin notice.
	 *
	 * @since 1.0.0
	 */
	public function hide_admin_notice() {

		$this->verify_ajax_request( 'hide_admin_notice' );

		if ( ! empty( $_POST['software'] ) && preg_match( '/(?:ssl|https)/', sanitize_key( wp_unslash( $_POST['software'] ) ) ) ) {
			$notices_transient = get_transient( Dashboard::HIDE_NOTICES_TRANSIENT );

			if ( $notices_transient === false ) {
				$notices_transient = [];
			}

			$notices_transient[ sanitize_key( wp_unslash( $_POST['software'] ) ) ] = 1;

			set_transient( Dashboard::HIDE_NOTICES_TRANSIENT, $notices_transient, DAY_IN_SECONDS );
		}

		wp_die();
	}

	/**
	 * Cleans up the transients.
	 *
	 * @since 1.0.0
	 */
	public function transients_cleanup() {

		$this->verify_ajax_request( 'transients_cleanup' );

		supv()->core()->transients()->cleanup( isset( $_POST['expired'] ) );

		( new TransientsCardView() )->output_stats( true );

		wp_die();
	}

	/**
	 * Loads the autoload options list.
	 *
	 * @since 1.0.0
	 */
	public function autoload_options_list() {

		$this->verify_ajax_request( 'autoload_options_list' );

		( new AutoloadCardView() )->output_options();

		wp_die();
	}

	/**
	 * Loads the autoload options history.
	 *
	 * @since 1.0.0
	 */
	public function autoload_options_history() {

		$this->verify_ajax_request( 'autoload_options_history' );

		( new AutoloadCardView() )->output_history();

		wp_die();
	}

	/**
	 * Updates the autoload value for the given options.
	 *
	 * @since 1.0.0
	 */
	public function autoload_update_option() {

		$this->verify_ajax_request( 'autoload_update_option' );

		$data = $this->extract_form_data();

		$options    = [];
		$is_history = false;

		foreach ( $data as $option_name => $value ) {
			if ( supv()->core()->autoload()->is_deactivated( $option_name ) ) {
				$options[ $option_name ] = supv()->core()->autoload()->reactivate( $option_name );
				$is_history              = true;
			} else {
				$options[ $option_name ] = supv()->core()->autoload()->deactivate( $option_name );
			}
		}

		( new AutoloadCardView() )->output_stats( $options, $is_history );

		wp_die();
	}

	/**
	 * Updates the WordPress auto update policy.
	 *
	 * @since 1.2.0
	 */
	public function wordpress_auto_update_policy() {

		$this->verify_ajax_request( 'wordpress_auto_update_policy' );

		$policy = ! empty( $_POST['wp_auto_update_policy'] ) ? sanitize_key( wp_unslash( $_POST['wp_auto_update_policy'] ) ) : false;

		if ( ! empty( $policy ) && preg_match( '/^(?:minor|major|disabled|dev)$/', $policy ) ) {
			supv()->core()->wordpress()->set_auto_update_policy( $policy );
		}

		( new WordPressCardView() )->output_select( true );

		wp_die();
	}

	/**
	 * Outputs the Secure Login settings.
	 *
	 * @since 1.3.0
	 */
	public function secure_login_settings_output() {

		$this->verify_ajax_request( 'secure_login_settings_output' );

		supv()->core()->secure_login()->update_settings(
			[
				'enabled' => 1,
			]
		);

		( new SecureLoginCardView() )->output_settings();

		wp_die();
	}

	/**
	 * Saves the Secure Login settings to the database.
	 *
	 * @since 1.3.0
	 */
	public function secure_login_settings_save() {

		$this->verify_ajax_request( 'secure_login_settings_save' );

		$settings = array_map( 'intval', $this->extract_form_data() ); // Converts all the values to int.

		supv()->core()->secure_login()->update_settings( $settings );

		( new SecureLoginCardView() )->output_settings( true );

		wp_die();
	}

	/**
	 * Extracts the form data.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	private function extract_form_data() {

		$data   = [];
		$prefix = 'supv-field-';

		// phpcs:disable WordPress.Security.NonceVerification.Missing

		foreach ( $_POST as $key => $value ) {
			// Decode and sanitize the field name.
			$sanitized_key = sanitize_key( urldecode( $key ) );

			// Skip if not a supervisor field.
			if ( strpos( $sanitized_key, $prefix ) !== 0 ) {
				continue;
			}

			// Extract field name by removing prefix.
			$field_name = substr( $sanitized_key, strlen( $prefix ) );

			if ( empty( $field_name ) ) {
				continue;
			}

			// Sanitize and store the value.
			$data[ $field_name ] = sanitize_text_field( wp_unslash( $value ) );
		}

		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return $data;
	}

	/**
	 * Verifies AJAX request security (nonce and capability).
	 *
	 * @since {VERSION}
	 *
	 * @param string $action The action name for nonce verification.
	 */
	private function verify_ajax_request( $action ) {

		check_ajax_referer( 'supv_' . $action );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'supervisor' ) );
		}
	}
}
