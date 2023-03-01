<?php
namespace SUPV\Admin;

use SUPV\Admin\Views\Cards\AutoloadCardView;
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

		check_ajax_referer( 'supv_hide_admin_notice' );

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

		check_ajax_referer( 'supv_transients_cleanup' );

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

		check_ajax_referer( 'supv_autoload_options_list' );

		( new AutoloadCardView() )->output_options();

		wp_die();
	}

	/**
	 * Loads the autoload options history.
	 *
	 * @since 1.0.0
	 */
	public function autoload_options_history() {

		check_ajax_referer( 'supv_autoload_options_history' );

		( new AutoloadCardView() )->output_history();

		wp_die();
	}

	/**
	 * Updates the autoload value for the given options.
	 *
	 * @since 1.0.0
	 */
	public function autoload_update_option() {

		check_ajax_referer( 'supv_autoload_update_option' );

		$options    = [];
		$is_history = false;

		foreach ( array_keys( $_POST ) as $key ) {
			if ( ! preg_match( '/^supv-opt-/', sanitize_key( $key ) ) ) {
				continue;
			}

			$option_name = preg_replace( '/^supv-opt-/', '', urldecode( sanitize_key( $key ) ) );

			if ( empty( $option_name ) ) {
				continue;
			}

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
	 * @since {VERSION}
	 */
	public function wordpress_auto_update_policy() {

		check_ajax_referer( 'supv_wordpress_auto_update_policy' );

		$policy = ! empty( $_POST['wp_auto_update_policy'] ) ? sanitize_key( wp_unslash( $_POST['wp_auto_update_policy'] ) ) : false;

		if ( ! empty( $policy ) && preg_match( '/^(?:minor|major|disabled|dev)$/', $policy ) ) {
			supv()->core()->wordpress()->set_auto_update_policy( $policy );
		}

		( new WordPressCardView() )->output_select( true );

		wp_die();
	}
}
