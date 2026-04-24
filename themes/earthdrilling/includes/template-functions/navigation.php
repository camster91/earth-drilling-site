<?php
/**
 * Template function to get nav menu.
 *
 * @param $slug
 */
function hny_get_menu( $slug ) {
	$menus = Theme()->get_menus();
	$menu  = $menus->get_menu( $slug );

	if ( $menu ) {
		$menu->render();
	}
}

/**
 * Custom breadcrumb function.
 */
function hny_breadcrumbs() {
	$templates = array(
		'before'   => '<nav class="breadcrumbs"><ul class="breadcrumbs__items" itemscope itemtype="http://schema.org/BreadcrumbList">',
		'after'    => '</ul></nav>',
		'standard' => '<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="breadcrumbs__item">%s</li>',
		'current'  => '<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="breadcrumbs__item active">%s</li>',
		'link'     => '<a href="%s" itemprop="item"><span itemprop="name">%s</span></a>',
	);
	$options   = array(
		'show_htfpt' => true,
	);

	return new Theme_Breadcrumbs( $templates, $options );
}
