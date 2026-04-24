<?php
/**
 * Generates Foundation Interchange Data Attribute.
 *
 * @param       $id
 * @param array $breakpoints
 *
 * @return string
 */
function hny_interchange( $id, $breakpoints = array() ) {
	if ( ! $id ) {
		return '';
	}

	$sizes = hny_get_image_sizes();

	if ( $breakpoints ) {
		foreach ( $sizes as $slug => $size ) {
			if ( ! in_array( $size['breakpoint'], $breakpoints ) ) {
				unset( $sizes[ $slug ] );
			}
		}
	}

	if ( ! $sizes ) {
		return '';
	}

	$attrs = array_map(
		function( $slug, $size ) use ( $id ) {
			return '[' . hny_get_image_url( $id, $slug ) . ', ' . $size['breakpoint'] . ']';
		},
		array_keys( $sizes ),
		$sizes
	);

	return 'data-interchange="' . esc_attr( implode( ', ', $attrs ) ) . '"';
}

/**
 * Public accessor to get image sizes from Theme class.
 *
 * @return array
 */
function hny_get_image_sizes() {
	return Theme()->get_image_sizes();
}

/**
 * Returns a URL for an attachment image ID.
 *
 * @package    WordPress
 * @subpackage hny-theme_boilerplate
 * @since      1.0
 *
 * @param int $id Attachment image ID.
 * @param string $size Image size.
 *
 * @return string Image URL.
 */
function hny_get_image_url( $id, $size = 'thumbnail' ) {
	$src = wp_get_attachment_image_src( absint( $id ), $size );
	return $src[0] ?? false;
}

/**
 * Returns the url of a post thumbnail for the current post. Can also specify
 * post ID and image size.
 *
 * @package    WordPress
 * @subpackage hny-theme_boilerplate
 * @since      1.0
 *
 * @param string $size The image size to return. Use 'full' for full image.
 * @param int    $id   ID of another post to return.
 *
 * @return string|bool Image URL or false
 */
function hny_get_featured_image( $id, $size = 'thumbnail' ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	if ( has_post_thumbnail( $id ) ) {
		return hny_get_image_url( get_post_thumbnail_id( $id ), $size );
	} else {
		return false;
	}
}

/**
 * Returns the url of a theme image.
 *
 * @param $filename
 *
 * @return string
 */
function hny_get_theme_image( $filename ) {
	return get_stylesheet_directory_uri() . '/dist/images/' . $filename;
}
