<?php
namespace SUPV\Core;

/**
 * The SSL class.
 *
 * @package supervisor
 *
 * @since 1.0.0
 */
class SSL {
	/**
	 * Transient to store the SSL data.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SSL_DATA_TRANSIENT = 'supv_ssl_data';

	/**
	 * Transient to store if SSL is available or not.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SSL_AVAILABLE_TRANSIENT = 'supv_ssl_available';

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
		add_action( 'shutdown', [ $this, 'get_ssl_data' ] );
	}

	/**
	 * Determine if a SSL certificate is available or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if SSL is available.
	 */
	public function is_ssl_available() {
		if ( is_ssl() ) {
			return true;
		}

		$is_available = get_transient( self::SSL_AVAILABLE_TRANSIENT );

		if ( false === $is_available ) {
			$siteurl = parse_url( get_option( 'siteurl' ) );

			if ( empty( $siteurl['host'] ) ) {
				return false;
			}

			$socket = @fsockopen( 'ssl://' . $siteurl['host'], 443, $errno, $errstr, 20 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

			$is_available = ( false != $socket );

			set_transient( self::SSL_AVAILABLE_TRANSIENT, $is_available, DAY_IN_SECONDS );
		}

		return $is_available;
	}

	/**
	 * Determines if a SSL certificate will expire soon.
	 *
	 * @since 1.0.0
	 *
	 * @return int|false Number of days until certificate expiration or false on error.
	 */
	public function is_ssl_expiring() {
		$ssl_data = get_transient( self::SSL_DATA_TRANSIENT );

		if ( false !== $ssl_data && ! empty( $ssl_data['validity']['to'] ) ) {
			$current    = time();
			$expiration = strtotime( $ssl_data['validity']['to'] );

			$diff = intval( floor( $expiration - $current ) / DAY_IN_SECONDS );

			return ( ( $diff <= 15 ) ? $diff : false );
		}

		return false;
	}
}
