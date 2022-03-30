<?php
/**
 * Plugin Name: Supervisor
 * Plugin URI:  https://supervisor-wp.com
 * Description: Checks the health and security of your WordPress install.
 * Version:     1.0.0
 * Author:      Supervisor Team
 * Author URI:  https://supervisor-wp.com/contributors
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Text Domain: supv
 * Domain Path: /languages
 *
 * @package supervisor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SUPV', true );
define( 'SUPV_VERSION', '1.0.0' );
define( 'SUPV_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SUPV_PLUGIN_URL', plugins_url( '', __FILE__ ) );

define( 'SUPV_INC_DIR', SUPV_PLUGIN_DIR . '/includes' );

/**
 * Plugin loader.
 *
 * @since 1.0.0.
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

require_once SUPV_PLUGIN_DIR . '/src/Loader.php';
supv();
