<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Front_Scripts
 */
class Theme_Front_Scripts extends Theme_Script_Loader {
	/**
	 * Init theme front-end scripts.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
	}

	/**
	 * @param $urls
	 * @param $relation_type
	 *
	 * @return array
	 */
	public function resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'font', 'queue' ) && 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://cdn.jsdelivr.net',
				'crossorigin',
			);
		}

		return $urls;
	}

	/**
	 * Enqueue all front-end styles.
	 */
	public function load_styles() {
		wp_dequeue_style( 'wp-block-library' );
		$this->enqueue_styles( $this->get_styles() );
	}

	/**
	 * Get front-end styles.
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'inter-font'   => array(
				'src'  => 'https://cdn.jsdelivr.net/npm/inter-ui@3.13.1/inter.min.css',
				'deps' => array(),
			),
			'teko-font'   => array(
				'src'  => 'https://use.typekit.net/mau2svt.css',
				'deps' => array(),
			),
			'styles' => array(
				'src'  => $this->get_asset_url( 'main.css' ),
				'deps' => array(),
			),
		);
	}

	/**
	 * Enqueue all front-end scripts.
	 */
	public function load_scripts() {
		wp_deregister_script( 'jquery' );
		$this->register_scripts( $this->get_scripts() );

		$this->enqueue_script( 'jquery' );
		$this->enqueue_script( 'migrate' );
	}

	/**
	 * Get front-end scripts.
	 *
	 * @return array
	 */
	public function get_scripts() {
		return array(
			'jquery'      => array(
				'src'       => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js',
				'deps'      => array(),
				'in_footer' => false,
			),
			'migrate'     => array(
				'src'       => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.3.0/jquery-migrate.min.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => false,
			),
			'main'        => array(
				'src'       => $this->get_asset_url( 'main.js' ),
				'deps'      => array( 'jquery', 'imagesloaded' ),
				'params'    => array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				),
				'in_footer' => true,
			),
			'google-maps' => array(
				'src'       => 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . Theme()->google_api_key,
				'deps'      => array(),
				'in_footer' => true,
			),
			'sliders'     => array(
				'src'       => $this->get_asset_url( 'sliders.js' ),
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
		);
	}
}
