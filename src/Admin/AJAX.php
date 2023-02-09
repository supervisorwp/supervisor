<?php
namespace SUPV\Admin;

/**
 * The AJAX class.
 *
 * @package supervisor-wp
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

		if ( isset( $_POST['software'] ) && preg_match( '/(?:ssl|https)/', sanitize_key( wp_unslash( $_POST['software'] ) ) ) ) {
			$notices_transient = get_transient( Dashboard::HIDE_NOTICES_TRANSIENT );

			if ( false === $notices_transient ) {
				$notices_transient = [];
			}

			$notices_transient[ sanitize_key( $_POST['software'] ) ] = 1;

			set_transient( Dashboard::HIDE_NOTICES_TRANSIENT, $notices_transient, DAY_IN_SECONDS );
		}

		wp_die();
	}

	/**
	 * Cleans up the transients.
	 *
	 * @since 1.0
	 */
	public function transients_cleanup() {

		check_ajax_referer( 'supv_transients_cleanup' );

		supv()->core()->transients()->cleanup( isset( $_POST['expired'] ) );

		wp_die();
	}
}
