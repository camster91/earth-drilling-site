<?php
/**
 * Like get_template_part() put lets you pass args to the template file
 * Args are available in the template as $template_args array
 *
 * @param string $file          template part.
 * @param mixed  $template_args wp_args style argument list.
 * @param mixed  $cache_args    wp_args style argument list.
 *
 * @return string|null
 * @subpackage hny-theme_boilerplate
 * @since      1.0
 *
 * @link       https://github.com/humanmade/hm-core/blob/master/hm-core.functions.php
 *
 * @package    WordPress
 */
function hny_get_template_part( $file, $template_args = array(), $cache_args = array() ) {
	$template_args = wp_parse_args( $template_args );
	$cache_args    = wp_parse_args( $cache_args );
	if ( $cache_args ) {
		foreach ( $template_args as $key => $value ) {
			if ( is_scalar( $value ) || is_array( $value ) ) {
				$cache_args[ $key ] = $value;
			} elseif ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
				$cache_args[ $key ] = call_user_func( 'get_id', $value );
			}
		}

		$cache = wp_cache_get( $file, wp_json_encode( $cache_args ) );

		if ( false !== $cache ) {
			if ( ! empty( $template_args['return'] ) ) {
				return $cache;
			}
			echo $cache;
		}
	}
	$file_handle = $file;
	do_action( 'start_operation', 'hny_template_part::' . $file_handle );
	$partial = null;
	if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) ) {
		$partial = get_stylesheet_directory() . '/' . $file . '.php';
	} elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) ) {
		$partial = get_template_directory() . '/' . $file . '.php';
	}

	if ( $partial && file_exists( $partial ) ) {
		ob_start();
		$return = require $partial;
		$data   = ob_get_clean();

		do_action( 'end_operation', 'hny_template_part::' . $file_handle );
		if ( $cache_args ) {
			wp_cache_set( $file, $data, wp_json_encode( $cache_args ), 3600 );
		}

		if ( ! empty( $template_args['return'] ) ) {
			if ( false === $return ) {
				return false;
			} else {
				return $data;
			}
		}

		echo $data;
	} else {
		echo '<strong>Partial not found: </strong> ' . $file;
	}

	return false;
}
