<?php
/**
 * Plugin Name: ED Color Preview Toggle
 * Description: Allows previewing gold (#aa9767) color scheme on the CA site.
 * Add ?ed_colors=gold to any URL to preview. Remove param to revert.
 * Set ed_colors_permanent option to '1' via WP-CLI to go live.
 * Version: 0.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function() {
	$use_gold = false;

	if ( isset( $_GET['ed_colors'] ) && 'gold' === $_GET['ed_colors'] ) {
		$use_gold = true;
	}

	if ( '1' === get_option( 'ed_colors_permanent', '0' ) ) {
		$use_gold = true;
	}

	if ( ! $use_gold ) {
		return;
	}

	$theme_uri = get_stylesheet_directory_uri();
	$theme_dir = get_stylesheet_directory();

	// Dequeue original main stylesheet
	foreach ( wp_styles()->registered as $handle => $details ) {
		if ( isset( $details->src ) && strpos( $details->src, 'dist/styles/main-' ) !== false && strpos( $details->src, 'main-gold' ) === false ) {
			wp_dequeue_style( $handle );
		}
	}

	// Enqueue gold main stylesheet
	wp_enqueue_style(
		'edct-gold-theme',
		$theme_uri . '/dist/styles/main-gold.css',
		[],
		filemtime( $theme_dir . '/dist/styles/main-gold.css' )
	);

	// Enqueue gold editor stylesheet (for Gutenberg block styles)
	wp_enqueue_style(
		'edct-gold-editor',
		$theme_uri . '/dist/styles/editor-gold.css',
		[],
		filemtime( $theme_dir . '/dist/styles/editor-gold.css' )
	);
}, 999 );

/**
 * Swap SVG background images from green to gold versions.
 * Used by the theme's u-photo-bg and similar classes.
 */
add_filter( 'style_loader_tag', function( $tag, $handle ) {
	return $tag;
}, 10, 2 );

/**
 * Replace green SVG references in rendered HTML with gold versions.
 */
add_action( 'wp_footer', function() {
	if ( ! is_gold_mode() ) {
		return;
	}
	?>
	<script>
	(function(){
		// Swap green SVG backgrounds for gold versions
		var svgMap = {
			'background_secondary_page.svg': 'background_secondary_page-gold.svg',
			'footer-bg.svg': 'footer-bg-gold.svg',
			'industry-bg.svg': 'industry-bg-gold.svg',
			'rig-bg.svg': 'rig-bg-gold.svg',
			'section-bg.svg': 'section-bg-gold.svg'
		};
		// Check all elements with background-image URLs
		document.querySelectorAll('[style*="background-image"]').forEach(function(el){
			var style = el.getAttribute('style');
			for (var green in svgMap) {
				if (style.indexOf(green) !== -1) {
					el.setAttribute('style', style.replace(green, svgMap[green]));
				}
			}
		});
		// Also swap CSS background-image URLs in stylesheets that reference SVG files
		for (var i = 0; i < document.styleSheets.length; i++) {
			try {
				var sheet = document.styleSheets[i];
				var rules = sheet.cssRules || sheet.rules;
				for (var j = 0; j < rules.length; j++) {
					var rule = rules[j];
					if (rule.style && rule.style.backgroundImage) {
						var bg = rule.style.backgroundImage;
						for (var green in svgMap) {
							if (bg.indexOf(green) !== -1) {
								rule.style.backgroundImage = bg.replace(green, svgMap[green]);
							}
						}
					}
				}
			} catch(e) {
				// Cross-origin stylesheet - skip
			}
		}
	})();
	</script>
	<?php
}, 99 );

/**
 * Helper: check if gold mode is active.
 */
function is_gold_mode(): bool {
	if ( isset( $_GET['ed_colors'] ) && 'gold' === $_GET['ed_colors'] ) {
		return true;
	}
	return '1' === get_option( 'ed_colors_permanent', '0' );
}
