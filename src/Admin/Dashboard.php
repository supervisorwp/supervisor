<?php
namespace SUPV\Admin;

use SUPV\Admin\Views\Notices\HTTPSView;
use SUPV\Admin\Views\Notices\SSLView;
use SUPV\Admin\Views\Pages\AutoloadView;
use SUPV\Admin\Views\Pages\DashboardView;

/**
 * The Dashboard class.
 *
 * @package supervisor
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

		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_init', [ $this, 'load_resources' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ], 5 );
		}

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );

		add_action( 'admin_head', [ $this, 'hide_admin_notices' ] );
	}

	/**
	 * Loads the admin resources.
	 *
	 * @since 1.0.0
	 */
	public function load_resources() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Loads JS only if current screen is the Supervisor dashboard.
		wp_register_script( 'supv-js', supv_get_asset_url( 'supervisor' . $suffix . '.js', 'js' ), false, SUPV_VERSION );

		wp_localize_script(
			'supv-js',
			'supv',
			[
				'loading' => esc_html__( 'Loading...', 'supervisor' ),
			]
		);

		wp_enqueue_script( 'supv-js' );

		// Loads the CSS.
		wp_register_style( 'supv-css', supv_get_asset_url( 'supervisor' . $suffix . '.css', 'css' ), false, SUPV_VERSION );
		wp_enqueue_style( 'supv-css' );
	}

	/**
	 * Adds a menu and sub menu pages on WordPress Dashboard.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {

		/**
		 * Filters the Supervisor menu position.
		 *
		 * @since {VERSION}
		 *
		 * @param float|int $position The position in the menu order.
		 */
		$menu_position = apply_filters( 'supv_admin_dashboard_menu_position', 200 );

		$this->hookname = add_menu_page( 'Dashboard', 'Supervisor', 'manage_options', 'supervisor', [ $this, 'dashboard_page' ], 'none', $menu_position );
	}

	/**
	 * Loads the admin page view.
	 *
	 * @since 1.0.0
	 */
	public function dashboard_page() {

		( new DashboardView() )->output();
	}

	/**
	 * Loads the autoload options page view.
	 *
	 * @since 1.0.0
	 */
	public function autoload_page() {

		( new AutoloadView() )->output();
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

		if ( $screen->id !== 'dashboard' ) {
			return;
		}

		$notices = [
			HTTPSView::class => 'https',
			SSLView::class   => 'ssl',
		];

		$notices_transient = get_transient( self::HIDE_NOTICES_TRANSIENT );

		foreach ( $notices as $notice => $name ) {
			if ( ! isset( $notices_transient[ $name ] ) ) {
				( new $notice() )->output();
			}
		}
	}

	/**
	 * Hides all the admin notices on the Supervisor screen.
	 *
	 * @since 1.1.0
	 */
	public function hide_admin_notices() {

		if ( ! supv_is_supervisor_screen() ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'network_admin_notices' );
	}
}
