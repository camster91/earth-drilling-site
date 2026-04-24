<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme_Hero
 */
class Theme_Hero {
	/**
	 * The queried object ID.
	 *
	 * @var
	 */
	protected $id = 0;

	/**
	 * The template name for this hero.
	 *
	 * @var int
	 */
	protected $template = '';

	/**
	 * Is hero enabled?
	 *
	 * @var bool
	 */
	protected $enabled = false;

	/**
	 * Theme_Hero constructor.
	 *
	 * @param int $id
	 */
	function __construct( $id = 0 ) {
		if ( is_numeric( $id ) && $id > 0 ) {
			$this->set_id( $id );
			$this->set_enabled();

			if ( $this->get_id() ) {
				$this->set_template();
				$this->set_enabled();
			}
		}
	}

	/**
	 * Set ID.
	 *
	 * @param int $id ID.
	 */
	private function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Set enabled.
	 */
	private function set_enabled() {
		$this->enabled = ! is_search() && ! is_404() && ( 'page' === get_post_type( $this->get_id() ) );
	}

	/**
	 * Returns the current object ID.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set template.
	 */
	private function set_template() {
		$this->template = is_front_page() ? 'hero-home' : 'hero-home';
	}

	/**
	 * @return array
	 */
	public function get_items() {
		$items = array();

		$photo      = null;
		$heading    = null;
		$subheading = null;
		$text       = null;
		$button     = null;

		$fields = array(
			'photo',
			'heading',
			'subheading',
			'text',
			'button',
		);

		if ( is_front_page() ) {
			$slides = get_post_meta( $this->get_id(), 'slides', true );

			if ( $slides ) {
				for ( $i = 0; $i < $slides; $i ++ ) {
					$data = array();

					foreach ( $fields as $field ) {
						$data[ $field ] = get_post_meta(
							$this->get_id(),
							'slides_' . $i . '_' . $field,
							true
						);
					}

					if ( ! empty( $data['photo'] ) && ! empty( $data['heading'] ) ) {
						$items[] = $data;
					}
				}
			}
		} else {
			if ( is_tax() ) {
				global $wp_query;
				$posts = $wp_query->posts;
				$first = current( $posts );

				$items[] = array(
					'photo'   => get_post_thumbnail_id( $first->ID ),
					'heading' => get_the_title( $first->ID ),
				);
			} else {
				$data = array();

				foreach ( $fields as $field ) {
					$data[ $field ] = get_post_meta( $this->get_id(), $field, true );
				}

				if ( ! empty( $data['photo'] ) && ! empty( $data['heading'] ) ) {
					$items[] = $data;
				}
			}
		}

		return $items;
	}

	/**
	 * Renders correct template based on type of hero being shown.
	 */
	public function render() {
		if ( $this->is_enabled() ) {
			hny_get_template_part(
				'partials/' . $this->get_template(),
				array( 'instance' => $this )
			);
		} else {
			if ( hny_get_page_title() ) {
				echo '<h1 class="u-screen-reader">' . hny_get_page_title() . '</h1>';
			}
		}
	}

	/**
	 * Returns whether or not hero is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return $this->enabled;
	}

	/**
	 * Returns the template.
	 */
	private function get_template() {
		return $this->template;
	}
}
