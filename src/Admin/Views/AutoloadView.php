<?php
namespace SUPV\Admin\Views;

/**
 * The AutoloadView class.
 *
 * @package supervisor-wp
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
				<div class="text"><?php esc_html_e( 'Autoload Options', 'supervisor-wp' ); ?></div>
			</div>

			<div class="content">
				<p><?php esc_html_e( 'WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.', 'supervisor-wp' ); ?></p>
				<p><?php esc_html_e( 'All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.', 'supervisor-wp' ); ?></p>

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

		$stats = supv()->core()->autoload()->get_stats();
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
			<button type="button" class="supv-button" id="supv-btn-autoload-top">
				<?php esc_html_e( 'Top Autoload Options', 'supervisor-wp' ); ?>
			</button>

			<button type="button" class="supv-button supv-button-secondary" id="supv-btn-autoload-history">
				<?php esc_html_e( 'History', 'supervisor-wp' ); ?>
			</button>
		</div>
		<?php
	}
}
