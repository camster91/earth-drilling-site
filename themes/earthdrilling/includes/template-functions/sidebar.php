<?php
/**
 * Does this page have a sub-nav/sidebar?
 * Checks the sub-nav menu items to determine if current page is a child (has parent).
 *
 * @return bool
 */
function hny_has_sidebar() {
	if ( is_front_page() ) {
		return false;
	}

	if ( ! is_page() ) {
		return false;
	}

	$items = wp_get_nav_menu_items( 'sub-nav' );
	$id    = get_queried_object_id();

	if ( $items ) {
		foreach ( $items as $item ) {
			if ( $id === (int) $item->object_id && $item->menu_item_parent ) {
				return true;
			}
		}
	}

	return false;
}
