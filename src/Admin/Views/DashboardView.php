<?php
namespace SUPV\Admin\Views;

/**
 * The DashboardView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class DashboardView extends AbstractView {

	/**
	 * The full qualified name for the views to load.
	 *
	 * @since 1.0.0
	 *
	 * @var string[]
	 */
	private $views = [
		TransientsView::class,
		AutoloadView::class,
	];

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-container-fluid">
			<?php $this->output_header(); ?>

			<div class="supv-container">
				<?php $this->output_overview(); ?>

				<?php $this->output_cards(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the header.
	 *
	 * @since 1.0.0
	 */
	public function output_header() {

		?>
		<div class="supv-header">
			<div class="supv-header-logo">
				<img src="<?php echo esc_url( supv_get_asset_url( 'supervisor.png' ) ); ?>" title="Supervisor" />
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the software overview.
	 *
	 * @since 1.0.0
	 */
	public function output_overview() {

		$server_info = supv()->core()->server()->get_data();
		?>
		<div class="supv-overview">
			<div class="supv-overview-box">
				<p>WordPress</p>
				<p><?php echo ! empty( $server_info['wp'] ) ? esc_html( $server_info['wp'] ) : '-'; ?></p>
			</div>

			<div class="supv-overview-box">
				<p>PHP</p>
				<p><?php echo ! empty( $server_info['php'] ) ? esc_html( $server_info['php'] ) : '-'; ?></p>
			</div>

			<div class="supv-overview-box">
				<p><?php echo ! empty( $server_info['database']['service'] ) ? esc_html( $server_info['database']['service'] ) : 'Database'; ?></p>
				<p><?php echo ! empty( $server_info['database']['version'] ) ? esc_html( $server_info['database']['version'] ) : '-'; ?></p>
			</div>

			<div class="supv-overview-box">
				<p>Web Server</p>
				<p>
					<?php
					echo ! empty( $server_info['web']['service'] ) ? esc_html( $server_info['web']['service'] ) . '/' : '';
					echo ! empty( $server_info['web']['version'] ) ? esc_html( $server_info['web']['version'] ) : '-';
					?>
				</p>
			</div>

			<?php if ( ! empty( supv()->core()->server()->get_ip() ) ) : ?>
				<div class="supv-overview-box">
					<p>Server IP</p>
					<p>
						<?php echo esc_html( supv()->core()->server()->get_ip() ); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Outputs the cards.
	 *
	 * @since 1.0.0
	 */
	public function output_cards() {
		?>
		<div class="supv-boxes supv-row">
			<?php foreach ( $this->views as $view ) : ?>
				<div class="supv-box supv-col col-md-6">
					<?php ( new $view() )->output(); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
