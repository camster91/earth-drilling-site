<?php
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
function hny_get_svg( $args = array() ) {
	return Theme_Icons::get_icon( $args );
}
