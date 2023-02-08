<?php
namespace SUPV\Admin\Views;

/**
 * The AutoloadView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class AutoloadView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text">Autoload Options</div>
			</div>

			<div class="content">
				<p>WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.</p>
				<p>All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.</p>

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

		$stats = supv()->core()->autoload()->get_stats();
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
