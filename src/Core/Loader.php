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
	 * The SecureLogin object.
	 *
	 * @since {VERSION}
	 *
	 * @var SecureLogin
	 */
	private $secure_login;

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
	 * The WordPress object.
	 *
	 * @since 1.0.0
	 *
	 * @var WordPress
	 */
	private $wordpress;

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
	 * Get the SecureLogin object.
	 *
	 * @since {VERSION}
	 *
	 * @return SecureLogin
	 */
	public function secure_login() {

		return $this->secure_login;
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
	 * Get the WordPress object.
	 *
	 * @since 1.2.0
	 *
	 * @return WordPress
	 */
	public function wordpress() {

		return $this->wordpress;
	}

	/**
	 * Set all the things up.
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		// Loads the SecureLogin class globally.
		$this->secure_login = new SecureLogin();

		// Loads the plugin classes only if you are using Roles or WP-CLI.
		if ( is_admin() || supv_is_doing_wpcli() ) {
			$this->server     = new Server();
			$this->ssl        = new SSL();
			$this->autoload   = new Autoload();
			$this->transients = new Transients();
			$this->wordpress  = new WordPress();
		}
	}
}
