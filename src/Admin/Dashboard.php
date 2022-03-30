<?php
namespace SUPV\Admin;

/**
 * The Dashboard class.
 *
 * @package supervisor
 *
 * @since 1.0.0
 */
class Dashboard {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 5 );
	}

	/**
	 * Adds a menu page on WordPress Dashboard.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {
		add_menu_page( 'Supervisor', 'Supervisor', 'manage_options', 'supervisor', [ $this, 'admin_page' ], 'none', 200 );
	}

	/**
	 * Loads the admin page view.
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {
		$this->view( 'admin/dashboard' );
	}

	/**
	 * Includes a view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the view to load.
	 */
	private function view( $name ) {
		$file = SUPV_PLUGIN_DIR . '/resources/views/' . $name . '.php';

		if ( file_exists( $file ) ) {
			include $file;
		}
	}
}
