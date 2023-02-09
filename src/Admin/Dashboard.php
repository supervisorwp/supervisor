<?php
namespace SUPV\Admin;

use SUPV\Admin\Views\DashboardView;
use SUPV\Admin\Views\Notices\HTTPSView;
use SUPV\Admin\Views\Notices\SSLView;

/**
 * The Dashboard class.
 *
 * @package supervisor-wp
 * @since 1.0.0
 */
final class Dashboard {
	/**
	 * Option to disable admin notices.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const DISABLE_NOTICES_OPTION = 'supv_disable_admin_notices';

	/**
	 * Transient to store if an admin notice should be displayed or not.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const HIDE_NOTICES_TRANSIENT = 'supv_hide_admin_notices';

	/**
	 * Admin page hookname.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $hookname = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Initialize the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		add_action( 'admin_init', [ $this, 'load_resources' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 5 );
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}

	/**
	 * Loads the admin resources.
	 *
	 * @since 1.0.0
	 */
	public function load_resources() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Loads JS only if current screen is the Supervisor dashboard.
		if ( supv_is_supervisor_screen() ) {
			wp_register_script( 'supv-js', supv_get_asset_url( 'supervisor' . $suffix . '.js', 'js' ), false, SUPV_VERSION );
			wp_enqueue_script( 'supv-js' );
		}

		// Loads the CSS.
		wp_register_style( 'supv-css', supv_get_asset_url( 'supervisor' . $suffix . '.css', 'css' ), false, SUPV_VERSION );
		wp_enqueue_style( 'supv-css' );
	}

	/**
	 * Adds a menu page on WordPress Dashboard.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {

		$this->hookname = add_menu_page( 'Supervisor', 'Supervisor', 'manage_options', 'supervisor-wp', [ $this, 'admin_page' ], 'none', 200 );
	}

	/**
	 * Loads the admin page view.
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {

		( new DashboardView() )->output();
	}

	/**
	 * Loads the admin notices.
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		if ( get_option( self::DISABLE_NOTICES_OPTION ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! preg_match( '/^(' . $this->hookname . '|dashboard)$/', $screen->id ) ) {
			return;
		}

		$notices = [
			HTTPSView::class,
			SSLView::class,
		];

		$notices_transient = get_transient( self::HIDE_NOTICES_TRANSIENT );

		foreach ( $notices as $notice ) {
			if ( ! isset( $notices_transient[ $notice ] ) ) {
				( new $notice() )->output();
			}
		}
	}
}
