<?php
namespace SUPV\Utils;

/**
 * The Upgrade class.
 *
 * @package supervisor
 * @since {VERSION}
 */
final class Upgrade {

	/**
	 * Option to store the current plugin version.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const PLUGIN_VERSION_OPTION = 'supv_version';

	/**
	 * Constructor.
	 *
	 * @since {VERSION}
	 */
	public function __construct() {

		$this->maybe_upgrade_db();
	}

	/**
	 * Cleans up the transients after plugin updates.
	 *
	 * @since {VERSION}
	 */
	public function maybe_upgrade_db() {

		$version = get_option( self::PLUGIN_VERSION_OPTION );

		if ( $version !== SUPV_VERSION ) {
			Install::cleanup_plugin_options( true );

			update_option( self::PLUGIN_VERSION_OPTION, SUPV_VERSION );
		}
	}
}
