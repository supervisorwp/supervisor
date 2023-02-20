<?php
namespace SUPV\Core;

/**
 * The SSL class.
 *
 * @package supervisor
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

		$this->hooks();
	}

	/**
	 * Initialize the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		add_action( 'shutdown', [ $this, 'get_data' ] );
	}

	/**
	 * Retrieves some information from SSL certificate associated with site url.
	 *
	 * @since 1.0.0
	 *
	 * @return array|false SSL data or false on error.
	 */
	public function get_data() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( ! is_ssl() && ( ! defined( 'WP_CLI' ) || ! WP_CLI ) ) {
			return false;
		}

		$ssl_data = get_transient( self::SSL_DATA_TRANSIENT );

		if ( $ssl_data === false ) {
			$context = stream_context_create(
				[
					'ssl' => [
						'capture_peer_cert' => true,
						'verify_peer'       => false,
					],
				]
			);

			$siteurl = wp_parse_url( get_option( 'siteurl' ) );

			if ( empty( $siteurl['host'] ) ) {
				return false;
			}

			$socket = @stream_socket_client( 'ssl://' . $siteurl['host'] . ':443', $errno, $errstr, 20, STREAM_CLIENT_CONNECT, $context ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

			if ( ! $socket ) {
				set_transient( self::SSL_DATA_TRANSIENT, [], DAY_IN_SECONDS );

				return false;
			}

			$params = stream_context_get_params( $socket );

			if ( ! empty( $params['options']['ssl']['peer_certificate'] ) ) {
				$certificate = openssl_x509_parse( $params['options']['ssl']['peer_certificate'] );

				$ssl_data = [
					'common_name' => ! empty( $certificate['subject']['CN'] ) ? $certificate['subject']['CN'] : '',
					'issuer'      => ! empty( $certificate['issuer']['CN'] ) ? $certificate['issuer']['CN'] : '',
					'validity'    => [
						'from' => gmdate( 'Y-m-d H:i:s', $certificate['validFrom_time_t'] ),
						'to'   => gmdate( 'Y-m-d H:i:s', $certificate['validTo_time_t'] ),
					],
				];

				set_transient( self::SSL_DATA_TRANSIENT, $ssl_data, DAY_IN_SECONDS );
			}
		}

		return $ssl_data;
	}

	/**
	 * Determine if a SSL certificate is available or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if SSL is available.
	 */
	public function is_available() {

		if ( is_ssl() ) {
			return true;
		}

		$is_available = get_transient( self::SSL_AVAILABLE_TRANSIENT );

		if ( $is_available === false ) {
			$siteurl = wp_parse_url( get_option( 'siteurl' ) );

			if ( empty( $siteurl['host'] ) ) {
				return false;
			}

			$socket = @fsockopen( 'ssl://' . $siteurl['host'], 443, $errno, $errstr, 20 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.WP.AlternativeFunctions.file_system_read_fsockopen

			$is_available = ( $socket !== false );

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
	public function is_expiring() {

		$ssl_data = get_transient( self::SSL_DATA_TRANSIENT );

		if ( $ssl_data !== false && ! empty( $ssl_data['validity']['to'] ) ) {
			$current    = time();
			$expiration = strtotime( $ssl_data['validity']['to'] );

			$diff = intval( floor( $expiration - $current ) / DAY_IN_SECONDS );

			return ( ( $diff <= 15 ) ? $diff : false );
		}

		return false;
	}
}
