<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Header_Utility_Walker
 */
class Header_Utility_Walker extends Walker_Nav_Menu {
	/**
	 * @param string         $output
	 * @param int            $depth
	 * @param stdClass|array $args
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"" . $args->theme_location . "__submenu submenu\">\n";
	}
}
