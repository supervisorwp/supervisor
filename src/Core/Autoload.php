<?php
namespace SUPV\Core;

/**
 * The Autoload class.
 *
 * @package supervisor
 * @since 1.0.0
 */
class Autoload {
	/**
	 * Option to store the history of deactivated autoload options.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const DEACTIVATION_HISTORY_OPTION = 'supv_autoload_deactivation_history';

	/**
	 * Returns the biggest WordPress autoload options.
	 *
	 * @since 1.0.0
	 *
	 * @return array The name and size of the biggest autoload options.
	 */
	public function get() {

		/**
		 * Filters the total of autoload options that should be returned.
		 *
		 * @since {VERSION}
		 *
		 * @param int $limit The total of autoload options that should be returned.
		 */
		$limit = apply_filters( 'supv_core_autoload_get_limit', 10 );

		if ( $limit < 1 ) {
			$limit = 10;
		}

		global $wpdb;

		$options = [];

		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT option_name, ROUND(LENGTH(option_value) / POWER(1024,2), 3) AS size FROM $wpdb->options WHERE autoload = 'yes' AND option_name NOT REGEXP '^_(site_)?transient' ORDER BY size DESC LIMIT 0,%d;",
				$limit
			)
		);

		foreach ( $result as $option ) {
			$options[ $option->option_name ] = (float) $option->size;
		}

		/**
		 * Filters the list of biggest autoload options.
		 *
		 * @since {VERSION}
		 *
		 * @param array $options The list of biggest autoload options.
		 */
		return apply_filters( 'supv_core_autoload_get', $options );
	}

	/**
	 * Returns the WordPress autoload options count and size.
	 *
	 * @since 1.0.0
	 *
	 * @return array Stats of the autoload options.
	 */
	public function get_stats() {

		global $wpdb;

		$result = $wpdb->get_row( "SELECT COUNT(*) AS count, SUM(LENGTH(option_value)) / POWER(1024,2) AS size FROM $wpdb->options WHERE autoload = 'yes' AND option_name NOT REGEXP '^_(site_)?transient';" );

		$count = (int) $result->count;
		$size  = (float) $result->size;

		$stats = [
			'count' => $count,
			'size'  => $size,
		];

		/**
		 * Filters the autoload options stats.
		 *
		 * @since {VERSION}
		 *
		 * @param array $stats Array with the total count and size of the stats.
		 */
		return apply_filters( 'supv_core_autoload_stats', $stats );
	}

	/**
	 * Returns the autoload options deactivated via Supervisor.
	 *
	 * @since 1.0.0
	 *
	 * @return array|false Name and timestamp of the options or false if none.
	 */
	public function get_history() {

		$history = get_option( self::DEACTIVATION_HISTORY_OPTION );

		if ( $history ) {
			$updated = false;

			/**
			 * Filters for how long a deactivated autoload option will remain in the history.
			 *
			 * @since {VERSION}
			 *
			 * @param int $timestamp The expiration timestamp. Any options with deactivation timestamp older than expiration timestamp will be removed from history. False if should not expire.
			 */
			$expiration = apply_filters( 'supv_core_autoload_history_expiration_timestamp', strtotime( '-4 weeks' ) );

			foreach ( $history as $name => $timestamp ) {
				if ( ! get_option( $name ) || ( get_option( $name ) && $timestamp < $expiration ) ) {
					unset( $history[ $name ] );

					$updated = true;
				}
			}

			if ( $updated ) {
				update_option( self::DEACTIVATION_HISTORY_OPTION, $history );
			}

			$history = array_reverse( $history, true );
		}

		/**
		 * Filters the history of deactivated autoload options.
		 *
		 * @since {VERSION}
		 *
		 * @param array $history List of deactivated options.
		 */
		return apply_filters( 'supv_core_autoload_history', $history );
	}

	/**
	 * Determine if an option is set to autoload or not.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The option name.
	 *
	 * @return boolean True if autoload is disabled.
	 */
	public function is_deactivated( $option_name ) {

		global $wpdb;

		$autoload = $wpdb->get_var( $wpdb->prepare( "SELECT autoload FROM $wpdb->options WHERE option_name = %s;", $option_name ) );

		return ( $autoload === 'no' );
	}

	/**
	 * Determine if an option is a WP core one or not.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The option name.
	 *
	 * @return boolean True if it is a WP core option.
	 */
	public function is_core_option( $option_name ) {

		$is_core_option = false;

		$wp_opts_file = SUPV_PLUGIN_DIR . '/assets/wp_options.json';

		if ( file_exists( $wp_opts_file ) ) {
			$wp_opts = json_decode( file_get_contents( $wp_opts_file ) );

			$is_core_option = in_array( $option_name, $wp_opts, true );
		}

		/**
		 * Filters if a given option is a WordPress core option or not.
		 *
		 * @since {VERSION}
		 *
		 * @param bool   $is_core_option True if it is a WP core option.
		 * @param string $option_name    The option name.
		 */
		return apply_filters( 'supv_core_autoload_is_core_option', $is_core_option, $option_name );
	}

	/**
	 * Deactivates an autoload option.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $option_name The name of the option to disable.
	 * @param boolean $logging     Save deactivation to history.
	 *
	 * @return int|false Number of affected rows or false on error.
	 */
	public function deactivate( $option_name, $logging = true ) {

		return $this->update( $option_name, 'no', $logging );
	}

	/**
	 * Reactivates an autoload option that was disabled previously.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option to disable.
	 *
	 * @return int|false Number of affected rows or false on error.
	 */
	public function reactivate( $option_name ) {

		return $this->update( $option_name, 'yes' );
	}

	/**
	 * Updates the autoload value for the given option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option to disable.
	 * @param string $autoload    The new value for the autoload field. Only 'yes' or 'no'.
	 * @param string $logging     Save deactivation to history.
	 *
	 * @return int|false Number of affected rows or false on error.
	 */
	private function update( $option_name, $autoload = 'no', $logging = true ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded,Generic.Metrics.NestingLevel.MaxExceeded

		global $wpdb;

		if ( ! get_option( $option_name ) ) {
			return false;
		}

		$should_autoload = ( $autoload === 'yes' );

		// update option's autoload value to $autoload.
		$result = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->options SET autoload = %s WHERE option_name LIKE %s;", $autoload, $option_name ) );

		if ( empty( $result ) ) {
			return false;
		}

		if ( $should_autoload && $this->is_deactivated( $option_name ) ) {
			return false;
		}

		if ( ! $should_autoload && ! $this->is_deactivated( $option_name ) ) {
			return false;
		}

		if ( ! $logging ) {
			return $result;
		}

		$updated = false;

		if ( $should_autoload ) {
			// removes option name and timestamp from history.
			$history = get_option( self::DEACTIVATION_HISTORY_OPTION );

			if ( $history && is_array( $history ) ) {
				foreach ( $history as $name => $timestamp ) {
					if ( get_option( $name ) && $name === $option_name ) {
						unset( $history[ $name ] );

						$updated = true;

						break;
					}
				}
			}
		} else {
			// adds option name and timestamp to history.
			if ( ! get_option( self::DEACTIVATION_HISTORY_OPTION ) ) {
				add_option( self::DEACTIVATION_HISTORY_OPTION, '', '', 'no' );
			}

			$history = get_option( self::DEACTIVATION_HISTORY_OPTION );

			if ( ! is_array( $history ) ) {
				$history = [];
			}

			$history[ $option_name ] = time();
		}

		if ( ! $should_autoload || $updated ) {
			update_option( self::DEACTIVATION_HISTORY_OPTION, $history );
		}

		return $result;
	}
}
