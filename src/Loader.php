<?php
namespace SUPV;

/**
 * The Loader class.
 *
 * @package supervisor
 *
 * @since 1.0.0
 */
class Loader {
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
		add_action( 'plugins_loaded', [ $this, 'loader' ] );
	}

	/**
	 * Load the plugin classes.
	 *
	 * @since 1.0.0
	 */
	public function loader() {
		// Autoload classes.
		require_once SUPV_PLUGIN_DIR . '/vendor/autoload.php';

		// Loads the CLI class.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
		}
	}
}
