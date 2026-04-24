<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Sidebars
 */
class Theme_Sidebars {
	/**
	 * Clean up default WordPress menu classes and attributes.
	 * Filter also added to remove ID attribute from menu items.
	 */
	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, 'register_sidebars' ) );
	}

	/**
	 * Register WordPress sidebars.
	 */
	public static function register_sidebars() {
		register_sidebar(
			array(
				'id'            => 'primary',
				'name'          => __( 'Primary Sidebar', 'hny' ),
				'class'         => 'l-sidebar',
				'description'   => __( 'The following widgets will appear in the main sidebar div.', 'hny' ),
				'before_widget' => '<div id="%1$s" class="widget widget--%2$s">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h4 class="widget__title">',
				'after_title'   => '</h4><div class="widget__body">',
			)
		);
	}
}

Theme_Sidebars::init();
