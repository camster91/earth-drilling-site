<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Login_Page
 */
class Theme_Login_Page {
	/**
	 * Init custom admin theme hooks.
	 */
	public static function init() {
		add_filter( 'login_head', array( __CLASS__, 'login_logo' ) );
		add_filter( 'login_headerurl', array( __CLASS__, 'login_logo_url' ) );
		add_filter( 'login_headertext', array( __CLASS__, 'login_logo_text' ) );
	}

	/**
	 * Add custom login logo.
	 */
	public static function login_logo() {
		$logo           = false;
		$possible_logos = array(
			'dist/images/logo-login.svg',
			'dist/images/logo-login.png',
			'dist/images/logo.svg',
			'dist/images/logo.png',
		);

		foreach ( $possible_logos as $option ) {
			if ( file_exists( THEME_ABSPATH . $option ) ) {
				$logo = $option;
				break;
			}
		}

		if ( $logo ) {
			?>
			<style type="text/css">
				.login h1 a {
					background-image: url('<?php echo THEME_URI . $logo; ?>');
					height: 80px;
					width: auto;
					margin-bottom: 2rem;
					background-size: contain;
				}
			</style>
			<?php
		}
	}

	/**
	 * Change the url when clicking login logo.
	 *
	 * @return string
	 */
	public static function login_logo_url() {
		return esc_url( home_url() );
	}

	/**
	 * Customize the login logo title.
	 *
	 * @return string
	 */
	public static function login_logo_text() {
		return esc_attr( get_bloginfo( 'name' ) );
	}
}

Theme_Login_Page::init();
