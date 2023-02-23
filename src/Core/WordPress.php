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

		add_action( 'wp_loaded', [ $this, 'set_wp_auto_update_policy' ] );
	}

	/**
	 * Sets the WordPress auto update policy.
	 *
	 * @since {VERSION}
	 */
	public function set_wp_auto_update_policy() { // phpcs:ignore WPForms.PHP.HooksMethod.InvalidPlaceForAddingHooks

		$policy = $this->get_auto_update_policy();

		if ( ! empty( $policy ) && preg_match( '/^(minor|major|dev|disabled)$/', $policy ) ) {
			if ( $policy === 'disabled' ) {
				add_filter( 'automatic_updater_disabled', '__return_true' );
			} else {
				add_filter( 'allow_' . $policy . '_auto_core_updates', '__return_true' );
			}
		}
	}

	/**
	 * Changes the auto update policy value which could be 'disabled', 'minor', 'major' or 'dev'.
	 *
	 * @param string $option_value The auto update policy.
	 *
	 * @since {VERSION}
	 */
	public function change_auto_update_policy( $option_value ) {

		if ( $this->get_auto_update_policy() ) {
			update_option( self::CORE_AUTO_UPDATE_OPTION, $option_value );
		}
	}

	/**
	 * Returns the selected auto update policy.
	 *
	 * @since {VERSION}
	 *
	 * @return string|bool It can assume 'disabled', 'minor', 'major', 'dev' or false.
	 */
	public function get_auto_update_policy() {

		if ( $this->is_auto_update_constant_enabled() ) {
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
	private function is_auto_update_constant_enabled() {

		return defined( 'AUTOMATIC_UPDATER_DISABLED' ) || defined( 'WP_AUTO_UPDATE_CORE' );
	}
}
