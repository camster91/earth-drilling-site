<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Content_Block_Column
 */
class Theme_Content_Block_Column extends Theme_Content_Block {
	/**
	 * Theme_Content_Block_Column constructor.
	 *
	 * @param $id
	 * @param $key
	 */
	function __construct( $id, $key ) {
		$this->id  = $id;
		$this->key = $key;
	}

	/**
	 * @return array
	 */
	public function get_classes() {
		$classes   = array( 'cell', 'cell--flex' );
		$alignment = $this->get_prop( 'alignment' );

		if ( $alignment && 'top' !== $alignment ) {
			$classes[] = 'align-' . $alignment;
		}

		return $classes;
	}

	/**
	 * Get all modules in this column.
	 *
	 * @return Theme_Content_Block_Module[]
	 */
	public function get_modules() {
		$modules = $this->get_prop( 'modules' );

		if ( ! $modules || ! is_array( $modules ) ) {
			return [];
		}

		return array_map(
			function ( $module, $key ) {
				return new Theme_Content_Block_Module( $this->id, $this->key . '_modules_' . $key, $module );
			},
			$modules,
			array_keys( $modules )
		);
	}

	/**
	 * @return mixed|string
	 */
	public function get_heading_size() {
		return $this->get_prop( 'column_heading_size' );
	}

	/**
	 * @return mixed|string
	 */
	public function get_heading() {
		$heading = $this->get_prop( 'column_heading_heading' );

		return $heading ? $heading : '';
	}

	/**
	 * @return mixed|string
	 */
	public function get_subheading() {
		$heading = $this->get_prop( 'column_heading_subheading' );

		return $heading ? $heading : '';
	}

	/**
	 * Render the column.
	 */
	public function render() {
		hny_get_template_part( 'partials/content-blocks/column', [ 'column' => $this ] );
	}
}
