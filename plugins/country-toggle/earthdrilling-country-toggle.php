<?php
/**
 * Plugin Name: Earth Drilling Country Toggle
 * Version:     2.2.2
 */

class Earth_Drilling_Country_Toggle {
	private string $current_site;
	private array $urls;
	private static ?self $instance = null;

	public static function get_instance(): self {
		return self::$instance ??= new self();
	}

	private function __construct() {
		$this->current_site = $this->detect_current_site();
		$this->urls = [
			'ca' => 'https://earthdrilling.com/ca/',
			'us' => 'https://earthdrilling.com/us/',
		];
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'wp_nav_menu_items', [ $this, 'inject_toggle_desktop' ], 10, 2 );
		add_action( 'wp_footer', [ $this, 'inject_toggle_mobile' ] );
		add_action( 'wp_head', [ $this, 'output_hreflang' ] );
		add_action( 'init', [ $this, 'deduplicate_canonical' ] );
	}

	private function detect_current_site(): string {
		$path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		return ( $path && false !== strpos( $path, '/us' ) ) ? 'us' : 'ca';
	}

	public function enqueue_assets(): void {
		wp_register_style( 'edct-toggle', false );
		wp_enqueue_style( 'edct-toggle' );
		wp_add_inline_style( 'edct-toggle', $this->inline_css() );
	}

	private function inline_css(): string {
		return <<<'CSS'
.edct-switch { position: relative; display: inline-flex; align-items: center; border-radius: 20px; background: rgba(0,0,0,.25); padding: 3px; gap: 0; box-shadow: inset 0 1px 3px rgba(0,0,0,0.3); }
.edct-switch__slider { position: absolute; top: 3px; left: 3px; width: calc(50% - 3px); height: calc(100% - 6px); border-radius: 17px; background: rgba(170, 151, 103, 0.6); transition: transform 0.3s cubic-bezier(0.68,-0.55,0.265,1.55); z-index: 0; }
.edct-switch--us .edct-switch__slider { transform: translateX(100%) translateX(1px); }
.edct-switch__side { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 17px; text-decoration: none; color: rgba(255,255,255,0.45); font-size: 10px; font-weight: 700; }
.edct-switch__side--active { color: #fff; }
.edct-flag-svg { width: 16px; height: 11px; border-radius: 2px; display: block; }
.header-utility__item.edct-toggle-item { display: flex !important; align-items: center; padding: 0 8px !important; border-left: 1px solid rgba(255,255,255,0.15) !important; }

/* Mobile Polish */
@media (max-width: 768px) {
    .masthead__logo a { width: 180px !important; }
    .site-header__masthead { padding: 12px 0 !important; }
    .header-utility__item.edct-toggle-item { display: none !important; }
    .site-footer__contact { min-height: auto !important; padding-top: 3rem !important; }
}

/* Headline & Overlap Polish */
.heading--drill { padding-top: 1.5rem !important; }
.heading--stacked { margin-top: -2rem !important; }
[data-context=light] .heading, [data-context=light] .heading span { color: #2d3e4f !important; }
.content-block__container { color: #2d3e4f !important; }

/* Overlap Rules */
.home.has-hero .content-block:first-child.content-block--has-decoration { margin-top: -8.75rem !important; }
@media (min-width: 64em) {
    .home.has-hero .content-block:first-child.content-block--has-decoration { margin-top: -18.875rem !important; }
}
.site-footer__card { background: #fff !important; border-radius: 8px !important; padding: 2rem 2.5rem !important; box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important; }
CSS;
	}

	public function inject_toggle_desktop( string $items, object $args ): string {
		if ( ! isset( $args->theme_location ) || 'header-utility' !== $args->theme_location ) { return $items; }
		$toggle_html = $this->render_switch( 'desktop' );
		$items = preg_replace( '/(<li[^>]*class="[^"]*header-utility__item[^"]*header-utility__item--dark[^"]*"[^>]*>.*?Request a Quote.*?<\/li>)/is', '$1' . $toggle_html, $items, 1, $count );
		if ( 0 === $count ) { $items .= $toggle_html; }
		return $items;
	}

	public function inject_toggle_mobile(): void {
		$toggle_html = $this->render_switch( 'mobile' );
		echo "<script>(function(){var m=document.querySelector('.mobile-utility__items');if(!m)return;var w=document.createElement('div');w.innerHTML=" . json_encode($toggle_html) . ";while(w.firstChild)m.appendChild(w.firstChild);})();</script>";
	}

	public function output_hreflang(): void {
		echo '<link rel="alternate" hreflang="en-CA" href="https://earthdrilling.com/ca/" />' . PHP_EOL;
		echo '<link rel="alternate" hreflang="en-US" href="https://earthdrilling.com/us/" />' . PHP_EOL;
		echo '<link rel="alternate" hreflang="x-default" href="https://earthdrilling.com/" />' . PHP_EOL;
	}

	public function deduplicate_canonical(): void {
		remove_action( 'wp_head', 'rel_canonical' );
	}

	private function flag_svg( string $country ): string {
		if ( 'ca' === $country ) { return '<svg class="edct-flag-svg" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="7" height="20" fill="#f00"/><rect x="7" width="16" height="20" fill="#fff"/><rect x="23" width="7" height="20" fill="#f00"/><path d="M15 6l.8 2 2.2.2-1.6 1.5.5 2.2L15 10.8l-1.9.9.5-2.2-1.6-1.5 2.2-.2z" fill="#f00"/></svg>'; }
		return '<svg class="edct-flag-svg" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="20" fill="#fff"/><rect y="1.5" width="30" height="1.5" fill="#b22234"/><rect y="4.5" width="30" height="1.5" fill="#b22234"/><rect y="7.5" width="30" height="1.5" fill="#b22234"/><rect y="10.8" width="30" height="1.5" fill="#b22234"/><rect y="14" width="30" height="1.5" fill="#b22234"/><rect y="17" width="30" height="1.5" fill="#b22234"/><rect width="12" height="10.8" fill="#3c3b6e"/></svg>';
	}

	private function render_switch( string $context = 'desktop' ): string {
		$other_site = ( 'ca' === $this->current_site ) ? 'us' : 'ca';
		$other_url  = "https://earthdrilling.com/{$other_site}/";
		$is_ca      = 'ca' === $this->current_site;
		$li_class = ( 'desktop' === $context ) ? 'header-utility__item header-utility__item--dark edct-toggle-item' : 'mobile-utility__item edct-toggle-item';
		$position_class = $is_ca ? 'edct-switch--ca' : 'edct-switch--us';
		$html  = '<li class="' . esc_attr( $li_class ) . '">';
		$html .= '<div class="edct-switch ' . esc_attr( $position_class ) . '" role="radiogroup" aria-label="Country selector">';
		$html .= $is_ca ? '<span class="edct-switch__side edct-switch__side--active" aria-current="true">' : '<a href="' . $other_url . '" class="edct-switch__side edct-switch__side--link">';
		$html .= $this->flag_svg( 'ca' ) . '<span class="edct-switch__label">CA</span>' . ( $is_ca ? '</span>' : '</a>' );
		$html .= $is_ca ? '<a href="' . $other_url . '" class="edct-switch__side edct-switch__side--link">' : '<span class="edct-switch__side edct-switch__side--active" aria-current="true">';
		$html .= $this->flag_svg( 'us' ) . '<span class="edct-switch__label">US</span>' . ( $is_ca ? '</a>' : '</span>' );
		$html .= '<span class="edct-switch__slider"></span></div></li>';
		return $html;
	}
}
Earth_Drilling_Country_Toggle::get_instance();
