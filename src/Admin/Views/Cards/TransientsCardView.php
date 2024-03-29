<?php
namespace SUPV\Admin\Views\Cards;

use SUPV\Admin\Views\AbstractView;

/**
 * The TransientsCardView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class TransientsCardView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text"><?php esc_html_e( 'Transients', 'supervisor' ); ?></div>
			</div>

			<div class="content">
				<p><?php esc_html_e( 'WordPress transients are used to temporarily cache specific data. For example, developers often use them to improve their themes and plugins performance by caching database queries and script results.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'However, some badly coded plugins and themes can store too much information on these transients, or can even create an excessively high number of transients, resulting in performance degradation.', 'supervisor' ); ?></p>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'Cleaning up the transients won\'t affect your site functionality. In fact, plugins, themes, and WordPress itself will recreate them according to their needs.', 'supervisor' ); ?>
				</p>

				<div id="supv-transients-stats">
					<?php $this->output_stats(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the stats.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $success True if should display the success message.
	 */
	public function output_stats( $success = false ) {

		$stats = supv()->core()->transients()->get_stats();
		?>
		<div class="supv-stats">
			<ul>
				<li>
					<p class="supv-stats-title"><?php esc_html_e( 'Total', 'supervisor' ); ?></p>
					<p class="supv-stats-data"><span><?php echo ! empty( $stats['count'] ) ? esc_html( $stats['count'] ) : '0'; ?></span></p>
				</li>
				<li>
					<p class="supv-stats-title"><?php esc_html_e( 'Size', 'supervisor' ); ?></p>
					<p class="supv-stats-data">
						<span><?php echo ! empty( $stats['size'] ) && is_numeric( $stats['size'] ) ? esc_html( number_format( $stats['size'], 2 ) ) : '0.00'; ?></span>
						<span class="supv-stats-footer"><?php esc_html_e( 'MB', 'supervisor' ); ?></span>
					</p>
				</li>
			</ul>
		</div>

		<div class="supv-ctas">
			<button type="button" class="supv-button" id="supv-btn-transients-clear">
				<?php esc_html_e( 'Clear All Transients', 'supervisor' ); ?>
			</button>
		</div>

		<?php if ( $success ) : ?>
			<div class="supv-text-success">
				<?php esc_html_e( 'Yay! The transients were cleaned up successfully.', 'supervisor' ); ?>
			</div>
		<?php endif; ?>

		<?php
	}
}
