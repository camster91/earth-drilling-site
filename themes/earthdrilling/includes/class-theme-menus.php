<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Theme_Menus
 */
class Theme_Menus {
	/**
	 * Array of Theme_Menu objects.
	 *
	 * @var array
	 */
	protected $menus = array();

	/**
	 * Theme_Menus constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->register_menus();
		$this->add_hooks();
	}

	/**
	 * Include menu classes.
	 */
	private function includes() {
		/**
		 * Menu walkers.
		 */
		include_once THEME_ABSPATH . 'includes/walkers/class-header-utility-walker.php';
		include_once THEME_ABSPATH . 'includes/walkers/class-mobile-walker.php';
		include_once THEME_ABSPATH . 'includes/walkers/class-sub-nav-walker.php';
		include_once THEME_ABSPATH . 'includes/walkers/class-top-bar-walker.php';
	}

	/**
	 * Define and register menus.
	 */
	private function register_menus() {
		$items = array(
			array(
				'label' => 'Header Utility',
				'args'  => array(
					'walker' => new Header_Utility_Walker(),
				),
			),
			array(
				'label' => 'Primary Nav',
				'args'  => array(
					'walker' => new Top_Bar_Walker(),
				),
			),
			array(
				'label' => 'Mobile Nav',
				'args'  => array(
					'walker'    => new Mobile_Walker(),
					'accordion' => true,
				),
			),
			array(
				'label' => 'Mobile Utility',
				'args'  => array(
					'walker'    => new Mobile_Walker(),
					'accordion' => true,
				),
			),
			array(
				'label' => 'Sub Nav',
				'args'  => array(
					'depth'                  => 3,
					'walker'                 => new Sub_Nav_Walker(),
					'sub_menu'               => true,
					'expand_siblings'        => true,
					'direct_parent'          => true,
					'show_parent'            => true,
					'no_results_hide_parent' => true,
					'limit_depth_no_active'  => true,
				),
			),
			array(
				'label' => 'Services Quick Links',
				'args'  => array(
					'depth'       => 1,
					'link_before' => '<div></div>',
					'link_after'  => '<div></div>',
				),
			),
			array(
				'label' => 'Drills Quick Links',
				'args'  => array(
					'depth'       => 1,
					'link_before' => '<div></div>',
					'link_after'  => '<div></div>',
				),
			),
			array(
				'label' => 'Industry Quick Links',
				'args'  => array(
					'depth'       => 1,
					'link_before' => '<div></div>',
					'link_after'  => '<div></div>',
				),
			),
			array(
				'label' => 'Industry Section Nav',
				'args'  => array(
					'walker' => new Top_Bar_Walker(),
				),
			),
			array(
				'label' => 'Legal Nav',
				'args'  => array(
					'depth' => 1,
				),
			),
		);

		foreach ( $items as $item ) {
			if ( isset( $item['label'] ) && ! empty( $item['label'] && isset( $item['args'] ) ) ) {
				$menu = new Theme_Menu( $item['label'], $item['args'] );

				if ( ! array_key_exists( $menu->get_slug(), $this->get_menus() ) ) {
					$menu->register();
				}

				$this->menus[ $menu->get_slug() ] = $menu;
			}
		}
	}

	/**
	 * Gets all theme nav menus from the class.
	 *
	 * @return array
	 */
	public function get_menus() {
		return $this->menus;
	}

	/**
	 * Clean up default WordPress menu classes and attributes.
	 * Filter also added to remove ID attribute from menu items.
	 */
	public function add_hooks() {
		add_filter( 'nav_menu_item_id', '__return_false' );
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ), 10, 3 );
		add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_link_attributes' ), 10, 3 );
		add_filter( 'nav_menu_item_title', array( $this, 'nav_menu_item_title' ), 10, 4 );
		add_filter( 'wp_nav_menu_items', array( $this, 'remove_menu_whitespace' ) );
		add_filter( 'wp_nav_menu_objects', array( $this, 'add_js_dropdown_class' ), 10, 2 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'sub_menu_object' ), 10, 2 );
		add_filter( 'wp_nav_menu', array( $this, 'replace_anchor_with_button' ), 10, 2 );
	}

	/**
	 * Gets a theme nav menu object from the class.
	 *
	 * @param $slug
	 *
	 * @return bool|Theme_Menu
	 */
	public function get_menu( $slug ) {
		$menus = $this->get_menus();

		if ( array_key_exists( $slug, $menus ) ) {
			return $menus[ $slug ];
		}

		return false;
	}

	/**
	 * Strip default WordPress navigation link classes in favour of BEM classes.
	 *
	 * @param array    $classes Nav items classes array.
	 * @param object   $item    Nav item object.
	 * @param stdClass $args    Nav menu arguments.
	 *
	 * @return array The modified class list.
	 */
	public function nav_menu_css_class( $classes, $item, $args ) {
		$new_classes   = array();
		$blog          = get_option( 'page_for_posts' );
		$base_class    = 0 !== (int) $item->menu_item_parent ? $args->theme_location . '__submenu-item' : $args->theme_location . '__item';
		$active_class  = $base_class . '--active';
		$new_classes[] = $base_class;

		if ( (bool) 1 === $item->current || true === $item->current_item_ancestor || in_array(
			'current-page-ancestor',
			$classes,
			true
		) ) {
			$new_classes[] = $active_class;
		}

		if ( ( hny_is_blog() || is_singular( 'post' ) ) && ( $blog === $item->object_id || wp_get_post_parent_id( $blog ) === absint( $item->object_id ) ) ) {
			$new_classes[] = $active_class;
		}

		if ( is_post_type_archive( 'industry' ) && 'industry' === $item->object && 'post_type_archive' === $item->type ) {
			$new_classes[] = $active_class;
		}

		if ( 'header-utility' === $args->theme_location ) {
			$highlight = get_post_meta( $item->ID, 'highlight', true );

			if ( $highlight ) {
				$new_classes[] = $base_class . '--highlight';
			}

			$dark = get_post_meta( $item->ID, 'dark', true );

			if ( $dark ) {
				$new_classes[] = $base_class . '--dark';
			}
		}

		if ( 'industry-section-nav' === $args->theme_location ) {
			if ( 1 === $item->menu_order ) {
				$new_classes[] = 'is-active';
			}
		}

		return array_unique( $new_classes );
	}

	/**
	 * Add custom data attributes to menu links.
	 *
	 * @param array    $atts Nav item attributes.
	 * @param object   $item Nav item object.
	 * @param stdClass $args Nav menu arguments.
	 *
	 * @return array|null Returns the modified attributes.
	 */
	public function nav_menu_link_attributes( $atts, $item, $args ) {
		if ( 'header-utility' === $args->theme_location ) {
			if ( trailingslashit( home_url() ) === $item->url ) {
				$atts['title'] = $item->title;
			}
		}

		if ( 'industry-section-nav' === $args->theme_location ) {
			unset( $atts['href'] );
			$atts['data-scroll-target'] = get_post_field( 'post_name', $item->object_id );
		}

		if ( 'primary-nav' === $args->theme_location ) {
			$atts['title'] = $item->title;
		}

		if ( 'services-quick-links' === $args->theme_location || 'industry-quick-links' === $args->theme_location ) {
			$background = get_post_meta( $item->ID, 'background_image', true );

			if ( $background ) {
				$atts['style'] = 'background-image: url(\'' . hny_get_image_url(
					$background,
					'hny-small'
				) . '\');';
			}
		}

		return $atts;
	}

	/**
	 * Filter nav menu item titles.
	 *
	 * @param string   $title The menu item's title.
	 * @param object   $item  The current menu item.
	 * @param stdClass $args  An array of wp_nav_menu() arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 *
	 * @return string $title The menu item's title with dropdown icon.
	 */
	public function nav_menu_item_title( $title, $item, $args, $depth ) {
		if ( 'primary-nav' === $args->theme_location && 0 === $depth ) {
			$title = explode( ' ', $item->title );

			return implode(
				' ',
				array_map(
					function ( $part ) {
						return '<span>' . $part . '</span>';
					},
					$title
				)
			);
		}

		if ( 'header-utility' === $args->theme_location && trailingslashit( home_url() ) === $item->url ) {
			return hny_get_svg( array( 'icon' => 'home' ) );
		}

		$new_title = '<span>' . $title . '</span>';

		if ( 'industry-quick-links' === $args->theme_location || 'industry-section-nav' === $args->theme_location || 'drills-quick-links' === $args->theme_location ) {
			$icon = 'rig_category' === $item->object ? get_term_meta( $item->object_id, 'icon', true ) : get_post_meta( $item->ID, 'icon', true );

			return '<span>' . hny_get_svg( array( 'icon' => $icon ) ) . $new_title . '</span>';
		}

		if ( 2 <= $args->depth ) {
			foreach ( $item->classes as $class ) {
				if ( 'menu-item-has-children' === $class || 'page_item_has_children' === $class ) {
					if ( 0 !== (int) $item->menu_item_parent ) {
						$new_title = $new_title . hny_get_svg( array( 'icon' => 'chevron-right' ) );
					} else {
						$new_title = $new_title . hny_get_svg( array( 'icon' => 'chevron-down' ) );
					}
				}
			}
		}

		return $new_title;
	}

	/**
	 * @param $menu
	 * @param $args
	 *
	 * @return string|string[]|null
	 */
	public function replace_anchor_with_button( $menu, $args ) {
		if ( 'industry-section-nav' === $args->theme_location ) {
			$find    = array( '/<a/', '/<\/a>/' );
			$replace = array(
				'<button class="js-waypoint-link"',
				'</button>',
			);

			return preg_replace( $find, $replace, $menu );
		}

		return $menu;
	}

	/**
	 * Strips extra whitespace from WordPress menu HTML output.
	 *
	 * @param $items
	 *
	 * @return string|string[]|null
	 */
	public function remove_menu_whitespace( $items ) {
		return preg_replace( '/>(\s|\n|\r)+</', '><', $items );
	}

	/**
	 * Adds JS trigger class if menu has child items.
	 *
	 * @param $sorted_menu_items
	 * @param $args
	 *
	 * @return mixed
	 */
	public function add_js_dropdown_class( $sorted_menu_items, $args ) {
		$has_dropdown = false;

		if ( 2 <= $args->depth ) {
			foreach ( $sorted_menu_items as $key => $item ) {
				if ( 0 !== (int) $item->menu_item_parent ) {
					$has_dropdown = true;
					break;
				}
			}

			if ( $has_dropdown ) {
				$classes          = array( $args->menu_class, 'js-' . $args->theme_location );
				$args->menu_class = implode( ' ', $classes );
			}
		}

		if ( false !== strpos( $args->theme_location, '-quick-links' ) ) {
			if ( 8 <= count( $sorted_menu_items ) ) {
				$classes          = array(
					$args->menu_class,
					$args->theme_location . '__items--offset',
				);
				$args->menu_class = implode( ' ', $classes );
			}
		}

		return $sorted_menu_items;
	}

	/**
	 * Add a `sub_menu` option to `wp_nav_menu()`. Great for sidebar sub nav. Several other
	 * options are also added.
	 *
	 * Only used in a `wp_nav_menu_objects` hook.
	 *
	 * ## Usage
	 *
	 * The following is an example of using `wp_nav_menu` with the added arguments:
	 * <code>
	 * <?php
	 * wp_nav_menu( array(
	 *
	 *        // Normal wp_nav_menu stuff
	 *        'theme_location' => 'primary',
	 *        'container' => 'nav',
	 *        'container_class' => 'menu subnav',
	 *        'depth' => 2,
	 *
	 *        // Added argument to enable sub-menus
	 *        'sub_menu' => true,
	 *
	 *        // Additional added arguments with defaults
	 *        'expand_siblings' => false, // Whether or not sibling menu items's children should be expanded
	 *        'direct_parent' => true, // Only use the current item as parent, or climb the tree
	 *        'show_parent' => true, // Show the top level parent (probably the section name)
	 *        'no_results_hide_parent' => true, // If show_parent is enabled but there's no menu, then hide the parent too
	 *        'limit_depth_no_active' => true // If we're on a page where none of the subnav items are open, limit depth to 1
	 *        )
	 *    );
	 *    ?>
	 * </code>
	 *
	 * @param array    $sorted_menu_items The menu items, sorted by each menu item's menu order
	 * @param stdClass $args              An object containing wp_nav_menu() arguments
	 *
	 * @link       https://codex.wordpress.org/Function_Reference/wp_nav_menu
	 * @link       https://developer.wordpress.org/reference/hooks/wp_nav_menu_objects/
	 * @internal   Only used in a `wp_nav_menu_objects` hook
	 *
	 * @return array|null Returns the modified menu items
	 */
	public function sub_menu_object( $sorted_menu_items, $args ) {
		if ( isset( $args->sub_menu ) && true === $args->sub_menu ) {
			// Store some info about our menu items
			$root_id              = 0;
			$current_menu_item_id = 0;

			// find the current menu item
			foreach ( $sorted_menu_items as $menu_item ) {
				if ( $menu_item->current ) {
					$root_id              = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
					$current_menu_item_id = $menu_item->ID;
					break;
				}
			}

			// find the top level parent
			if ( isset( $args->direct_parent ) && false === $args->direct_parent ) {
				$prev_root_id = $root_id;

				while ( 0 !== (int) $prev_root_id ) {
					foreach ( $sorted_menu_items as $menu_item ) {
						if ( $menu_item->ID == $prev_root_id ) {
							$prev_root_id = $menu_item->menu_item_parent;

							// don't set the root_id to 0 if we've reached the top of the menu
							if ( 0 !== (int) $prev_root_id ) {
								$root_id = $menu_item->menu_item_parent;
								break;
							}
						}
					} // foreach
				} // while
			} // Not direct_parent

			$menu_item_parents = array();

			// Loop through items and unset ones outside of the tree
			foreach ( $sorted_menu_items as $key => $item ) {
				// init menu_item_parents
				if ( $item->ID == $root_id ) {
					$menu_item_parents[ $item->ID ] = $item->title;
				}

				if ( array_key_exists( $item->menu_item_parent, $menu_item_parents ) ) {
					// part of sub-tree: keep!
					$menu_item_parents[ $item->ID ] = $item->title;
				} elseif ( ! ( ( isset( $args->show_parent ) && ( isset( $args->show_parent ) && false !== $args->show_parent ) ) && array_key_exists(
					$item->ID,
					$menu_item_parents
				) ) ) {
					// not part of sub-tree: away with it!
					unset( $sorted_menu_items[ $key ] );
				}
			} // foreach

			// Figure out if there's an active item in the menu
			// Loop through the items and check if there is no active item in the array. If not, limit depth to 1.
			$active_in_array = false;

			foreach ( $sorted_menu_items as $key => $item ) {
				// Found an active one
				if ( true === $item->current ) {
					$active_in_array = true;
				}
			}

			// If show parent is set but that'd be the only thing returned, and no_results_hide_parent is true
			if ( ( isset( $args->no_results_hide_parent ) && true === $args->no_results_hide_parent ) && ( isset( $args->show_parent ) && true === $args->show_parent ) && count( $sorted_menu_items ) == 1 ) {
				$sorted_menu_items = array();
			}

			if ( ! isset( $args->limit_depth_no_active ) || true === $args->limit_depth_no_active ) {
				// Time to unset some things
				if ( ! $active_in_array ) {
					foreach ( $sorted_menu_items as $key => $item ) {
						// Unset ones that aren't direct children of the current item
						if ( $item->menu_item_parent != $root_id ) {
							unset( $sorted_menu_items[ $key ] );
						}
					}
				}
			} // limit_depth_no_active

			// If there is an active item in the menu, only expand it's children (don't expand siblings)
			if ( $active_in_array && ( ! isset( $args->expand_siblings ) || false === $args->expand_siblings ) ) {
				// Loop through items, unset some
				foreach ( $sorted_menu_items as $key => $item ) {
					// Unset items that aren't children of the root or current item
					if ( $item->menu_item_parent != $current_menu_item_id && $item->menu_item_parent != $root_id ) {
						unset( $sorted_menu_items[ $key ] );
					}
				}
			}
		}

		return $sorted_menu_items;
	}
}
