<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Scripts
 */
class Theme_Scripts {
	/**
	 * Theme_Scripts constructor.
	 */
	function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Include required script files.
	 */
	private function includes() {
		include_once THEME_ABSPATH . 'includes/scripts/class-theme-login-scripts.php';
		include_once THEME_ABSPATH . 'includes/scripts/class-theme-admin-scripts.php';
		include_once THEME_ABSPATH . 'includes/scripts/class-theme-front-scripts.php';
	}

	/**
	 * Register all script loaders.
	 */
	private function init() {
		$scripts = array(
			'Theme_Login_Scripts',
			'Theme_Admin_Scripts',
			'Theme_Front_Scripts',
		);

		foreach ( $scripts as $script ) {
			$this->$script = new $script();
			$this->$script->init();
		}
	}
}
