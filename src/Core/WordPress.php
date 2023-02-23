<?php
namespace SUPV\Core;

/**
 * The WordPress class.
 *
 * @package supervisor
 * @since {VERSION}
 */
class WordPress {

	/**
	 * Option to store the auto update policy.
	 *
	 * @since {VERSION}
	 *
	 * @var string
	 */
	const CORE_AUTO_UPDATE_OPTION = 'supv_wordpress_auto_update_policy';

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

		add_action( 'wp_loaded', [ $this, 'filter_core_updates' ] );
	}

	/**
	 * Filters the WordPress core updates option.
	 *
	 * @since {VERSION}
	 */
	public function filter_core_updates() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		$core_auto_update_policy = $this->get_core_auto_update_policy();

		if ( $core_auto_update_policy && preg_match( '/^(minor|major|dev|disabled)$/', $core_auto_update_policy ) ) {
			if ( $core_auto_update_policy === 'disabled' ) {
				add_filter( 'automatic_updater_disabled', '__return_true' );
			} else {
				add_filter( 'allow_' . $core_auto_update_policy . '_auto_core_updates', '__return_true' );
			}
		}
	}

	/**
	 * Returns the selected auto update policy.
	 *
	 * @since {VERSION}
	 *
	 * @return string|bool It can assume 'disabled', 'minor', 'major', 'dev' or false.
	 */
	public function get_core_auto_update_policy() {

		if ( $this->is_wp_auto_update_enabled() ) {
			return false;
		}

		return get_option( self::CORE_AUTO_UPDATE_OPTION, 'minor' );
	}

	/**
	 * Determines if WordPress auto update constants are enabled or not.
	 *
	 * @since {VERSION}
	 *
	 * @return boolean True if WordPress auto update constants are available.
	 */
	public function is_wp_auto_update_enabled() {

		return ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) || defined( 'WP_AUTO_UPDATE_CORE' ) );
	}

	/**
	 * Sets the wp-healthcheck auto update option value which could be 'disabled', 'minor', 'major' or 'dev'.
	 *
	 * @param string $option_value Auto update value.
	 *
	 * @since {VERSION}
	 */
	public function set_core_auto_update_option( $option_value ) {

		$core_auto_update_option = get_option( self::CORE_AUTO_UPDATE_OPTION );

		if ( $this->is_wp_auto_update_enabled() && $core_auto_update_option ) {
			delete_option( self::CORE_AUTO_UPDATE_OPTION );
		}

		update_option( self::CORE_AUTO_UPDATE_OPTION, $option_value );
	}
}
