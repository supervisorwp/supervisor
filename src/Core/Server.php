<?php
namespace SUPV\Core;

/**
 * The Server class.
 *
 * @package supervisor
 * @since 1.0.0
 */
class Server {
	/**
	 * Transient to store the server data.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SERVER_DATA_TRANSIENT = 'supv_server_data';

	/**
	 * Retrieves the server data.
	 *
	 * @since 1.0.0
	 *
	 * @return array The server data.
	 */
	public function get_data() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		global $wpdb;

		$server = get_transient( self::SERVER_DATA_TRANSIENT );

		if ( $server === false ) {
			include ABSPATH . WPINC . '/version.php';

			$php = preg_match( '/^(\d+\.){2}\d+/', phpversion(), $phpversion );

			$db_service = ( preg_match( '/MariaDB/', $wpdb->dbh->server_info ) ) ? 'MariaDB' : 'MySQL';
			$db_version = $wpdb->db_version();

			if ( $db_service === 'MariaDB' ) {
				$db_version = preg_replace( '/[^0-9.].*/', '', $wpdb->get_var( 'SELECT @@version;' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
			}

			$server = [
				'database' => [
					'service' => $db_service,
					'version' => $db_version,
				],
				'php'      => $phpversion[0],
				'wp'       => $wp_version,
				'web'      => [],
			];

			$server_software = ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : false;

			if ( ! empty( $server_software ) ) {
				$matches = [];

				if ( preg_match( '/(apache|nginx)/i', $server_software, $matches ) ) {
					$server['web'] = [
						'service' => strtolower( $matches[0] ),
						'version' => preg_match( '/([0-9]+\.){2}([0-9]+)?/', $server_software, $matches ) ? trim( $matches[0] ) : false,
					];
				} else {
					$server['web'] = [
						'service' => 'Web',
						'version' => $server_software,
					];
				}
			}

			set_transient( self::SERVER_DATA_TRANSIENT, $server, DAY_IN_SECONDS );
		}

		/**
		 * Filters the server data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $server The server data.
		 */
		return apply_filters( 'supv_server_data', $server );
	}

	/**
	 * Retrieves the server IP address.
	 *
	 * @since 1.0.0
	 *
	 * @return string|false The IP address, or false if IP was not found.
	 */
	public function get_ip() {

		// gethostname() was added only on PHP 5.3.
		if ( function_exists( 'gethostname' ) ) {
			$ip = gethostbyname( gethostname() );
		}

		// If ip was not found via gethostbyname(), try to retrieve it from the $_SERVER array.
		if ( empty( $ip ) && ! empty( $_SERVER['SERVER_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) );
		}

		$ip = ! empty( $ip ) && filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : false;

		/**
		 * Filters the server IP.
		 *
		 * @since 1.0.0
		 *
		 * @param array $server The server IP.
		 */
		return apply_filters( 'supv_server_ip', $ip );
	}
}
