<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Content_Block_Row
 */
class Theme_Content_Block_Row extends Theme_Content_Block {
	/**
	 * @var int
	 */
	private $index = 0;

	/**
	 * Theme_Content_Block_Row constructor.
	 *
	 * @param $id
	 * @param $index
	 */
	function __construct( $id, $index ) {
		$this->id    = $id;
		$this->index = $index;
		$this->key   = 'blocks_' . $this->index;
	}

	/**
	 * Get the classes for this row.
	 *
	 * @return array
	 */
	public function get_row_classes() {
		$row_classes = array( 'content-block' );

		$row_classes[] = 'content-block--' . $this->get_block_theme();

		if ( $this->get_setting( 'centered' ) ) {
			$row_classes[] = 'content-block--centered';
		}

		if ( $this->get_setting( 'remove_spacing' ) || ! $this->get_columns() ) {
			$row_classes[] = 'content-block--no-spacing';
		}

		if ( $this->get_background() ) {
			$row_classes[] = 'content-block--has-background';
			$row_classes[] = 'content-block--has-background--' . $this->get_background();
		} else {
			$row_classes[] = 'content-block--default';
		}

		if ( $this->get_decoration() ) {
			$row_classes[] = 'content-block--has-decoration';
		}

		if ( $this->get_shadow() ) {
			$row_classes[] = 'content-block--has-shadow';
		}

		if ( $this->get_bottom_shapes() ) {
			$row_classes[] = 'content-block--has-bottom-shapes';
		}

		if ( $this->get_heading() || $this->get_subheading() ) {
			$row_classes[] = 'content-block--has-heading';
		} else {
			$row_classes[] = 'content-block--no-heading';
		}

		if ( $this->get_columns() ) {
			$row_classes[] = 'content-block--has-columns';
		} else {
			$row_classes[] = 'content-block--no-columns';
		}

		return array_filter( $row_classes );
	}

	/**
	 * @return mixed
	 */
	public function get_block_theme() {
		if ( is_front_page() && $this->get_decoration() ) {
			return 'dark';
		}

		return 'blue' === $this->get_background() ? 'dark' : 'light';
	}

	/**
	 * @return mixed
	 */
	public function get_decoration() {
		return $this->get_prop( 'decoration' );
	}

	/**
	 * Get the background colour setting for this row.
	 *
	 * @return string
	 */
	public function get_background() {
		$color = $this->get_prop( 'background' );

		return 'white' !== $color ? $color : '';
	}

	/**
	 * Get content block row setting.
	 *
	 * @param $setting
	 *
	 * @return mixed
	 */
	public function get_setting( $setting ) {
		return $this->get_prop( 'block_settings_' . $setting );
	}

	/**
	 * Get all of the columns for this row.
	 *
	 * @return Theme_Content_Block_Column[]
	 */
	public function get_columns() {
		$columns = $this->get_prop( 'columns' );

		if ( ! $columns || ! is_array( $columns ) ) {
			return array();
		}

		return array_map(
			function ( $column, $key ) {
				return new Theme_Content_Block_Column( $this->id, $this->key . '_columns_' . $key );
			},
			$columns,
			array_keys( $columns )
		);
	}

	/**
	 * @return mixed
	 */
	public function get_bottom_shapes() {
		return $this->get_prop( 'bottom_shapes' );
	}

	/**
	 * Gets the heading for this row.
	 *
	 * @return string
	 */
	public function get_heading() {
		return $this->get_setting( 'block_heading_heading' );
	}

	/**
	 * Gets the heading for this row.
	 *
	 * @return string
	 */
	public function get_subheading() {
		return $this->get_setting( 'block_heading_subheading' );
	}

	/**
	 * Gets the heading for this row.
	 *
	 * @return string
	 */
	public function get_shadow() {
		return $this->get_setting( 'shadow' );
	}

	/**
	 * Get the classes for the row header.
	 *
	 * @return array
	 */
	public function get_header_classes() {
		$header_classes = array( 'content-block__header' );

		if ( $this->get_heading() || $this->get_subheading() ) {
			$centered = $this->get_setting( 'block_heading_centered' );

			if ( $centered ) {
				$header_classes[] = 'content-block__header--centered';
			}
		}

		return array_filter( $header_classes );
	}

	/**
	 * Get the classes for this row.
	 *
	 * @return array
	 */
	public function get_container_classes() {
		$container_classes = array( 'grid-container' );

		$width = $this->get_setting( 'grid_width' );

		if ( $width && 'default' !== $width ) {
			$container_classes[] = 'grid-container--' . $width;
		}

		return array_filter( $container_classes );
	}

	/**
	 * Get the classes for this row.
	 *
	 * @return array
	 */
	public function get_grid_classes() {
		$grid_classes = array( 'grid-x', 'grid-padding-x' );

		if ( $this->get_layout() ) {
			switch ( $this->get_layout() ) {
				case 'two-column':
					if ( ! $this->get_first_column_width() || $this->get_first_column_width() === 50 ) {
						$grid_classes[] = 'medium-up-2';
					}
					break;
				case 'three-column':
					$grid_classes[] = 'medium-up-2 large-up-3';
					break;
				case 'four-column':
					$grid_classes[] = 'medium-up-2 large-up-4';
					break;
				case 'five-column':
					$grid_classes[] = 'medium-up-2 large-up-5';
					break;
				default:
					break;
			}
		}

		return array_filter( $grid_classes );
	}

	/**
	 * Get the layout name for this row.
	 *
	 * @return string
	 */
	public function get_layout() {
		return str_replace( '_', '-', $this->get_setting( 'block_layout' ) );
	}

	/**
	 * Gets width of first column.
	 *
	 * @return mixed
	 */
	public function get_first_column_width() {
		return absint( $this->get_setting( 'first_column_width' ) );
	}

	/**
	 * Render the row.
	 */
	public function render() {
		hny_get_template_part( 'partials/content-blocks/row', array( 'row' => $this ) );
	}
}
