<?php
namespace SUPV\Admin\Views\Cards;

use SUPV\Admin\Views\AbstractView;
use SUPV\Core\SecureLogin;

/**
 * The SecureLoginCardView class.
 *
 * @package supervisor
 * @since {VERSION}
 */
final class SecureLoginCardView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since {VERSION}
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text"><?php esc_html_e( 'Secure Login Page', 'supervisor' ); ?></div>
			</div>

			<div class="content">
				<p><?php esc_html_e( 'By default, the login page on your WordPress site can be accessed by anyone, including hackers who can use automated tools to launch Brute Force attacks against it.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'These attacks involve trying numerous combinations of usernames and passwords until the correct credentials are found, potentially granting hackers access to your website.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'To enhance the security of your website, we recommend enabling our Brute Force Protection feature.', 'supervisor' ); ?></p>

				<p class="supv-secure-login-switch">
					<?php
					$args = [
						'id'      => 'supv-secure-login-switch',
						'text'    => 'Enable Brute Force Protection',
						'value'   => 1,
						'checked' => supv()->core()->secure_login()->is_enabled(),
					];

					/**
					 * Outputs the switch component.
					 *
					 * @since {VERSION}
					 *
					 * @param array $args The component arguments.
					 */
					do_action( 'supv_admin_views_components_switch', $args ); // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
					?>
				</p>

				<div id="supv-secure-login-settings" class="supv-secure-login-settings">
					<?php if ( supv()->core()->secure_login()->is_enabled() ) : ?>
						<?php $this->output_settings(); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the Brute Force protection settings.
	 *
	 * @since {VERSION}
	 */
	public function output_settings() {

		$settings = supv()->core()->secure_login()->get_settings();
		?>
		<form id="supv-secure-login-settings-form" name="supv-secure-login-settings-form">
			<ul>
				<li><?php esc_html_e( 'Max Retries', 'supervisor' ); ?></li>
				<li><input name="supv-field-max-retries" type="number" value="<?php echo ! empty( $settings['max-retries'] ) ? esc_attr( $settings['max-retries'] ) : esc_attr( SecureLogin::DEFAULT_SETTINGS['max-retries'] ); ?>" /></li>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'The maximum number of allowed failed attempts before the user is locked out.', 'supervisor' ); ?>
				</p>
			</ul>
			<ul>
				<li><?php esc_html_e( 'Lockout Time', 'supervisor' ); ?></li>
				<li><input name="supv-field-lockout-time" type="number" value="<?php echo ! empty( $settings['lockout-time'] ) ? esc_attr( $settings['lockout-time'] ) : esc_attr( SecureLogin::DEFAULT_SETTINGS['lockout-time'] ); ?>" /> <?php esc_html_e( 'minutes', 'supervisor' ); ?></li>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'This specifies the duration (in minutes) for which a user will be locked out after reaching the maximum number of login retries.', 'supervisor' ); ?>
				</p>
			</ul>
			<ul>
				<li><?php esc_html_e( 'Max Lockouts', 'supervisor' ); ?></li>
				<li><input name="supv-field-max-lockouts" type="number" value="<?php echo ! empty( $settings['max-lockouts'] ) ? esc_attr( $settings['max-lockouts'] ) : esc_attr( SecureLogin::DEFAULT_SETTINGS['max-lockouts'] ); ?>" /></li>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'The maximum number of times a user can be locked out for the standard lockout time, after which they will be locked out for an extended period.', 'supervisor' ); ?>
				</p>
			</ul>
			<ul>
				<li><?php esc_html_e( 'Extended Lockout', 'supervisor' ); ?></li>
				<li><input name="supv-field-extended-lockout" type="number" value="<?php echo ! empty( $settings['extended-lockout'] ) ? esc_attr( $settings['extended-lockout'] ) : esc_attr( SecureLogin::DEFAULT_SETTINGS['extended-lockout'] ); ?>" /> <?php esc_html_e( 'hours', 'supervisor' ); ?></li>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'This specifies the duration (in hours) for which a user will be locked out after exceeding the maximum number of standard lockouts.', 'supervisor' ); ?>
				</p>
			</ul>
			<ul>
				<li><?php esc_html_e( 'Reset Retries', 'supervisor' ); ?></li>
				<li><input name="supv-field-reset-retries" type="number" value="<?php echo ! empty( $settings['reset-retries'] ) ? esc_attr( $settings['reset-retries'] ) : esc_attr( SecureLogin::DEFAULT_SETTINGS['reset-retries'] ); ?>" /> <?php esc_html_e( 'hours', 'supervisor' ); ?></li>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'After the specified number of hours, the failed login attempts for a user will be reset.', 'supervisor' ); ?>
				</p>
			</ul>

			<div class="supv-ctas-left">
				<input name="supv-field-enabled" id="supv-field-enabled" type="hidden" value="1" />

				<button type="submit" form="supv-secure-login-settings-form" class="supv-button" id="supv-btn-secure-login-settings-save">
					<?php esc_html_e( 'Save Settings', 'supervisor' ); ?>
				</button>
			</div>
		</form>
		<?php
	}
}
