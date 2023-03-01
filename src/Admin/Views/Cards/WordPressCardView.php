<?php
namespace SUPV\Admin\Views\Cards;

use SUPV\Admin\Views\AbstractView;

/**
 * The WordPressCardView class.
 *
 * @package supervisor
 * @since {VERSION}
 */
final class WordPressCardView extends AbstractView {

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

				<div id="supv-wordpress-update-policy-box">
					<?php $this->output_select(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the user select.
	 *
	 * @since {VERSION}
	 *
	 * @param bool $success True if the success message should be displayed.
	 */
	public function output_select( $success = false ) {

		$selected_policy = supv()->core()->wordpress()->get_auto_update_policy();

		$policies = [
			'major'    => esc_html__( 'Install major and minor updates automatically', 'supervisor' ),
			'minor'    => esc_html__( 'Install minor updates automatically', 'supervisor' ),
			'disabled' => esc_html__( 'Disable automatic updates (not recommended)', 'supervisor' ),
		];
		?>
		<p><label for="supv-wordpress-update-policy"><strong><?php esc_html_e( 'Select the WordPress Update Policy:', 'supervisor' ); ?></strong></label></p>

		<p>
			<select id="supv-wordpress-update-policy" name="supv-wordpress-update-policy" class="supv-w-100">
				<?php foreach ( $policies as $policy => $text ) : ?>
					<option value="<?php echo esc_html( $policy ); ?>" <?php echo ( $policy === $selected_policy ) ? 'selected="selected"' : ''; ?>>
						<?php echo esc_html( $text ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>

		<?php if ( $success ) : ?>
			<p class="supv-wordpress-auto-update-policy-message supv-text-success">
				<?php esc_html_e( 'The WordPress Automatic Background Updates policy has been updated.', 'supervisor' ); ?>
			</p>
		<?php endif; ?>
		<?php
	}
}
