<?php
namespace SUPV\Admin\Views\Cards;

use SUPV\Admin\Views\AbstractView;

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
				<p><?php esc_html_e( 'By default, the login page on your WordPress site can be accessed by anyone, including hackers who can use automated tools to launch brute force attacks against it.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'Such attacks involve trying numerous combinations of usernames and passwords until the correct credentials are found, giving hackers access to your website.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'To enhance the security of your website, you can enforce rules that restrict the number of login attempts.', 'supervisor' ); ?></p>

				<p class="supv-secure-login-switch">
					<?php
					$args = [
						'id'    => 'supv-secure-login-restrict-attempts-switch',
						'text'  => 'Restrict the Number of Login Attempts',
						'value' => 1,
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

				<div id="supv-secure-login-restrict-attempts-settings" class="supv-secure-login-restrict-attempts-settings"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the restrict login attempts settings.
	 *
	 * @since {VERSION}
	 */
	public function output_restrict_login_attempts() {

		?>
		<ul>
			<li><?php esc_html_e( 'Max Retries', 'supervisor' ); ?></li>
			<li><input type="number" value="5" /></li>

			<p class="box-info">
				<span class="supv-icon-info"></span>
				<?php esc_html_e( 'The maximum number of allowed failed attempts before the user is locked out.', 'supervisor' ); ?>
			</p>
		</ul>
		<ul>
			<li><?php esc_html_e( 'Lockout Time', 'supervisor' ); ?></li>
			<li><input type="number" value="10" /> <?php esc_html_e( 'minutes', 'supervisor' ); ?></li>

			<p class="box-info">
				<span class="supv-icon-info"></span>
				<?php esc_html_e( 'This specifies the duration (in minutes) for which a user will be locked out after reaching the maximum number of login retries.', 'supervisor' ); ?>
			</p>
		</ul>
		<ul>
			<li><?php esc_html_e( 'Max Lockouts', 'supervisor' ); ?></li>
			<li><input type="number" value="3" /></li>

			<p class="box-info">
				<span class="supv-icon-info"></span>
				<?php esc_html_e( 'The maximum number of times a user can be locked out for the standard lockout time, after which they will be locked out for an extended period.', 'supervisor' ); ?>
			</p>
		</ul>
		<ul>
			<li><?php esc_html_e( 'Extended Lockout', 'supervisor' ); ?></li>
			<li><input type="number" value="12" /> <?php esc_html_e( 'hours', 'supervisor' ); ?></li>

			<p class="box-info">
				<span class="supv-icon-info"></span>
				<?php esc_html_e( 'This specifies the duration (in hours) for which a user will be locked out after exceeding the maximum number of standard lockouts.', 'supervisor' ); ?>
			</p>
		</ul>
		<ul>
			<li><?php esc_html_e( 'Reset Retries', 'supervisor' ); ?></li>
			<li><input type="number" value="12" /> <?php esc_html_e( 'hours', 'supervisor' ); ?></li>

			<p class="box-info">
				<span class="supv-icon-info"></span>
				<?php esc_html_e( 'After the specified number of hours, the failed login attempts for a user will be reset.', 'supervisor' ); ?>
			</p>
		</ul>
		<?php
	}
}
