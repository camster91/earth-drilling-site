<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Cleanup
 */
class Theme_Cleanup {
	/**
	 * Clean up WordPress defaults.
	 */
	public static function init() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'parent_post_rel_link', 10 );
		remove_action( 'wp_head', 'start_post_rel_link', 10 );
		remove_action( 'wp_head', 'rel_canonical', 10 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'template_redirect', 'rest_output_link_header' );
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		add_filter( 'style_loader_tag', array( __CLASS__, 'remove_type_attr' ), 9999, 1 );
		add_filter( 'script_loader_tag', array( __CLASS__, 'remove_type_attr' ), 9999, 1 );
		add_filter( 'style_loader_src', array( __CLASS__, 'remove_wp_ver' ), 9999 );
		add_filter( 'script_loader_src', array( __CLASS__, 'remove_wp_ver' ), 9999 );
		add_filter( 'use_block_editor_for_post', '__return_false', 10 );
		add_filter( 'use_block_editor_for_page', '__return_false', 10 );
		add_filter( 'the_generator', '__return_false' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Removes unnecessary 'type' attribute from enqueued script and style tags.
	 *
	 * @param $tag
	 *
	 * @return null|string|string[]
	 */
	public static function remove_type_attr( $tag ) {
		return preg_replace( "/[\/\s\/g]type=['\"]text\/(javascript|css)['\"]/", '', $tag );
	}

	/**
	 * Hide WP version strings from scripts and styles.
	 *
	 * @param $src
	 *
	 * @return mixed
	 */
	public static function remove_wp_ver( $src ) {
		if ( ! is_admin() ) {
			global $wp_version;
			$parts = explode( '?', $src );
			if ( isset( $parts[1] ) && 'ver=' . $wp_version === $parts[1] ) {
				return $parts[0];
			}
		}

		return $src;
	}
}

Theme_Cleanup::init();
