<?php
/**
 * Plugin Name: Earth Drilling Country Redirect
 * Plugin URI:  https://earthdrilling.com
 * Description: Displays a full-screen country selector popup on the root domain, directing visitors to either the Canadian (/ca/) or US (/us/) site. Stores preference in a cookie for 30 days.
 * Version:     1.0.0
 * Author:      Earth Drilling
 * License:     GPL-2.0+
 *
 * This plugin should be installed on the ROOT WordPress site (earthdrilling.com).
 * The Canadian site lives at /ca/ and the US site at /us/.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constants – adjust these if the URL paths ever change.
 */
define( 'EDCR_CA_URL', '/ca/' );
define( 'EDCR_US_URL', '/us/' );
define( 'EDCR_COOKIE_NAME', 'ed_country' );
define( 'EDCR_COOKIE_EXPIRY', 30 * DAY_IN_SECONDS ); // 30 days

class Earth_Drilling_Country_Redirect {

	/** Singleton */
	private static ?self $instance = null;

	public static function get_instance(): self {
		return self::$instance ??= new self();
	}

	private function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'wp_footer', [ $this, 'render_popup' ] );
		add_action( 'template_redirect', [ $this, 'handle_redirect' ] );

		/* SEO: hreflang on root site */
		add_action( 'wp_head', [ $this, 'output_hreflang' ] );

		/* SEO: Remove duplicate canonical (Rank Math + theme both output one) */
		add_action( 'init', [ $this, 'deduplicate_canonical' ] );
	}

	/* ------------------------------------------------------------------ *
	 *  SEO: Output hreflang tags on the root domain.
	 *  Points to both sub-sites and to itself as x-default.
	 * ------------------------------------------------------------------ */

	public function output_hreflang(): void {
		$ca_url = esc_url( home_url( EDCR_CA_URL ) );
		$us_url = esc_url( home_url( EDCR_US_URL ) );
		$root   = esc_url( home_url( '/' ) );
		echo '<link rel="alternate" hreflang="en-CA" href="' . $ca_url . '" />' . "\n";
		echo '<link rel="alternate" hreflang="en-US" href="' . $us_url . '" />' . "\n";
		echo '<link rel="alternate" hreflang="x-default" href="' . $root . '" />' . "\n";
	}

	/* ------------------------------------------------------------------ *
	 *  SEO: Remove duplicate canonical — Rank Math outputs one;
	 *  the theme outputs a second via rel_canonical. Remove the theme's.
	 * ------------------------------------------------------------------ */

	public function deduplicate_canonical(): void {
		remove_action( 'wp_head', 'rel_canonical' );
	}

	/* ------------------------------------------------------------------ *
	 *  Redirect logic – if a returning visitor already has a cookie,
	 *  send them straight to the right site without showing the popup.
	 * ------------------------------------------------------------------ */

	public function handle_redirect(): void {
		// Only redirect on the front page of the root site.
		if ( ! is_front_page() ) {
			return;
		}

		// Don't redirect if this is a WP admin / REST / cron request.
		if ( is_admin() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		$country = sanitize_text_field( $_COOKIE[ EDCR_COOKIE_NAME ] ?? '' );

		if ( $country === 'ca' ) {
			wp_redirect( home_url( EDCR_CA_URL ), 302 );
			exit;
		}
		if ( $country === 'us' ) {
			wp_redirect( home_url( EDCR_US_URL ), 302 );
			exit;
		}
	}

	/* ------------------------------------------------------------------ *
	 *  Enqueue CSS / JS
	 * ------------------------------------------------------------------ */

	public function enqueue_assets(): void {
		// Only load on front page.
		if ( ! is_front_page() ) {
			return;
		}

		wp_enqueue_style(
			'edcr-popup',
			plugin_dir_url( __FILE__ ) . 'css/popup.css',
			[],
			'1.0.0'
		);

		wp_enqueue_script(
			'edcr-popup',
			plugin_dir_url( __FILE__ ) . 'js/popup.js',
			[],
			'1.0.0',
			true  // load in footer
		);

		wp_localize_script( 'edcr-popup', 'edcr', [
			'caUrl'       => home_url( EDCR_CA_URL ),
			'usUrl'       => home_url( EDCR_US_URL ),
			'cookieName'  => EDCR_COOKIE_NAME,
			'cookieExpiry' => EDCR_COOKIE_EXPIRY,
		] );
	}

	/* ------------------------------------------------------------------ *
	 *  Render the popup HTML in the footer.
	 * ------------------------------------------------------------------ */

	public function render_popup(): void {
		// Only show on front page.
		if ( ! is_front_page() ) {
			return;
		}

		// If visitor already has a cookie, a JS redirect will handle it;
		// the PHP redirect above already caught server-side requests.
		?>
		<div id="edcr-overlay" class="edcr-overlay">
			<div class="edcr-popup">
				<button class="edcr-close" id="edcr-close" aria-label="Close country selector">&times;</button>



				<div class="edcr-popup__content">
					<div class="edcr-popup__logo">
						<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/logo.png' ); ?>" alt="Earth Drilling" class="edcr-popup__logo-img" />
					</div>

					<h2 class="edcr-popup__heading">Choose Your Region</h2>
					<p class="edcr-popup__subheading">Select the site that serves your area</p>

					<div class="edcr-popup__cards">
						<a href="<?php echo esc_url( home_url( EDCR_CA_URL ) ); ?>" class="edcr-card edcr-card--ca" data-country="ca">
							<div class="edcr-card__flag">🇨🇦</div>
							<h3 class="edcr-card__title">Canada</h3>
							<span class="edcr-card__btn">Enter Site &rarr;</span>
						</a>

						<a href="<?php echo esc_url( home_url( EDCR_US_URL ) ); ?>" class="edcr-card edcr-card--us" data-country="us">
							<div class="edcr-card__flag">🇺🇸</div>
							<h3 class="edcr-card__title">United States</h3>
							<span class="edcr-card__btn">Enter Site &rarr;</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

/* Initialise */
Earth_Drilling_Country_Redirect::get_instance();