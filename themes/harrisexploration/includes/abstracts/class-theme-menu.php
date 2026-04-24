<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme_Menu
 */
class Theme_Menu {
	/**
	 * Theme_Menu args.
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * The menu label.
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 * The menu slug.
	 *
	 * @var
	 */
	protected $slug = '';

	/**
	 * Theme_Menu constructor.
	 *
	 * @param $label
	 * @param $args
	 */
	function __construct( $label = '', $args = array() ) {
		if ( $label ) {
			$this->set_label( $label );
			$this->set_slug( $label );
			$this->set_args( $args );
		}
	}

	/**
	 * Set nav menu label.
	 *
	 * @param $label
	 */
	private function set_label( $label ) {
		$this->label = $label;
	}

	/**
	 * Set slug based on provided label.
	 *
	 * @param $label
	 */
	private function set_slug( $label ) {
		$this->slug = sanitize_title( $label );
	}

	/**
	 * Set nav menu args, merge with some sensible defaults.
	 *
	 * @param $args
	 */
	private function set_args( $args ) {
		$accordion = false;
		$drilldown = false;
		$defaults  = array(
			'container'              => 'nav',
			'container_class'        => $this->get_slug(),
			'menu_class'             => $this->get_slug() . '__items',
			'items_wrap'             => '<h2 class="u-screen-reader">' . $this->get_label() . ' Menu</h2><div class="' . $this->get_slug() . '__wrapper"><ul class="%2$s">%3$s</ul></div>',
			'theme_location'         => $this->get_slug(),
			'before'                 => '',
			'after'                  => '',
			'depth'                  => 3,
			'fallback_cb'            => false,
			'sub_menu'               => false,
			'expand_siblings'        => true,
			'direct_parent'          => false,
			'show_parent'            => true,
			'no_results_hide_parent' => false,
			'limit_depth_no_active'  => false,
			'walker'                 => new Top_Bar_Walker(),
		);

		if ( isset( $args['accordion'] ) && true === $args['accordion'] ) {
			unset( $args['accordion'] );
			$accordion = true;
		}

		if ( isset( $args['drilldown'] ) && true === $args['drilldown'] ) {
			unset( $args['drilldown'] );
			$drilldown = true;
		}

		$this->args = wp_parse_args( $args, $defaults );

		if ( $accordion ) {
			$this->args['menu_class'] .= ' accordion-menu';
			$this->args['items_wrap']  = str_replace(
				'<ul',
				'<ul data-accordion-menu data-multi-open="false"',
				$this->args['items_wrap']
			);
		}

		if ( $drilldown ) {
			$this->args['menu_class'] .= ' drilldown';
			$this->args['items_wrap']  = str_replace(
				'<ul',
				'<ul data-drilldown',
				$this->args['items_wrap']
			);
		}
	}

	/**
	 * Get nav menu slug.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get nav menu label.
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Register nav menu.
	 */
	public function register() {
		if ( $this->get_slug() && $this->get_label() ) {
			register_nav_menu( $this->get_slug(), $this->get_label() );
		}
	}

	/**
	 * Output nav menu if it is registered and has a menu assigned.
	 */
	public function render() {
		if ( has_nav_menu( $this->get_slug() ) ) {
			wp_nav_menu( $this->get_args() );
		}
	}

	/**
	 * Get nav menu args.
	 *
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}
}
