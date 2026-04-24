<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Admin_Scripts
 */
class Theme_Admin_Scripts extends Theme_Script_Loader {
	/**
	 * Init theme admin scripts.
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
	}

	/**
	 * Enqueue all admin styles.
	 */
	public function load_styles() {
		$this->enqueue_styles( $this->get_styles() );
		add_editor_style( $this->get_asset_url( 'editor.css' ) );
	}

	/**
	 * Get admin styles.
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'admin_styles' => array(
				'src'  => $this->get_asset_url( 'admin.css' ),
				'deps' => [],
			),
		);
	}

	/**
	 * Enqueue all admin scripts.
	 */
	public function load_scripts() {
		$this->register_scripts( $this->get_scripts() );
		$this->enqueue_script( 'admin' );
	}

	/**
	 * Get admin scripts.
	 *
	 * @return array
	 */
	public function get_scripts() {
		return array(
			'admin' => array(
				'src'       => $this->get_asset_url( 'admin.js' ),
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
		);
	}
}
