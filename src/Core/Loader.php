<?php
namespace SUPV\Core;

/**
 * The Loader class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class Loader {
	/**
	 * The Autoload object.
	 *
	 * @since 1.0.0
	 *
	 * @var Autoload
	 */
	private $autoload;

	/**
	 * The Server object.
	 *
	 * @since 1.0.0
	 *
	 * @var Server
	 */
	private $server;

	/**
	 * The SSL object.
	 *
	 * @since 1.0.0
	 *
	 * @var SSL
	 */
	private $ssl;

	/**
	 * The Transients object.
	 *
	 * @since 1.0.0
	 *
	 * @var Transients
	 */
	private $transients;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->setup();
	}

	/**
	 * Get the Autoload object.
	 *
	 * @since 1.0.0
	 *
	 * @return Autoload
	 */
	public function autoload() {

		return $this->autoload;
	}

	/**
	 * Get the Server object.
	 *
	 * @since 1.0.0
	 *
	 * @return Server
	 */
	public function server() {

		return $this->server;
	}

	/**
	 * Get the SSL object.
	 *
	 * @since 1.0.0
	 *
	 * @return SSL
	 */
	public function ssl() {

		return $this->ssl;
	}

	/**
	 * Get the Transients object.
	 *
	 * @since 1.0.0
	 *
	 * @return Transients
	 */
	public function transients() {

		return $this->transients;
	}

	/**
	 * Set all the things up.
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		$this->server     = new Server();
		$this->ssl        = new SSL();
		$this->autoload   = new Autoload();
		$this->transients = new Transients();
	}
}
