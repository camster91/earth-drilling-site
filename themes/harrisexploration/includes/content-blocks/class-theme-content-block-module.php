<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Content_Block_Module
 */
class Theme_Content_Block_Module extends Theme_Content_Block {
	/**
	 * @var string
	 */
	private $name = '';

	/**
	 * Theme_Content_Block_Module constructor.
	 *
	 * @param $id
	 * @param $key
	 * @param $name
	 */
	function __construct( $id, $key, $name ) {
		$this->id   = $id;
		$this->key  = $key;
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function get_classes() {
		$base    = 'content-block__module';
		$module  = $base . '--' . $this->get_name();
		$classes = array( $base, $module );

		if ( $this->get_prop( 'alignment' ) ) {
			$classes[] = $module . '--' . $this->get_prop( 'alignment' );
		}

		return $classes;
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Render the module.
	 * Pass the row heading into the module just in case this is from a special block.
	 *
	 * @param string $heading
	 */
	public function render( $heading = '' ) {
		hny_get_template_part(
			'partials/content-blocks/modules/' . $this->name,
			array(
				'module'  => $this,
				'heading' => $heading,
			)
		);
	}
}
