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
				<p><?php esc_html_e( 'To enhance the security of your website, you can restrict the number of login attempts.', 'supervisor' ); ?></p>

				<p>
					<label class="supv-switch">
						<input id="supv-secure-login-restrict-login-attempts" name="supv-secure-login-restrict-login-attempts" type="checkbox" />
						<span class="supv-switch-slider"></span>
					</label>
					<span class="supv-switch-text"><label for="supv-secure-login-restrict-login-attempts"><?php esc_html_e( 'Limit the login attempts', 'supervisor' ); ?></label></span>
				</p>

				<div id="">
					<?php $this->output_limit_login_attempts(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the limit login attempts options.
	 *
	 * @since {VERSION}
	 */
	public function output_limit_login_attempts() {

		?>
		<div>
			<strong><?php esc_html_e( 'Rules', 'supervisor' ); ?></strong>
			<p>
				<label class="supv-switch">
					<input id="supv-secure-login-xxx" name="supv-secure-login-xxx" type="checkbox" />
					<span class="supv-switch-slider"></span>
				</label>

				<span class="supv-switch-text">
					<label for="supv-secure-login-xxx">
						Block IP address for <input type="number" value="5" /> minutes after <input type="number" value="5" /> login attempts.
					</label>
				</span>
			</p>
		</div>
		<?php
	}
}
