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
	 * Autoload values that are considered active.
	 *
	 * @since 1.3.2
	 *
	 * @var array
	 */
	const ACTIVE_VALUES = [ 'yes', 'on', 'auto-on', 'auto' ];

	/**
	 * Autoload values that are considered deactivated.
	 *
	 * @since 1.3.2
	 *
	 * @var array
	 */
	const DEACTIVATED_VALUES = [ 'no', 'off', 'auto-off' ];

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
		 * @since 1.2.0
		 *
		 * @param int $limit The total of autoload options that should be returned.
		 */
		$limit = apply_filters( 'supv_core_autoload_get_limit', 10 );

		if ( $limit < 1 ) {
			$limit = 10;
		}

		// Prepare the autoload values for the SQL query.
		$active_values = "'" . implode( "','", self::ACTIVE_VALUES ) . "'";

		// Get the biggest autoload options.
		$options = [];

		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->get_results(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT option_name, ROUND(LENGTH(option_value) / POWER(1024,2), 3) AS size FROM $wpdb->options WHERE autoload IN ($active_values) AND option_name NOT REGEXP '^_(site_)?transient' ORDER BY size DESC LIMIT 0,%d;",
				$limit
			)
		);

		foreach ( $result as $option ) {
			$options[ $option->option_name ] = (float) $option->size;
		}

		/**
		 * Filters the list of biggest autoload options.
		 *
		 * @since 1.2.0
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

		// Prepare the autoload values for the SQL query.
		$active_values = "'" . implode( "','", self::ACTIVE_VALUES ) . "'";

		// Get the autoload options stats.
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->get_row(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"SELECT COUNT(*) AS count, SUM(LENGTH(option_value)) / POWER(1024,2) AS size FROM $wpdb->options WHERE autoload IN ($active_values) AND option_name NOT REGEXP %s;",
				'^_(site_)?transient'
			)
		);

		$stats = [
			'count' => (int) ( $result->count ?? 0 ),
			'size'  => (float) ( $result->size ?? 0.0 ),
		];

		/**
		 * Filters the autoload options stats.
		 *
		 * @since 1.2.0
		 *
		 * @param array $stats Array with the total count and size of the autoload options.
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
			 * @since 1.2.0
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
		 * @since 1.2.0
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

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$autoload = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT autoload FROM $wpdb->options WHERE option_name = %s;",
				$option_name
			)
		);

		return in_array( $autoload, self::DEACTIVATED_VALUES, true );
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
		 * @since 1.2.0
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
	private function update( $option_name, $autoload = 'no', $logging = true ) {

		if ( ! get_option( $option_name ) ) {
			return false;
		}

		$should_autoload = in_array( $autoload, self::ACTIVE_VALUES, true );

		// Update option's autoload value.
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->query(
			$wpdb->prepare(
				"UPDATE $wpdb->options SET autoload = %s WHERE option_name = %s;",
				$this->determine_autoload_value( $autoload ),
				$option_name
			)
		);

		if ( empty( $result ) ) {
			return false;
		}

		// Validate the update was successful.
		if ( ! $this->validate_autoload_state( $option_name, $should_autoload ) ) {
			return false;
		}

		if ( $logging ) {
			$this->update_history( $option_name, $should_autoload );
		}

		return $result;
	}

	/**
	 * Validates that the autoload state matches expectations.
	 *
	 * @since 1.3.2
	 *
	 * @param string $option_name     The option name.
	 * @param bool   $should_autoload Whether the option should be autoloaded.
	 *
	 * @return bool True if state is valid, false otherwise.
	 */
	private function validate_autoload_state( $option_name, $should_autoload ) {

		$is_deactivated = $this->is_deactivated( $option_name );

		if ( $should_autoload && $is_deactivated ) {
			return false;
		}

		if ( ! $should_autoload && ! $is_deactivated ) {
			return false;
		}

		return true;
	}

	/**
	 * Updates the deactivation history based on autoload action.
	 *
	 * @since 1.3.2
	 *
	 * @param string $option_name     The option name.
	 * @param bool   $should_autoload Whether the option should be autoloaded.
	 */
	private function update_history( $option_name, $should_autoload ) {

		if ( $should_autoload ) {
			$this->remove_from_history( $option_name );
		} else {
			$this->add_to_history( $option_name );
		}
	}

	/**
	 * Removes an option from the deactivation history.
	 *
	 * @since 1.3.2
	 *
	 * @param string $option_name The option name.
	 */
	private function remove_from_history( $option_name ) {

		$history = get_option( self::DEACTIVATION_HISTORY_OPTION );

		if ( ! is_array( $history ) ) {
			return;
		}

		if ( isset( $history[ $option_name ] ) ) {
			unset( $history[ $option_name ] );

			update_option( self::DEACTIVATION_HISTORY_OPTION, $history );
		}
	}

	/**
	 * Adds an option to the deactivation history.
	 *
	 * @since 1.3.2
	 *
	 * @param string $option_name The option name.
	 */
	private function add_to_history( $option_name ) {

		$history = $this->get_or_create_history();

		$history[ $option_name ] = time();

		update_option( self::DEACTIVATION_HISTORY_OPTION, $history );
	}

	/**
	 * Gets the deactivation history or creates it if it doesn't exist.
	 *
	 * @since 1.3.2
	 *
	 * @return array The deactivation history array.
	 */
	private function get_or_create_history() {

		$history = get_option( self::DEACTIVATION_HISTORY_OPTION );

		if ( ! $history ) {
			add_option(
				self::DEACTIVATION_HISTORY_OPTION,
				[],
				'',
				$this->determine_autoload_value( 'no' )
			);

			return [];
		}

		return is_array( $history ) ? $history : [];
	}

	/**
	 * Determines the autoload value based on the provided autoload string.
	 *
	 * @since 1.3.2
	 *
	 * @param string $autoload The autoload value to determine.
	 *
	 * @return string The appropriate autoload value for the current WordPress version.
	 */
	private function determine_autoload_value( $autoload ) {

		if ( ! in_array( $autoload, array_merge( self::ACTIVE_VALUES, self::DEACTIVATED_VALUES ), true ) ) {
			return $autoload;
		}

		$use_legacy_values = ! function_exists( 'wp_determine_option_autoload_value' );

		if ( in_array( $autoload, self::ACTIVE_VALUES, true ) ) {
			return $use_legacy_values ? 'yes' : 'on';
		}

		if ( in_array( $autoload, self::DEACTIVATED_VALUES, true ) ) {
			return $use_legacy_values ? 'no' : 'off';
		}

		return $autoload;
	}
}
