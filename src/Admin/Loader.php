<?php
namespace SUPV\Admin;

use SUPV\Admin\Views\ComponentsView;

/**
 * The Loader class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class Loader {
	/**
	 * The AJAX object.
	 *
	 * @since 1.0.0
	 *
	 * @var AJAX
	 */
	private $ajax;

	/**
	 * The Dashboard object.
	 *
	 * @since 1.0.0
	 *
	 * @var Dashboard
	 */
	private $dashboard;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->setup();
	}

	/**
	 * Get the AJAX object.
	 *
	 * @since 1.0.0
	 *
	 * @return AJAX
	 */
	public function ajax() {

		return $this->ajax;
	}

	/**
	 * Get the Dashboard object.
	 *
	 * @since 1.0.0
	 *
	 * @return Dashboard
	 */
	public function dashboard() {

		return $this->dashboard;
	}

	/**
	 * Set all the things up.
	 *
	 * @since 1.0.0
	 */
	private function setup() {

		// Loads the plugin classes only if you are using Dashboard or WP-CLI.
		if ( is_admin() || supv_is_doing_wpcli() ) {
			$this->ajax      = new AJAX();
			$this->dashboard = new Dashboard();

			// Loads the components.
			new ComponentsView();
		}
	}
}
