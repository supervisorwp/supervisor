<?php
namespace SUPV;

use SUPV\Utils\Install;
use SUPV\Utils\Upgrade;

/**
 * The Loader class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class Loader {
	/**
	 * The Admin\Loader object.
	 *
	 * @since 1.0.0
	 *
	 * @var Admin\Loader
	 */
	private $admin;

	/**
	 * The Core\Loader object.
	 *
	 * @since 1.0.0
	 *
	 * @var Core\Loader
	 */
	private $core;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * WordPress actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		add_action( 'plugins_loaded', [ $this, 'setup' ] );
	}

	/**
	 * Load the plugin classes.
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		// Loads the helper functions.
		require_once SUPV_PLUGIN_DIR . '/inc/helpers.php';

		// Loads the Upgrade class.
		new Upgrade();

		// Loads the plugin classes.
		$this->core  = new Core\Loader();
		$this->admin = new Admin\Loader();

		// Loads the plugin hooks.
		new Install();
	}

	/**
	 * Get the Core\Loader object.
	 *
	 * @since 1.0.0
	 *
	 * @return Core\Loader
	 */
	public function core() {

		return $this->core;
	}

	/**
	 * Get the Admin\Loader object.
	 *
	 * @since 1.0.0
	 *
	 * @return Admin\Loader
	 */
	public function admin() {

		return $this->admin;
	}
}
