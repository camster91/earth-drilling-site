<?php
/**
 * Plugin Name: Earth Drilling Country Toggle
 * Description: Adds a sliding country toggle switch (CA/US) to the header utility nav and mobile nav. Install on BOTH /ca/ and /us/ WordPress sites.
 * Version:     2.2.0
 * Author:      Earth Drilling
 * License:     GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Earth_Drilling_Country_Toggle {

	private string $current_site;
	private array $urls;
	private static ?self $instance = null;

	public static function get_instance(): self {
		return self::$instance ??= new self();
	}

	private function __construct() {
		$this->current_site = $this->detect_current_site();

		// Use absolute HTTPS URL to avoid broken paths on subsites
		$this->urls = [
			'ca' => 'https://earthdrilling.com/ca/',
			'us' => 'https://earthdrilling.com/us/',
		];

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_filter( 'wp_nav_menu_items', [ $this, 'inject_toggle_desktop' ], 10, 2 );
		add_action( 'wp_footer', [ $this, 'inject_toggle_mobile' ] );

		/* SEO fixes */
		add_action( 'wp_head', [ $this, 'output_hreflang' ] );
		add_action( 'init', [ $this, 'deduplicate_canonical' ] );
	}

	private function detect_current_site(): string {
		if ( defined( 'EDCT_CURRENT_SITE' ) && in_array( EDCT_CURRENT_SITE, [ 'ca', 'us' ], true ) ) {
			return EDCT_CURRENT_SITE;
		}
		$path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
		if ( $path && false !== strpos( $path, '/us' ) ) {
			return 'us';
		}
		return 'ca';
	}

	public function enqueue_assets(): void {
		wp_register_style(
			'edct-toggle',
			false // no external CSS file — inline instead
		);
		wp_enqueue_style( 'edct-toggle' );
		wp_add_inline_style( 'edct-toggle', $this->inline_css() );
	}

	/* ------------------------------------------------------------------ *
	 *  Inline CSS — no extra HTTP request, instant render
	 * ------------------------------------------------------------------ */

	private function inline_css(): string {
		return <<<'CSS'
.edct-switch{position:relative;display:inline-flex;align-items:center;border-radius:20px;background:rgba(0,0,0,.25);padding:3px;gap:0;box-shadow:inset 0 1px 3px rgba(0,0,0,.3);transition:box-shadow .15s,transform .15s}
.edct-switch:hover{box-shadow:inset 0 1px 3px rgba(0,0,0,.45)}
.edct-switch__slider{position:absolute;top:3px;left:3px;width:calc(50% - 3px);height:calc(100% - 6px);border-radius:17px;background:rgba(59,74,103,.6);transition:transform .3s cubic-bezier(.68,-.55,.265,1.55),background .2s;width:calc(50% - 2px);z-index:0;box-shadow:0 1px 4px rgba(0,0,0,.2)}
.edct-switch:hover .edct-switch__slider{background:rgba(59,74,103,.85)}
.edct-switch--us .edct-switch__slider{transform:translateX(100%) translateX(1px)}
.edct-switch__side{position:relative;z-index:1;display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:17px;text-decoration:none;transition:color .2s,transform .15s;text-shadow:none;white-space:nowrap}
.edct-switch__side--active{color:#fff;font-weight:700;cursor:default;text-shadow:0 0 8px rgba(255,255,255,.3)}
.edct-switch__side--link{color:rgba(255,255,255,.45);cursor:pointer}
.edct-switch__side--link:hover{color:rgba(255,255,255,.9);transform:scale(1.05)}
.edct-flag-svg{width:16px;height:11px;border-radius:2px;display:block;flex-shrink:0;transition:transform .15s}
.edct-switch:hover .edct-flag-svg{transform:scale(1.1)}
.edct-switch__label{font-size:10px;font-weight:700;letter-spacing:.06em;line-height:1;text-transform:uppercase}
.header-utility__item.edct-toggle-item{display:flex!important;flex-direction:row!important;align-items:center!important;justify-content:center!important;padding:0 8px!important;border-left:1px solid rgba(255,255,255,.15)!important}
.mobile-utility__item.edct-toggle-item{display:flex!important;align-items:center!important;justify-content:center!important;border-top:1px solid rgba(255,255,255,.15);margin:8px 0 0;padding:10px 0!important}
@media(max-width:768px){.edct-switch{padding:4px}.edct-switch__side{padding:6px 16px;gap:6px}.edct-flag-svg{width:20px;height:14px}.edct-switch__label{font-size:12px}.edct-switch__slider{top:4px;left:4px;width:calc(50% - 4px);height:calc(100% - 8px)}}
@media(max-width:768px){.header-utility__item.edct-toggle-item{display:none!important}}
@media(max-width:768px){.masthead__logo a{width:150px!important}.masthead__logo .u-svg-container--logo{padding-bottom:23%!important}}
.home .page-content .l-container{padding:0!important;margin:0!important}.home .page-content .wysiwyg:empty{display:none!important}
.home.has-hero .content-block:first-child.content-block--has-decoration{margin-top:-14rem!important}@media print,screen and (min-width:64em){.home.has-hero .content-block:first-child.content-block--has-decoration{margin-top:-24rem!important}}@media screen and (min-width:103.125em){.home.has-hero .content-block:first-child.content-block--has-decoration{margin-top:-32rem!important}}
.u-svg-container--logo img,.u-svg-container--footer-logo img{object-fit:contain}
.masthead__logo a{width:300px!important;display:block}
.masthead__logo .u-svg-container--logo{position:relative!important;width:100%!important;height:auto!important;padding-bottom:23%!important}
.masthead__logo .u-svg-container--logo img{position:absolute!important;top:0!important;left:0!important;width:100%!important;height:100%!important;display:block}
.site-footer__logo{max-width:240px!important;width:240px!important;display:block!important}
.site-footer__logo .u-svg-container--footer-logo{position:relative!important;width:100%!important;height:auto!important;padding-bottom:23%!important}
.site-footer__logo .u-svg-container--footer-logo img{position:absolute!important;top:0!important;left:0!important;width:100%!important;height:100%!important;display:block}
.site-footer__contact{min-height:620px!important}
.site-footer__content{padding-bottom:4rem!important}
.site-footer__colophon{padding-top:1.25rem!important;padding-bottom:1.25rem!important}
.recent-updates{padding-bottom:3rem!important}
.recent-updates .grid-x{margin-bottom:0!important}
.site-footer__columns .site-footer__card{background:#fff!important;border-radius:8px!important;padding:2rem 2.5rem!important;box-shadow:0 4px 20px rgba(0,0,0,.12)!important}
.site-footer__columns .grid-x{margin:0!important}
.site-footer__intro{font-size:.8125rem!important;color:#9fafbc!important;margin:0 0 1rem!important;padding-bottom:1rem!important;border-bottom:1px solid #e0e4eb!important;line-height:1.4!important;text-transform:uppercase!important;letter-spacing:.05em!important;font-weight:700!important}
.heading--stacked{color:#fff!important}
.heading--stacked span{color:#fff!important}
.heading--drill span{color:#fff!important}
.heading--drill:after,.heading--drill:before{background:#fff!important}
[data-context=dark] .heading{color:#fff!important}
[data-context=dark] .heading span{color:#fff!important}
[data-context=light] .heading,[data-context=light] .heading span,[data-context=light] .heading--drill,[data-context=light] .heading--drill span,[data-context=light] .heading--stacked,[data-context=light] .heading--stacked span{color:#1e2534!important}
.site-footer__columns address{color:#3f4e59!important;line-height:1.6!important}
.site-footer__columns address a{color:#3b4a67!important}
.site-footer__columns .heading span{color:#1e2534!important}
.site-footer__heading{display:none!important}
@media(max-width:768px){.site-footer__columns .site-footer__card{padding:1.5rem 1rem!important;border-radius:4px!important}.site-footer__intro{font-size:.75rem!important;margin-bottom:.75rem!important;padding-bottom:.75rem!important}}
.recent-updates .article,.listing .listing__item .article{background:#fff!important;border-radius:8px!important;box-shadow:0 2px 12px rgba(0,0,0,.08)!important;overflow:hidden!important;margin-bottom:2rem!important}
.recent-updates .article__image-wrapper{border-radius:0!important;margin-bottom:0!important}
.recent-updates .article .heading,.listing .article .heading{font-size:1rem!important;padding:1rem 1.25rem 0!important}
.recent-updates .article .heading a,.listing .article .heading a{color:#1e2534!important}
.recent-updates .article__content,.listing .article__content{padding:0 1.25rem .75rem!important;font-size:.875rem!important;color:#4f4c4c!important}
.recent-updates .read-more,.listing .article .read-more{padding:0 1.25rem 1.25rem!important;display:inline-block!important}
.recent-updates .cell--flex{display:flex!important;flex-direction:column!important}
/* Hide green registered logo on US site */
.logo-list__item img[src*='registered_logo_green']{display:none!important}
@media(max-width:768px){.recent-updates .article,.listing .listing__item .article{border-radius:4px!important;margin-bottom:1.5rem!important}.recent-updates .article .heading,.listing .article .heading{padding:.75rem 1rem 0!important}.recent-updates .article__content,.listing ..article__content{padding:0 1rem .5rem!important}.recent-updates .read-more,.listing .article .read-more{padding:0 1rem 1rem!important}}
CSS;
	}

	/* ------------------------------------------------------------------ *
	 *  Desktop — inject after "Request a Quote"
	 * ------------------------------------------------------------------ */

	public function inject_toggle_desktop( string $items, object $args ): string {
		if ( ! isset( $args->theme_location ) || 'header-utility' !== $args->theme_location ) {
			return $items;
		}

		$toggle_html = $this->render_switch( 'desktop' );

		$items = preg_replace(
			'/(<li[^>]*class="[^"]*header-utility__item[^"]*header-utility__item--dark[^"]*"[^>]*>.*?Request a Quote.*?<\/li>)/is',
			'$1' . $toggle_html,
			$items,
			1,
			$count
		);

		if ( 0 === $count ) {
			$items .= $toggle_html;
		}

		return $items;
	}

	/* ------------------------------------------------------------------ *
	 *  Mobile — inject via wp_footer
	 * ------------------------------------------------------------------ */

	public function inject_toggle_mobile(): void {
		$toggle_html = $this->render_switch( 'mobile' );
		$other_site  = ( 'ca' === $this->current_site ) ? 'us' : 'ca';
		$other_url  = esc_url( $this->urls[ $other_site ] );
		?>
		<script>
		(function(){
			var m=document.querySelector('.mobile-utility__items');
			if(!m)return;
			var w=document.createElement('div');
			w.innerHTML=<?php echo json_encode( $toggle_html ); ?>;
			while(w.firstChild)m.appendChild(w.firstChild);
			/* Snappy cross-fade between sites */
			document.documentElement.style.transition='opacity 80ms ease-out';
			var links=document.querySelectorAll('.edct-switch__side--link');
			links.forEach(function(l){
				l.addEventListener('click',function(e){
					e.preventDefault();
					var h=l.getAttribute('href');
					if(!h)return;
					document.documentElement.style.opacity='0';
					setTimeout(function(){window.location.href=h;},85);
				});
			});
		})();
		</script>
		<?php
	}

	/* ------------------------------------------------------------------ *
	 *  SEO: Output hreflang tags for CA/US regional variants
	 * ------------------------------------------------------------------ */

	public function output_hreflang(): void {
		$ca_url = esc_url( $this->urls['ca'] );
		$us_url = esc_url( $this->urls['us'] );
		echo '<link rel="alternate" hreflang="en-CA" href="' . $ca_url . '" />' . "\n";
		echo '<link rel="alternate" hreflang="en-US" href="' . $us_url . '" />' . "\n";
		echo '<link rel="alternate" hreflang="x-default" href="https://earthdrilling.com/" />' . "\n";
	}

	/* ------------------------------------------------------------------ *
	 *  SEO: Prevent duplicate canonical — Rank Math outputs one already;
	 *  the theme outputs a second. Remove the theme's via early filter.
	 * ------------------------------------------------------------------ */

	public function deduplicate_canonical(): void {
		remove_action( 'wp_head', 'rel_canonical' );
	}

	/* ------------------------------------------------------------------ *
	 *  SVG flags — simplified for fast render
	 * ------------------------------------------------------------------ */

	private function flag_svg( string $country ): string {
		if ( 'ca' === $country ) {
			return '<svg class="edct-flag-svg" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="7" height="20" fill="#f00"/><rect x="7" width="16" height="20" fill="#fff"/><rect x="23" width="7" height="20" fill="#f00"/><path d="M15 6l.8 2 2.2.2-1.6 1.5.5 2.2L15 10.8l-1.9.9.5-2.2-1.6-1.5 2.2-.2z" fill="#f00"/></svg>';
		}
		// US flag — simplified: blue canton + red/white stripes
		return '<svg class="edct-flag-svg" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="20" fill="#fff"/><rect y="1.5" width="30" height="1.5" fill="#b22234"/><rect y="4.5" width="30" height="1.5" fill="#b22234"/><rect y="7.5" width="30" height="1.5" fill="#b22234"/><rect y="10.8" width="30" height="1.5" fill="#b22234"/><rect y="14" width="30" height="1.5" fill="#b22234"/><rect y="17" width="30" height="1.5" fill="#b22234"/><rect width="12" height="10.8" fill="#3c3b6e"/></svg>';
	}

	/* ------------------------------------------------------------------ *
	 *  Render the sliding toggle switch
	 * ------------------------------------------------------------------ */

	private function render_switch( string $context = 'desktop' ): string {
		$other_site = ( 'ca' === $this->current_site ) ? 'us' : 'ca';
		$other_url  = esc_url( $this->urls[ $other_site ] );
		$is_ca      = 'ca' === $this->current_site;

		$li_class = ( 'desktop' === $context )
			? 'header-utility__item header-utility__item--dark edct-toggle-item'
			: 'mobile-utility__item edct-toggle-item';

		$position_class = $is_ca ? 'edct-switch--ca' : 'edct-switch--us';

		$html  = '<li class="' . esc_attr( $li_class ) . '">';
		$html .= '<div class="edct-switch ' . esc_attr( $position_class ) . '" role="radiogroup" aria-label="Country selector">';

		// Canada side
		$html .= $is_ca
			? '<span class="edct-switch__side edct-switch__side--active" aria-current="true">'
			: '<a href="' . $other_url . '" class="edct-switch__side edct-switch__side--link">';
		$html .= $this->flag_svg( 'ca' );
		$html .= '<span class="edct-switch__label">CA</span>';
		$html .= $is_ca ? '</span>' : '</a>';

		// US side
		$html .= $is_ca
			? '<a href="' . $other_url . '" class="edct-switch__side edct-switch__side--link">'
			: '<span class="edct-switch__side edct-switch__side--active" aria-current="true">';
		$html .= $this->flag_svg( 'us' );
		$html .= '<span class="edct-switch__label">US</span>';
		$html .= $is_ca ? '</a>' : '</span>';

		// Slider indicator
		$html .= '<span class="edct-switch__slider"></span>';
		$html .= '</div>';
		$html .= '</li>';

		return $html;
	}
}

Earth_Drilling_Country_Toggle::get_instance();