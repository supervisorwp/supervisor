<?php
namespace SUPV\Admin\Views\Pages;

use SUPV\Admin\Views\AbstractView;
use SUPV\Admin\Views\Cards\AutoloadCardView;
use SUPV\Admin\Views\Cards\TransientsCardView;
use SUPV\Admin\Views\Cards\WordPressCardView;
use SUPV\Admin\Views\SystemInfoView;

/**
 * The DashboardView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class DashboardView extends AbstractView {

	/**
	 * The full qualified name for the cards to load.
	 *
	 * @since 1.0.0
	 *
	 * @var string[]
	 */
	private $cards = [
		TransientsCardView::class,
		AutoloadCardView::class,
		WordPressCardView::class,
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
				<?php ( new SystemInfoView() )->output(); ?>
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
	private function output_header() {

		?>
		<div class="supv-header">
			<div class="supv-header-logo">
				<img src="<?php echo esc_url( supv_get_asset_url( 'supervisor-logo.png' ) ); ?>" title="Supervisor" />
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the cards.
	 *
	 * @since 1.0.0
	 */
	private function output_cards() {

		?>
		<div class="supv-boxes">
			<?php foreach ( $this->cards as $card ) : ?>
				<div class="supv-box">
					<?php ( new $card() )->output(); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
