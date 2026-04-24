<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Job_Order_Form
 */
class Job_Order_Form {
	/**
	 *
	 */
	public static function init() {
		add_filter( 'gpnf_use_jquery_ui', '__return_true' );
		add_action( 'gpnf_template_args', array( __CLASS__, 'gpnf_template_args' ), 10 );
		add_filter( 'gform_field_content_3_42', array( __CLASS__, 'render_job_form' ), 10, 5 );
		add_filter( 'gform_pre_render_4', array( __CLASS__, 'populate_rigs' ) );
		add_filter( 'gform_pre_validation_4', array( __CLASS__, 'populate_rigs' ) );
		add_filter( 'gform_pre_submission_filter_4', array( __CLASS__, 'populate_rigs' ) );
		add_filter( 'gform_admin_pre_render_4', array( __CLASS__, 'populate_rigs' ) );
		add_filter( 'gform_entry_field_value', array( __CLASS__, 'show_rig_name' ), 10, 4 );
		add_filter( 'gform_merge_tag_filter', array( __CLASS__, 'rig_name_entry' ), 10, 6 );
	}

	/**
	 * @param $value
	 * @param $merge_tag
	 * @param $modifier
	 * @param $field
	 * @param $raw_value
	 * @param $format
	 *
	 * @return mixed|string
	 */
	public static function rig_name_entry( $value, $merge_tag, $modifier, $field, $raw_value, $format ) {
		if ( 'select' === $field->type && str_contains( $field->{'cssClass'}, 'js-select-rig' ) && ! empty( $raw_value ) ) {
			$value = get_the_title( $raw_value );
		}
		return $value;
	}

	/**
	 * @param $value
	 * @param $field
	 * @param $lead
	 * @param $form
	 *
	 * @return mixed|string
	 */
	public static function show_rig_name( $value, $field, $lead, $form ) {
		if ( ! str_contains( $field->{'cssClass'}, 'js-select-rig' ) ) {
			return $value;
		}

		if ( 'rig' !== get_post_type( $value ) ) {
			return $value;
		}

		return get_the_title( $value );
	}

	/**
	 * @param $form
	 *
	 * @return mixed
	 */
	public static function populate_rigs( $form ) {
		foreach ( $form['fields'] as &$field ) {

			if ( 'select' !== $field->type || false === strpos( $field->{'cssClass'}, 'js-select-rig' ) ) {
				continue;
			}

			$posts = get_posts( 'posts_per_page=-1&post_status=publish&post_type=rig' );

			$choices = array();

			foreach ( $posts as $post ) {
				$choices[] = array(
					'text'  => $post->post_title,
					'value' => $post->ID,
				);
			}

			$field->choices = $choices;

		}

		return $form;
	}

	/**
	 * @param $args
	 *
	 * @return mixed
	 */
	public static function gpnf_template_args( $args ) {
		$args['labels']['no_entries'] = sprintf(
			__( 'No %1$s selected. Please select a %2$s from the list above.', 'gp-nested-forms' ),
			strtolower( $args['field']->get_items_label() ),
			strtolower( $args['field']->get_item_label() ),
		);

		return $args;
	}

	/**
	 * @param $content
	 * @param $field
	 * @param $value
	 * @param $lead_id
	 * @param $form_id
	 *
	 * @return string
	 */
	public static function render_job_form( $content, $field, $value, $lead_id, $form_id ) {
		if ( is_admin() ) {
			return $content;
		}

		$terms = get_terms(
			array(
				'taxonomy' => 'rig_category',
			)
		);

		$rigs = array();

		foreach ( $terms as $term ) {
			$args = array(
				'post_type'      => 'rig',
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => $term->taxonomy,
						'terms'    => array( $term->term_id ),

					),
				),
			);

			$term_rigs = get_posts( $args );

			foreach ( $term_rigs as $rig ) {
				$title          = get_the_title( $rig );
				$rigs[ $title ] = $rig;
			}
		}

		$new_content = '';
		ob_start();
		?>
		<div class="reveal large reveal--rig-info js-rig-info-modal" id="rig-info" data-reveal></div>
		<?php
		$new_content .= '<div class="select-rig__items">';

		foreach ( $rigs as $title => $rig ) {
			ob_start();
			$terms      = get_the_terms( $rig, 'rig_category' );
			$term_names = array_map(
				function ( $term ) {
					return $term->name;
				},
				$terms
			);
			?>
			<div class="select-rig__item">
				<div class="rig-info">
					<div class="rig-info__name">
						<img src="<?php echo hny_get_featured_image( $rig, 'thumbnail' ); ?>" alt="" />
						<div>
							<strong><?php echo $title; ?></strong>
							<span>
								<?php echo implode( ', ', $term_names ); ?>
								<a class="inline-icon js-get-rig-info" data-rig="<?php echo $rig; ?>">
									<span>View Rig Info</span>
									<?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?>
								</a>
							</span>
						</div>
					</div>
					<div class="rig-info__action">
						<button type="button" class="gpnf-add-entry button small" data-formid="3"
								data-nestedformid="4" data-rig="<?php echo $rig; ?>"
								data-rig-title="<?php echo $title; ?>"
								data-bind="attr: { disabled: isMaxed }">
							Select Rig
						</button>
					</div>
				</div>
			</div>
			<?php
			$new_content .= ob_get_clean();
		}

		$new_content .= '</div>';

		return $new_content . $content;
	}
}

Job_Order_Form::init();
