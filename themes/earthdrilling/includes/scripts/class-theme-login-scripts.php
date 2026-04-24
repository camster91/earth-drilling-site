<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Login_Scripts
 */
class Theme_Login_Scripts extends Theme_Script_Loader {
	/**
	 * Init theme login scripts.
	 */
	public function init() {
		add_action( 'login_enqueue_scripts', array( $this, 'load_styles' ) );
	}

	/**
	 * Enqueue all login scripts and styles.
	 */
	public function load_styles() {
		$this->enqueue_styles( $this->get_styles() );
	}

	/**
	 * Get login page styles.
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'login_styles' => array(
				'src'  => $this->get_asset_url( 'login.css' ),
				'deps' => '',
			),
		);
	}
}
