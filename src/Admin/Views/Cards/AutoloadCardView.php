<?php
namespace SUPV\Admin\Views\Cards;

use SUPV\Admin\Views\AbstractView;

/**
 * The AutoloadCardView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class AutoloadCardView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="supv-card-header">
				<div class="text"><?php esc_html_e( 'Autoload Options', 'supervisor' ); ?></div>
			</div>

			<div class="supv-card-content">
				<p><?php esc_html_e( 'WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.', 'supervisor' ); ?></p>
				<p><?php esc_html_e( 'All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.', 'supervisor' ); ?></p>

				<p class="box-info">
					<span class="supv-icon-info"></span>
					<?php esc_html_e( 'When you deactivate an autoload option, you are not removing it. You are just telling WordPress to not load that option automatically on every request it does. In other words, the option will be loaded only when it is needed.', 'supervisor' ); ?>
				</p>
			</div>

			<div class="supv-card-actions">
				<div id="supv-autoload-stats" class="supv-autoload-stats">
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
	 * @param array     $updated_options A list with the updated options.
	 * @param bool|null $is_history      True if it was triggered from the history view.
	 */
	public function output_stats( $updated_options = [], $is_history = null ) {

		$stats   = supv()->core()->autoload()->get_stats();
		$history = supv()->core()->autoload()->get_history();
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
			<button type="button" class="supv-button" id="supv-btn-autoload-options">
				<?php esc_html_e( 'Top Autoload Options', 'supervisor' ); ?>
			</button>

			<?php if ( is_array( $history ) && count( $history ) > 0 ) : ?>
				<button type="button" class="supv-button supv-button-secondary" id="supv-btn-autoload-history">
					<?php esc_html_e( 'History', 'supervisor' ); ?>
				</button>
			<?php endif; ?>
		</div>

		<div id="supv-autoload-options">
			<?php if ( ! is_null( $is_history ) ) : ?>

				<?php if ( $is_history ) : ?>
					<?php $this->output_history( $updated_options ); ?>
				<?php else : ?>
					<?php $this->output_options( $updated_options ); ?>
				<?php endif; ?>

			<?php endif; ?>
		</div>

		<?php
	}

	/**
	 * Outputs the top autoload options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $updated_options A list with the updated options.
	 */
	public function output_options( $updated_options = [] ) {

		$opts = supv()->core()->autoload()->get();
		?>

		<form id="supv-autoload-form" name="supv-autoload-form">
			<div class="supv-autoload-list">
				<ul>
					<li></li>
					<li><?php esc_html_e( 'Name', 'supervisor' ); ?></li>
					<li><?php esc_html_e( 'Size', 'supervisor' ); ?></li>
				</ul>

				<?php foreach ( $opts as $name => $size ) : ?>
					<?php
					$title          = '';
					$id             = 'supv-opt-' . rawurlencode( $name );
					$is_core_option = supv()->core()->autoload()->is_core_option( $name );
					?>

					<ul>
						<li class="supv-col-check">
							<?php if ( ! $is_core_option ) : ?>
								<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="checkbox" />
							<?php else : ?>
								<?php $title = esc_html__( 'You can\'t deactivate a WordPress core option.', 'supervisor' ); ?>
								<input type="checkbox" disabled="disabled" title="<?php echo esc_attr( $title ); ?>" />
							<?php endif; ?>
						</li>

						<li class="supv-col-name">
							<label for="<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $title ); ?>">
								<?php echo esc_html( $name ); ?>

								<?php if ( $is_core_option ) : ?>
									<span class="supv-icon-wordpress"></span>
								<?php endif; ?>
							</label>
						</li>

						<li class="supv-col-size">
							<label for="<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $title ); ?>">
								<?php echo number_format( $size, 2 ); ?>
								<?php esc_html_e( 'MB', 'supervisor' ); ?>
							</label>
						</li>
					</ul>
				<?php endforeach; ?>
			</div>
		</form>

		<div class="supv-autoload-ctas">
			<button type="submit" class="supv-button supv-button-small" form="supv-autoload-form"><?php esc_html_e( 'Deactivate Selected Options', 'supervisor' ); ?></button>

			<button type="submit" class="supv-button supv-button-small supv-button-secondary" id="supv-btn-autoload-close"><?php esc_html_e( 'Close', 'supervisor' ); ?></button></div>
		</div>
		<?php

		if ( ! empty( $updated_options ) ) {
			$this->output_result( $updated_options );
		}
	}

	/**
	 * Outputs the autoload changes history.
	 *
	 * @since 1.0.0
	 *
	 * @param array $updated_options A list with the updated options.
	 */
	public function output_history( $updated_options = [] ) {

		$opts = supv()->core()->autoload()->get_history();
		?>

		<?php if ( count( $opts ) > 0 ) : ?>
			<form id="supv-autoload-form" name="supv-autoload-form">
				<div class="supv-autoload-list">
					<ul>
						<li></li>
						<li><?php esc_html_e( 'Name', 'supervisor' ); ?></li>
						<li><?php esc_html_e( 'Deactivation Time', 'supervisor' ); ?></li>
					</ul>

					<?php foreach ( $opts as $name => $timestamp ) : ?>
						<?php $id = 'supv-opt-' . rawurlencode( $name ); ?>

						<ul>
							<li class="supv-col-check">
								<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="checkbox" />
							</li>

							<li class="supv-col-history">
								<label for="<?php echo esc_attr( $id ); ?>">
									<?php echo esc_html( $name ); ?>
								</label>
							</li>

							<li class="supv-col-time">
								<label for="<?php echo esc_attr( $id ); ?>">
									<?php echo esc_html( gmdate( 'Y-m-d H:i:s', $timestamp ) ); ?>
								</label>
							</li>
						</ul>
					<?php endforeach; ?>
				</div>
			</form>
		<?php endif; ?>

		<div class="supv-autoload-ctas-internal">
			<?php if ( count( $opts ) > 0 ) : ?>
				<button type="submit" class="supv-button supv-button-small" form="supv-autoload-form"><?php esc_html_e( 'Reactivate Selected Options', 'supervisor' ); ?></button>
			<?php endif; ?>

			<button type="submit" class="supv-button supv-button-small supv-button-secondary" id="supv-btn-autoload-close"><?php esc_html_e( 'Close', 'supervisor' ); ?></button></div>
		</div>

		<?php
		if ( ! empty( $updated_options ) ) {
			$this->output_result( $updated_options, true );
		}
	}

	/**
	 * Outputs the autoload changes result.
	 *
	 * @since 1.0.0
	 *
	 * @param array $updated_options A list with the updated options.
	 * @param bool  $is_history      True if it was triggered from the history view.
	 */
	private function output_result( $updated_options = [], $is_history = false ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( ! supv()->admin()->ajax()->is_doing_ajax() || empty( $updated_options ) || ! is_array( $updated_options ) ) {
			return;
		}

		$fail = array_filter(
			$updated_options,
			function( $k ) {
				return $k === false;
			}
		);

		$success = array_filter(
			$updated_options,
			function( $k ) {
				return $k !== false;
			}
		);

		$message = $is_history ? esc_html__( 'reactivate', 'supervisor' ) : esc_html__( 'deactivate', 'supervisor' );

		$message_singular = $is_history ? esc_html_x( 'reactivated', 'singular: option was reactivated', 'supervisor' ) : esc_html_x( 'deactivated', 'singular: option was disabled', 'supervisor' );
		$message_plural   = $is_history ? esc_html_x( 'reactivated', 'plural: options were reactivated', 'supervisor' ) : esc_html_x( 'deactivated', 'plural: options were disabled', 'supervisor' );
		?>

		<?php if ( count( $success ) === 1 ) : ?>
			<div id="supv-autoload-result" class="supv-autoload-result">
				<p class="supv-text-success">
					<?php
					/* translators: %1$s is the option name, %2$s is the status ('deactivated' or 'reactivated'). */
					echo sprintf( esc_html__( 'Yay, the %1$s option was %2$s successfully.', 'supervisor' ), '<strong>' . sanitize_key( key( $success ) ) . '</strong>', esc_html( $message_singular ) );
					?>
				<p>
			</div>
		<?php elseif ( count( $success ) > 1 ) : ?>
			<div id="supv-autoload-result" class="supv-autoload-result">
				<p class="supv-text-success">
					<?php
					/* translators: %1$s is the status ('deactivated' or 'reactivated'). */
					echo sprintf( esc_html__( 'Yay, the below options were %1$s successfully:', 'supervisor' ), esc_html( $message_plural ) );
					?>
				</p>

				<div class="supv-autoload-result-list">
					<ul>
						<?php foreach ( $success as $name => $value ) : ?>
							<li class="supv-text-success"><strong><?php echo esc_html( $name ); ?></strong></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( count( $fail ) > 0 ) : ?>
			<?php foreach ( $fail as $name => $value ) : ?>
				<div id="supv-autoload-result" class="supv-autoload-result">
					<p class="supv-text-error">
						<?php
						/* translators: %1$s is the status ('deactivate' or 'reactivate'), %2$s is the option name. */
						echo sprintf( esc_html__( 'Oops, for some reason we couldn\'t %1$s the <strong>%2$s</strong> option.', 'supervisor' ), esc_html( $message ),  esc_html( $name ) );
						?>
					</p>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php
	}
}
