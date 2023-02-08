<?php
namespace SUPV\Admin\Views;

/**
 * The TransientsView class.
 *
 * @package supervisor
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
				<div class="text">Transients</div>
			</div>

			<div class="content">
				<p>WordPress transients are used to temporarily cache specific data. For example, developers often use them to improve their themes and plugins performance by caching database queries and script results.</p>
				<p>However, some badly coded plugins and themes can store too much information on these transients, or can even create an excessively high number of transients, resulting in performance degradation.</p>

				<?php $this->output_stats(); ?>
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
					<p class="supv-stats-title">Total</p>
					<p class="supv-stats-data"><span><?php echo ! empty( $stats['count'] ) ? esc_html( $stats['count'] ) : '0'; ?></span></p>
				</li>
				<li>
					<p class="supv-stats-title">Size</p>
					<p class="supv-stats-data">
						<span><?php echo ! empty( $stats['size'] ) && is_numeric( $stats['size'] ) ? esc_html( round( $stats['size'], 2 ) ) : '0.00'; ?></span>
						<span class="supv-stats-footer">Mb</span>
					</p>
				</li>
			</ul>
		</div>
		<?php
	}
}
