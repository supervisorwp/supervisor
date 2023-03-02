<?php
namespace SUPV\Core;

use WP_Error;

/**
 * The SecureLogin class.
 *
 * @package supervisor
 * @since {VERSION}
 */
class SecureLogin {

	/**
	 * Constructor.
	 *
	 * @since {VERSION}
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * WordPress actions and filters.
	 *
	 * @since {VERSION}
	 */
	public function hooks() {

		add_filter( 'wp_authenticate_user', [ $this, 'wp_authenticate_user' ], 10, 2 );
	}

	/**
	 * Filters the authentication.
	 *
	 * @since {VERSION}
	 *
	 * @param object $user     WP_User or WP_Error object if a previous callback failed authentication.
	 * @param string $password Password to check against the user.
	 */
	public function wp_authenticate_user( $user, $password ) {

		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if ( ! $this->is_limit_reached() && $this->is_ip_allowed() ) {
			return $user;
		}

		$user_ip = supv_get_user_ip();

		return $user;
	}

	public function is_limit_reached() {

		return false;
	}

	public function is_ip_allowed() {

		return true;
	}
}
