<?php
namespace SUPV\Core;

/**
 * The Loader class.
 *
 * @package supervisor
 *
 * @since 1.0.0
 */
class Loader {
	/**
	 * The Autoload object.
	 *
	 * @since 1.0.0
	 *
	 * @var Autoload
	 */
	public $autoload;

	/**
	 * The SSL object.
	 *
	 * @since 1.0.0
	 *
	 * @var SSL
	 */
	public $ssl;

	/**
	 * The Transients object.
	 *
	 * @since 1.0.0
	 *
	 * @var Transients
	 */
	public $transients;

	/**
	 * The Updates object.
	 *
	 * @since 1.0.0
	 *
	 * @var Updates
	 */
	public $updates;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->loader();
	}

	/**
	 * Initialize the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function loader() {
		// Loads the Core classes.
		$this->autoload   = new Autoload();
		$this->transients = new Transients();
		$this->ssl        = new SSL();
		$this->updates    = new Updates();
	}
}
