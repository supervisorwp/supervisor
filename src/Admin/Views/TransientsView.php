<?php
namespace SUPV\Admin\Views;

/**
 * The TransientsView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
class TransientsView extends AbstractView {

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
			</div>
		</div>
		<?php
	}
}
