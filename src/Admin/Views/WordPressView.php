<?php
namespace SUPV\Admin\Views;

/**
 * The WordPressView class.
 *
 * @package supervisor
 * @since {VERSION}
 */
final class WordPressView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since {VERSION}
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text"><?php esc_html_e( 'WordPress Automatic Background Updates', 'supervisor' ); ?></div>
			</div>

			<div class="content">
				<p><?php esc_html_e( 'The default WordPress behavior is to always update automatically to the latest minor release available. For example, WordPress 6.1 will automatically be updated to 6.1.1 upon release.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'Minor updates are released more often than major ones. These releases usually includes security updates, fixes, and enhancements.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'Major updates are released 3-4 times a year, and they always include new features, major enhancements, and bug fixes to WordPress.', 'supervisor' ); ?></p>
				<p><label for="supv-wordpress-update-policy"><?php esc_html_e( 'Select the WordPress Update Policy:', 'supervisor' ); ?></label></p>
				<p>
					<select id="supv-wordpress-update-policy" name="supv-wordpress-update-policy">
						<option value="minor">Install minor updates automatically</option>
						<option value="major">Install major and minor updates automatically</option>
						<option value="disabled">Disable automatic updates on my site (not recommended)</option>
					</select>
				</p>

				<div class="supv-ctas">
					<button type="button" class="supv-button" id="supv-btn-wordpress-auto-update-policy">
						<?php esc_html_e( 'Apply Update Policy', 'supervisor' ); ?>
					</button>
				</div>

				<p id="supv-wordpress-auto-update-policy-message" class="supv-text-success"></p>
			</div>
		</div>
		<?php
	}
}
