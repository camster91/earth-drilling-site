<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Body_Classes
 */
class Theme_Body_Classes {
	/**
	 * Init custom body classes.
	 */
	public static function init() {
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
	}

	/**
	 * Simplifies the default theme body classes added by WordPress.
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public static function body_classes( $classes ) {
		$whitelist = array(
			'home',
			'error404',
			'search',
			'search-results',
			'admin-bar',
			'blog',
			'single',
		);

		$classes = array_intersect( $classes, $whitelist );

		if ( is_author() || is_category() || is_home() || is_single() || is_tag() || is_date() ) {
			$classes[] = 'blog';
		}

		if ( hny_is_listing() && ! is_home() ) {
			$classes[] = 'is-listing-page';
		}

		if ( ! is_front_page() ) {
			$classes[] = 'not-front';
		}

		if ( hny_has_sidebar() ) {
			$classes[] = 'has-sidebar';
		}

		if ( Theme()->get_hero()->is_enabled() ) {
			$classes[] = 'has-hero';
		} else {
			$classes[] = 'no-hero';
		}

		if ( Theme()->get_content_blocks()->is_enabled() ) {
			$classes[] = 'has-content-blocks';
			$rows      = Theme()->get_content_blocks()->get_rows();

			if ( $rows && is_array( $rows ) && 1 === count( $rows ) ) {
				$classes[] = 'has-single-content-block';
			}
		} else {
			$classes[] = 'no-content-blocks';
		}

		$browsers = array(
			'is_iphone',
			'is_chrome',
			'is_safari',
			'is_NS4',
			'is_opera',
			'is_gecko',
			'is_lynx',
			'is_IE',
			'is_edge',
		);

		$classes[] = join(
			' ',
			array_map(
				function ( $browser ) {
					return strtolower( str_replace( 'is_', '', $browser ) );
				},
				array_filter(
					$browsers,
					function ( $browser ) {
						return $GLOBALS[ $browser ];
					}
				)
			)
		);

		$classes[] = 'hny';

		return array_filter( $classes );
	}
}

Theme_Body_Classes::init();
