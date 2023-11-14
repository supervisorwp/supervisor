<?php
namespace SUPV\Admin\Views;

/**
 * The ComponentsView class.
 *
 * @package supervisor
 * @since 1.3.0
 */
final class ComponentsView {

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * WordPress actions and filters.
	 *
	 * @since 1.3.0
	 */
	public function hooks() {

		add_action( 'supv_admin_views_components_switch', [ $this, 'switcher' ] );
	}

	/**
	 * The switch component.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args The component arguments.
	 */
	public function switcher( $args ) {

		if ( ! is_array( $args ) ) {
			return;
		}

		$args = wp_parse_args(
			$args,
			[
				// Defaults.
				'id'      => '',
				'text'    => '',
				'value'   => '',
				'checked' => false,
			]
		);

		?>
		<label class="supv-switch">
			<input id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" type="checkbox" value="<?php echo esc_attr( $args['value'] ); ?>" <?php echo $args['checked'] ? 'checked' : ''; ?> />
			<span class="supv-switch-slider"></span>
		</label>

		<span class="supv-switch-text">
			<label for="<?php echo esc_attr( $args['id'] ); ?>">
				<?php
				echo wp_kses(
					$args['text'],
					[
						'input' => [
							'type'  => [],
							'value' => [],
						],
					]
				);
				?>
			</label>
		</span>
		<?php
	}
}
