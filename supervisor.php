<?php
/**
 * Plugin Name: Supervisor
 * Plugin URI:  https://supervisorwp.com
 * Description: A plugin to help improve the performance and security of your WordPress install.
 * Version:     1.2.0
 * Author:      Supervisor Team
 * Author URI:  https://supervisorwp.com/contributors
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Text Domain: supervisor
 *
 * @package supervisor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants.
 *
 * @since 1.0.0
 */
define( 'SUPV', true );
define( 'SUPV_VERSION', '1.2.0' );

define( 'SUPV_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SUPV_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'SUPV_PLUGIN_FILE', __FILE__ );

define( 'SUPV_INC_DIR', SUPV_PLUGIN_DIR . '/includes' );

/**
 * Loads the autoloader.
 *
 * @since 1.0.0
 */
if ( file_exists( SUPV_PLUGIN_DIR . '/vendor/autoload.php' ) ) {
	require_once SUPV_PLUGIN_DIR . '/vendor/autoload.php';
}

/**
 * Plugin loader.
 *
 * @since 1.0.0
 *
 * @return \SUPV\Loader
 */
function supv() {

	static $supv;

	if ( ! $supv ) {
		$supv = new SUPV\Loader();
	}

	return $supv;
}

supv();
