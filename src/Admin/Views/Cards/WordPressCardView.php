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

		$requirements = supv()->core()->server()->get_requirements();

		$wp_versions = ! empty( $requirements['wordpress'] ) ? $requirements['wordpress'] : [];

		$wp_beta_version = ! empty( $wp_versions[2] ) ? strtok( $wp_versions[0], '-' ) : '6.2';
		$wp_curr_version = ! empty( $wp_versions[1] ) ? $wp_versions[1] : '6.1.1';
		?>
		<div class="supv-card">
			<div class="header">
				<div class="text"><?php esc_html_e( 'WordPress Automatic Background Updates', 'supervisor' ); ?></div>
			</div>

			<div class="content">
				<p>
					<?php
					echo sprintf(
						/* translators: %1$s is the latest WordPress version, %2$s is the next major WordPress release. */
						esc_html__( 'From WordPress 5.6 onwards, automatic updates are enabled by default for both minor and major releases. For example, WordPress %1$s will automatically be updated to %2$s upon release.', 'supervisor' ),
						$wp_curr_version,
						$wp_beta_version
					);
					?>
				</p>
				<p><?php esc_html_e( 'Minor updates are released more often than major ones. These releases usually includes security updates, fixes, and enhancements.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'Major updates are released 2-4 times a year, and they always include new features, major enhancements, and bug fixes to WordPress.', 'supervisor' ); ?></p>

				<div id="supv-wordpress-update-policy-box">
					<?php if ( ! supv()->core()->wordpress()->is_auto_update_constant_enabled() ) : ?>
						<?php $this->output_select(); ?>
					<?php else: ?>
						<p class="box-info">
							<span class="supv-icon-info"></span>
							<?php esc_html_e( 'Automatic updates are being managed by the WordPress update constants.', 'supervisor' ); ?>
						</p>
					<?php endif; ?>
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
