<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme_Icons
 */
class Theme_Icons {
	/**
	 * Array of SVG icons for output.
	 *
	 * @var array
	 */
	private static $icons = array();

	/**
	 * Add inline SVG hooks.
	 */
	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'include_icons' ) );
	}

	/**
	 * Return SVG markup.
	 *
	 * @param array $args  Parameters needed to display an SVG.
	 *
	 * @type string $icon  Required SVG icon filename.
	 * @type string $title Optional SVG title.
	 * @type string $desc  Optional SVG description.
	 *
	 * @return string SVG markup.
	 */
	public static function get_icon( $args = array() ) {
		if ( empty( $args ) ) {
			return __( 'Please define default parameters in the form of an array.', 'hny' );
		}

		if ( false === array_key_exists( 'icon', $args ) ) {
			return __( 'Please define an SVG icon filename.', 'hny' );
		}

		$defaults = array(
			'icon'     => '',
			'title'    => '',
			'desc'     => '',
			'fallback' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$unique_id       = uniqid();
		$aria_hidden     = ' aria-hidden="true"';
		$aria_labelledby = '';

		if ( $args['title'] ) {
			$aria_hidden     = '';
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

			if ( $args['desc'] ) {
				$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
			}
		}

		$svg = '<svg class="hny-svg hny-svg--' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		if ( $args['title'] ) {
			$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

			if ( $args['desc'] ) {
				$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
			}
		}

		$svg .= ' <use href="#hny-svg-' . esc_html( $args['icon'] ) . '" xlink:href="#hny-svg-' . esc_html( $args['icon'] ) . '"></use> ';

		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback hny-svg--' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		if ( ! in_array( $args['icon'], self::$icons ) ) {
			self::$icons[] = $args['icon'];
		}

		return $svg;
	}

	/**
	 * Add SVG definitions to the footer.
	 */
	public static function include_icons() {
		$icons = array_filter(
			self::$icons,
			function ( $icon ) {
				$file = THEME_ABSPATH . 'dist/icons/' . $icon . '.svg';

				return file_exists( $file );
			}
		);

		if ( $icons ) {
			?>
			<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"
				xmlns="http://www.w3.org/2000/svg">
				<defs>
					<?php
					foreach ( $icons as $icon ) {
						$file = THEME_ABSPATH . 'dist/icons/' . $icon . '.svg';
						echo file_get_contents( $file );
					}
					?>
				</defs>
			</svg>
			<?php
		}
	}
}

Theme_Icons::init();
