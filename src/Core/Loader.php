<?php
namespace SUPV\Core;

/**
 * The Loader class.
 *
 * @package supervisor
 * @since 1.0.0
 */
class Loader {
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
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->setup();
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
	 * Set all the things up.
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		$this->server = new Server();
		$this->ssl    = new SSL();
	}
}
