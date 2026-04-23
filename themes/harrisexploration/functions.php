<?php
require_once dirname( __FILE__ ) . '/includes/class-theme.php';

/**
 * Main instance of Theme.
 *
 * @return Theme
 */
function theme() {
	return Theme::get_instance();
}

add_action( 'after_setup_theme', 'theme' );
