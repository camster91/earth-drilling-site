<?php
/**
 * @return bool
 */
function hny_is_blog() {
	return is_author() || is_category() || is_home() || is_tag() || is_date();
}

/**
 * @param $url
 *
 * @return string
 */
function hny_get_youtube_embed_url( $url ) {
	return 'https://www.youtube.com/embed/' . hny_get_youtube_id( $url ) . '?rel=0&autoplay=1&showinfo=0';
}

/**
 * @param $url
 *
 * @return mixed|string
 */
function hny_get_youtube_id( $url ) {
	if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
		return isset( $match[1] ) && ! empty( $match[1] ) ? $match[1] : '';
	} else {
		return '';
	}
}

function hny_split_half( $string ) {
	$result  = array();
	$text    = explode( ' ', $string );
	$count   = count( $text );
	$string1 = '';
	$string2 = '';
	if ( $count > 1 ) {
		if ( 0 === $count % 2 ) {
			$start = $count / 2;
			$end   = $count;
			for ( $i = 0; $i < $start; $i ++ ) {
				$string1 .= $text[ $i ] . ' ';
			}
			for ( $j = $start; $j < $end; $j ++ ) {
				$string2 .= $text[ $j ] . ' ';
			}
			$result[] = $string1;
			$result[] = $string2;
		} else {
			$start = round( $count / 2 ) - 1;
			$end   = $count;
			for ( $i = 0; $i < $start; $i ++ ) {
				$string1 .= $text[ $i ] . ' ';
			}
			for ( $j = $start; $j < $end; $j ++ ) {
				$string2 .= $text[ $j ] . ' ';
			}
			$result[] = $string1;
			$result[] = $string2;

		}
	} else {
		$result[] = $string;
	}

	return $result;
}

function hny_get_rig_skiplink( $equipment ) {
	$terms = get_the_terms( $equipment, 'rig_category' );

	if ( $terms ) {
		$term = current( $terms );

		if ( $term instanceof WP_Term ) {
			return trailingslashit( get_term_link( $term, 'rig_category' ) ) . '#' . get_post_field( 'post_name', $equipment );
		}
	}

	return '';
}
