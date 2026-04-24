<?php
/**
 * Page title used in the content area of each page. Note this is kinda legacy.
 * Could be replaced with wp_title().
 *
 * @package    WordPress
 * @subpackage hny-theme_boilerplate
 * @since      1.0
 *
 * @return string The page title
 */
function hny_get_page_title() {
	$title = '';

	if ( is_singular() ) {
		if ( is_page() ) {
			$title = get_the_title();
		} elseif ( is_single() ) {
			$title = get_the_title();
		}
	} else {
		if ( is_home() ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		} elseif ( is_archive() ) {
			if ( is_post_type_archive() ) {
				$title = post_type_archive_title( '', false );
			}
			if ( is_category() ) {
				$title = 'All posts in ' . single_cat_title( '', false );
			} elseif ( is_tag() ) {
				$title = 'All posts in ' . single_tag_title( '', false );
			} elseif ( is_date() ) {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			} elseif ( is_author() ) {
				if ( get_query_var( 'author_name' ) ) {
					$curauth = get_user_by( 'login', get_query_var( 'author_name' ) );
				} else {
					$curauth = get_userdata( get_query_var( 'author' ) );
				}
				$title = $curauth->display_name . '.';
			}
		} elseif ( is_search() ) {
			$title = 'Search';
		} elseif ( is_404() ) {
			$title = '404 Error - Page Not Found';
		}
	}

	return $title;
}
