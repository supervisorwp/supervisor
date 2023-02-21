<?php
namespace SUPV\Utils;

use SUPV\Core\Autoload;
use SUPV\Core\Server;
use SUPV\Core\SSL;

/**
 * The Install class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class Install {

	/**
	 * All the plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $plugin_options = [
		Autoload::DEACTIVATION_HISTORY_OPTION,
	];

	/**
	 * All the plugin transients.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private static $plugin_transients = [
		Server::DATA_TRANSIENT,
		Server::MIN_REQUIREMENTS_TRANSIENT,
		SSL::AVAILABLE_TRANSIENT,
		SSL::DATA_TRANSIENT,
	];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		register_activation_hook( SUPV_PLUGIN_FILE, [ '\SUPV\Utils\Install', 'plugin_activation' ] );
		register_deactivation_hook( SUPV_PLUGIN_FILE, [ '\SUPV\Utils\Install', 'plugin_deactivation' ] );
		register_uninstall_hook( SUPV_PLUGIN_FILE, [ '\SUPV\Utils\Install', 'plugin_uninstall' ] );
	}

	/**
	 * Plugin activation hook.
	 *
	 * @since 1.0.0
	 */
	public static function plugin_activation() {

		if ( ! supv_is_doing_wpcli() ) {
			supv()->core()->ssl()->is_available();
		}
	}

	/**
	 * Plugin deactivation hook.
	 *
	 * @since 1.0.0
	 */
	public static function plugin_deactivation() {

		self::cleanup_plugin_options( true );
	}

	/**
	 * Plugin uninstall hook.
	 *
	 * @since 1.0.0
	 */
	public static function plugin_uninstall() {

		self::cleanup_plugin_options();
	}

	/**
	 * Removes the plugins options and transients.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $only_transients True if should remove only the transients.
	 */
	private static function cleanup_plugin_options( $only_transients = false ) {

		if ( ! $only_transients ) {
			// Removes the options.
			foreach ( self::$plugin_options as $option ) {
				if ( get_option( $option ) ) {
					delete_option( $option );
				}
			}
		}

		// Removes the transients.
		foreach ( self::$plugin_transients as $transient ) {
			if ( get_transient( $transient ) ) {
				delete_transient( $transient );
			}
		}
	}
}
