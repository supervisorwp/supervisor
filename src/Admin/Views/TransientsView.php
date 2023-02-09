<?php
namespace SUPV\Admin\Views;

/**
 * The TransientsView class.
 *
 * @package supervisor-wp
 * @since 1.0.0
 */
final class TransientsView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text"><?php esc_html_e( 'Transients', 'supervisor-wp' ); ?></div>
			</div>

			<div class="content">
				<p><?php esc_html_e( 'WordPress transients are used to temporarily cache specific data. For example, developers often use them to improve their themes and plugins performance by caching database queries and script results.', 'supervisor-wp' ); ?></p>
				<p><?php esc_html_e( 'However, some badly coded plugins and themes can store too much information on these transients, or can even create an excessively high number of transients, resulting in performance degradation.', 'supervisor-wp' ); ?></p>

				<?php $this->output_stats(); ?>
				<?php $this->output_ctas(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the stats.
	 *
	 * @since 1.0.0
	 */
	public function output_stats() {

		$stats = supv()->core()->transients()->get_stats();
		?>
		<div class="supv-stats">
			<ul>
				<li>
					<p class="supv-stats-title"><?php esc_html_e( 'Total', 'supervisor-wp' ); ?></p>
					<p class="supv-stats-data"><span><?php echo ! empty( $stats['count'] ) ? esc_html( $stats['count'] ) : '0'; ?></span></p>
				</li>
				<li>
					<p class="supv-stats-title"><?php esc_html_e( 'Size', 'supervisor-wp' ); ?></p>
					<p class="supv-stats-data">
						<span><?php echo ! empty( $stats['size'] ) && is_numeric( $stats['size'] ) ? esc_html( round( $stats['size'], 2 ) ) : '0.00'; ?></span>
						<span class="supv-stats-footer"><?php esc_html_e( 'MB', 'supervisor-wp' ); ?></span>
					</p>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Outputs the CTAs.
	 *
	 * @since 1.0.0
	 */
	public function output_ctas() {

		?>
		<div class="supv-ctas">
			<button type="button" class="supv-button" id="supv-btn-transients-clear">
				<?php esc_html_e( 'Clear All Transients', 'supervisor-wp' ); ?>
			</button>
		</div>
		<?php
	}
}
