<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Theme' ) ) {
	/**
	 * Class Theme
	 */
	class Theme {
		/**
		 * @var null
		 */
		protected static $instance = null;

		/**
		 * Theme_Content_Blocks instance.
		 *
		 * @var Theme_Content_Blocks
		 */
		protected $content_blocks = null;

		/**
		 * Theme_Hero instance.
		 *
		 * @var Theme_Hero
		 */
		protected $hero = null;

		/**
		 * Array of theme image sizes.
		 *
		 * @var array
		 */
		protected $image_sizes = array();

		/**
		 * Theme_Menus instance.
		 *
		 * @var Theme_Menus
		 */
		protected $menus = null;

		/**
		 * Theme_Scripts instance.
		 *
		 * @var Theme_Scripts
		 */
		protected $scripts = null;

		/**
		 * @var string
		 */
		public $google_api_key = 'AIzaSyBJwbXbgm8aIB-TaiupGnLRJqfLRVH5-Ak';

		/**
		 * @return Theme|null
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Theme constructor.
		 */
		function __construct() {
			$this->define_constants();
			$this->includes();
			$this->add_theme_support();
			$this->add_image_sizes();
			$this->add_hooks();
		}

		/**
		 * Define plugin constants.
		 */
		private function define_constants() {
			define( 'THEME_ABSPATH', get_stylesheet_directory() . '/' );
			define( 'THEME_URI', get_stylesheet_directory_uri() . '/' );
		}

		/**
		 * Include required core theme files.
		 */
		private function includes() {
			/**
			 * Template functions.
			 */
			include_once THEME_ABSPATH . 'includes/template-functions/images.php';
			include_once THEME_ABSPATH . 'includes/template-functions/listing.php';
			include_once THEME_ABSPATH . 'includes/template-functions/navigation.php';
			include_once THEME_ABSPATH . 'includes/template-functions/page-title.php';
			include_once THEME_ABSPATH . 'includes/template-functions/pagination.php';
			include_once THEME_ABSPATH . 'includes/template-functions/sidebar.php';
			include_once THEME_ABSPATH . 'includes/template-functions/svg-icons.php';
			include_once THEME_ABSPATH . 'includes/template-functions/template-part.php';
			include_once THEME_ABSPATH . 'includes/template-functions/utility.php';

			/**
			 * Abstracts.
			 */
			include_once THEME_ABSPATH . 'includes/abstracts/class-theme-content-block.php';
			include_once THEME_ABSPATH . 'includes/abstracts/class-theme-menu.php';
			include_once THEME_ABSPATH . 'includes/abstracts/class-theme-script-loader.php';

			/**
			 * Theme classes.
			 */
			include_once THEME_ABSPATH . 'includes/class-theme-body-classes.php';
			include_once THEME_ABSPATH . 'includes/class-theme-breadcrumbs.php';
			include_once THEME_ABSPATH . 'includes/class-theme-cleanup.php';
			include_once THEME_ABSPATH . 'includes/class-theme-content-blocks.php';
			include_once THEME_ABSPATH . 'includes/class-theme-editor.php';
			include_once THEME_ABSPATH . 'includes/class-theme-hero.php';
			include_once THEME_ABSPATH . 'includes/class-theme-icons.php';
			include_once THEME_ABSPATH . 'includes/class-theme-login-page.php';
			include_once THEME_ABSPATH . 'includes/class-theme-menus.php';
			include_once THEME_ABSPATH . 'includes/class-theme-scripts.php';
			include_once THEME_ABSPATH . 'includes/class-theme-sidebars.php';
			include_once THEME_ABSPATH . 'includes/class-job-order-form.php';

			/**
			 * Content blocks.
			 */
			include_once THEME_ABSPATH . 'includes/content-blocks/class-theme-content-block-row.php';
			include_once THEME_ABSPATH . 'includes/content-blocks/class-theme-content-block-column.php';
			include_once THEME_ABSPATH . 'includes/content-blocks/class-theme-content-block-module.php';

			$this->scripts = new Theme_Scripts();
			$this->menus   = new Theme_Menus();
		}

		/**
		 * Register support for theme functions.
		 */
		private function add_theme_support() {
			add_theme_support(
				'html5',
				array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' )
			);
			add_theme_support( 'menus' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
		}

		/**
		 * Register custom image sizes.
		 */
		private function add_image_sizes() {
			$image_sizes = array(
				'hny-small'  => array(
					'label'      => 'Honeycomb - Small',
					'breakpoint' => 'small',
					'width'      => 480,
				),
				'hny-medium' => array(
					'label'      => 'Honeycomb - Medium',
					'breakpoint' => 'medium',
					'width'      => 720,
				),
				'hny-large'  => array(
					'label'      => 'Honeycomb - Large',
					'breakpoint' => 'large',
					'width'      => 1024,
				),
				'hny-xlarge' => array(
					'label'      => 'Honeycomb - Extra Large',
					'breakpoint' => 'xlarge',
					'width'      => 1440,
				),
				'hny-wide'   => array(
					'label'      => 'Honeycomb - Wide',
					'breakpoint' => 'xxlarge',
					'width'      => 2560,
				),
			);

			foreach ( $image_sizes as $slug => $size ) {
				add_image_size( $slug, $size['width'] );
				$this->image_sizes[ $slug ] = $size;
			}
		}

		/**
		 * Init theme hooks and filters.
		 */
		private function add_hooks() {
			add_action( 'acf/init', array( $this, 'acf_google_api_key' ) );
			add_action( 'wp', array( $this, 'load_frontend_classes' ) );
			add_action( 'wp', array( $this, 'set_404_query' ) );
			add_action( 'wp_head', array( $this, 'add_schema' ) );
			add_action( 'template_redirect', array( $this, 'handle_redirects' ) );
			add_filter( 'image_size_names_choose', array( $this, 'image_size_names' ) );
			add_filter( 'gform_submit_button', array( $this, 'input_to_button' ), 10, 1 );
			add_filter(
				'relevanssi_excerpt_content',
				array( $this, 'custom_fields_to_excerpts' ),
				10,
				2
			);
			add_filter(
				'acf/load_field/key=field_5e4458f42b847',
				array( $this, 'load_association_logo_choices' ),
				10,
				2
			);
			add_filter( 'the_content', array( $this, 'add_wysiwyg_wrapper' ), 10, 2 );
			add_action( 'pre_get_posts', array( $this, 'change_taxonomy_posts_orderby' ), 10 );
			add_action( 'pre_get_posts', array( $this, 'change_industry_posts_orderby' ), 10 );
			add_action( 'pre_get_posts', array( $this, 'set_post_years_query' ) );
			add_filter(
				'relevanssi_post_ok',
				array( $this, 'exclude_parent_landing_pages' ),
				10,
				2
			);
			add_action( 'init', array( $this, 'post_year_permalinks' ) );

			$icon_fields = array(
				'field_5d544b202032b',
				'field_5ee3d949e7aed',
				'field_5f3b00a388dc8',
			);

			foreach ( $icon_fields as $field ) {
				add_filter(
					'acf/load_field/key=' . $field,
					array(
						$this,
						'acf_load_icon_choices',
					)
				);
			}
		}

		/**
		 * @param $allow
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function exclude_parent_landing_pages( $allow, $post_id ) {
			if ( 109 === absint( $post_id ) ) {
				return false;
			}

			$children = get_pages( array( 'child_of' => $post_id ) );

			if ( $children ) {
				return false;
			}

			return $allow;
		}

		/**
		 *
		 */
		public function post_year_permalinks() {
			add_rewrite_rule(
				'about-us/updates/([0-9]{4})/([0-9]{1,2})/?$',
				'index.php?post_type=post&year=$matches[1]&monthnum=$matches[2]',
				'top'
			);
			add_rewrite_rule(
				'about-us/updates/([0-9]{4})/?$',
				'index.php?post_type=post&year=$matches[1]',
				'top'
			);
		}

		/**
		 * @param $query
		 */
		public function set_post_years_query( $query ) {
			if ( ! is_admin() && $query->is_main_query() ) {
				if ( $query->is_author() || $query->is_category() || $query->is_home() || $query->is_tag() || $query->is_date() ) {
					$query->set( 'posts_per_page', '-1' );

					if ( ! $query->is_year() ) {
						$query->set( 'year', hny_get_years( 'post', null )[0] );
					}
				}
			}
		}

		/**
		 * @param $field
		 *
		 * @return mixed
		 */
		public function load_association_logo_choices( $field ) {
			$items            = hny_get_associations();
			$field['choices'] = array();

			if ( $items ) {
				foreach ( $items as $item ) {
					$field['choices'][ $item['company'] ] = $item['company'];

				}
			}

			return $field;
		}

		/**
		 * @param WP_Query $query
		 */
		public function change_taxonomy_posts_orderby( WP_Query $query ) {
			if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'rig_category' ) ) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
				$query->set( 'posts_per_page', - 1 );
			}
		}

		/**
		 * @param WP_Query $query
		 */
		public function change_industry_posts_orderby( WP_Query $query ) {
			if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'industry' ) ) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
				$query->set( 'posts_per_page', - 1 );
			}
		}


		/**
		 * @param $field
		 *
		 * @return mixed
		 */
		public function acf_load_icon_choices( $field ) {
			$choices = array();
			$dir     = new DirectoryIterator( THEME_ABSPATH . 'src/icons' );
			foreach ( $dir as $fileinfo ) {
				if ( 'svg' === $fileinfo->getExtension() ) {
					$slug             = $fileinfo->getBasename( '.svg' );
					$choices[ $slug ] = $slug;
				}
			}
			if ( $choices ) {
				asort( $choices );
				$field['choices'] = $choices;
			}

			return $field;
		}

		/**
		 *
		 */
		public function acf_google_api_key() {
			if ( function_exists( 'acf_update_setting' ) ) {
				acf_update_setting( 'google_api_key', $this->google_api_key );
			}
		}

		/**
		 * Adds localbusiness schema markup to wp_head.
		 */
		public function add_schema() {
			$type   = get_option( 'hny_local_business_type' );
			$logo   = get_option( 'hny_schema_logo' );
			$schema = array(
				'@id'        => home_url(),
				'@context'   => 'http://schema.org',
				'@type'      => $type ? str_replace( ' ', '', $type ) : 'LocalBusiness',
				'name'       => get_bloginfo( 'name' ),
				'url'        => get_home_url(),
				'telephone'  => '+1 ' . get_option( 'hny_phone_number' ),
				'address'    => array_filter(
					array(
						'@type'           => 'PostalAddress',
						'streetAddress'   => get_option( 'hny_street' ),
						'postalCode'      => get_option( 'hny_zip_postal_code' ),
						'addressLocality' => get_option( 'hny_city' ),
						'addressRegion'   => get_option( 'hny_province' ),
						'addressCountry'  => get_option( 'hny_country' ),
					)
				),
				'priceRange' => '$$$$',
				'image'      => $logo ? wp_get_attachment_image_url( $logo ) : hny_get_theme_image( 'logo.svg' ),
			);
			echo '<script type="application/ld+json">' . json_encode( array_filter( $schema ) ) . '</script>';
		}

		/**
		 * Load frontend theme classes.
		 */
		public function load_frontend_classes() {
			$id = get_the_ID();

			$this->hero           = new Theme_Hero( $id );
			$this->content_blocks = new Theme_Content_Blocks( is_post_type_archive( 'industry' ) ? 25 : get_the_ID() );
		}

		/**
		 * Sets 404 query properly so a listing doesn't get displayed by accident.
		 */
		public function set_404_query() {
			global $wp_query;
			if ( $wp_query->is_404() ) {
				$wp_query->init();
				$wp_query->is_404 = true;
			}
		}

		/**
		 * Handle template redirects.
		 * Redirect to first child page if attempting to access a parent page.
		 */
		public function handle_redirects() {
			if ( is_page() ) {
				global $wp_query;

				if ( 109 === $wp_query->queried_object_id ) {
					$args = array(
						'taxonomy' => 'rig_category',
						'fields'   => 'ids',
						'orderby'  => 'menu_order',
						'order'    => 'DESC',
					);

					$rig_category = current( get_terms( $args ) );
					wp_redirect( get_term_link( $rig_category, 'rig_category' ), 301 );
					exit;
				}

				$page_parent = $wp_query->queried_object->post_parent;

				if ( ! $page_parent ) {
					$page_id     = $wp_query->queried_object->ID;
					$child_pages = get_pages( 'child_of=' . $page_id . '&sort_column=menu_order' );

					if ( $child_pages ) {
						$firstchild = current( $child_pages );
						wp_redirect( get_permalink( $firstchild->ID ), 301 );
						exit;
					}
				}
			}
		}

		/**
		 * Set image size names.
		 *
		 * @param $sizes
		 *
		 * @return array
		 */
		public function image_size_names( $sizes ) {
			$labels = array();

			foreach ( $this->get_image_sizes() as $slug => $size ) {
				$labels[ $slug ] = $size['label'];
			}

			return array_merge( $sizes, $labels );
		}

		/**
		 * Changes default Gravity Forms input to a <button>.
		 *
		 * @param $button
		 *
		 * @return string
		 */
		public static function input_to_button( $button ) {
			$dom = new DOMDocument();
			$dom->loadHTML( $button );
			$input = $dom->getElementsByTagName( 'input' )->item( 0 );
			$input->setAttribute(
				'class',
				$input->getAttribute( 'class' ) . ' button--has-loader'
			);
			$new_button = $dom->createElement( 'button' );
			$new_button->appendChild(
				$dom->createElement( 'span', $input->getAttribute( 'value' ) )
			);
			$input->removeAttribute( 'value' );
			foreach ( $input->attributes as $attribute ) {
				$new_button->setAttribute( $attribute->name, $attribute->value );
			}
			// phpcs:disable
			$input->parentNode->replaceChild( $new_button, $input );
			$button = $dom->saveHtml( $new_button );

			ob_start();
			get_template_part( 'partials/loader' );
			$loader = ob_get_clean();

			ob_start();
			$new_button = str_replace( '<span>', $loader . '<span>', $button );

			echo $new_button;

			return ob_get_clean();
		}

		/**
		 * Add content block content to Relevanssi excerpts.
		 * Depending on the names of fields within any added modules, you may need to add those fields within the modules foreach below.
		 *
		 * @param $content
		 * @param $post
		 *
		 * @return string
		 */
		public function custom_fields_to_excerpts( $content, $post ) {
			$hero           = new Theme_Hero( $post->ID );
			$hero_items     = $hero->get_items();
			$content_blocks = new Theme_Content_Blocks( $post->ID );
			$rows           = $content_blocks->get_rows();

			if ( $hero_items ) {
				foreach ( $hero_items as $item ) {
					$values = array_filter( [
						$item['heading'],
						$item['subheading'],
						$item['text'],
					] );

					if ( $values ) {
						$content .= implode( ' ', $values );
					}
				}
			}

			if ( $rows ) {
				foreach ( $rows as $row ) {
					if ( $row->get_heading() ) {
						$content .= ' ' . $row->get_heading();
					}

					$columns = $row->get_columns();

					if ( $columns ) {
						foreach ( $columns as $column ) {
							$modules = $column->get_modules();

							if ( $column->get_heading() ) {
								$content .= ' ' . $column->get_heading();
							}

							if ( $column->get_subheading() ) {
								$content .= ' ' . $column->get_subheading();
							}

							if ( $modules ) {
								foreach ( $modules as $module ) {
									if ( $module->get_prop( 'heading' ) ) {
										$content .= ' ' . $module->get_prop( 'heading' );
									}

									if ( $module->get_prop( 'content' ) ) {
										$content .= ' ' . $module->get_prop( 'content' );
									}
								}
							}
						}
					}
				}
			}

			return strip_shortcodes( $content );
		}

		/**
		 * Adds a 'wysiwyg' div around any content coming from a tinyMCE editor (the_content).
		 *
		 * @param $content
		 *
		 * @return string
		 */
		public function add_wysiwyg_wrapper( $content ) {
			return '<div class="wysiwyg">' . $content . '</div>';
		}

		/**
		 * Theme_Hero getter.
		 *
		 * @return Theme_Hero
		 */
		public function get_hero() {
			return $this->hero;
		}

		/**
		 * Theme_Content_Blocks getter.
		 *
		 * @return Theme_Content_Blocks
		 */
		public function get_content_blocks() {
			return $this->content_blocks;
		}

		/**
		 * Theme_Menus getter.
		 *
		 * @return Theme_Menus
		 */
		public function get_menus() {
			return $this->menus;
		}

		/**
		 * Get image sizes array.
		 *
		 * @return array
		 */
		public function get_image_sizes() {
			return $this->image_sizes;
		}
	}
}
