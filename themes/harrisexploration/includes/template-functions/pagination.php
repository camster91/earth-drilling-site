<?php
/**
 * Generate pagination links, adopted for Foundation.
 */
function hny_pagination() {
	global $wp_query;
	$big = 999999999;

	$paginate_links = paginate_links(
		array(
			'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $wp_query->max_num_pages,
			'mid_size'  => 5,
			'prev_next' => true,
			'prev_text' => 'Prev',
			'next_text' => 'Next',
			'type'      => 'list',
		)
	);

	if ( $paginate_links ) {
		$preg_find = [
			'/\s*page-numbers\s*/', // Captures string 'page-numbers' and any whitespace before and after
			"/\s*class=''/", // Captures any empty class attributes
			'/<li><a class="prev" href="(\S+)">/', // '(\S+)' Captures href value for backreference
			'/<li><a class="next" href="(\S+)">/', // '(\S+)' Captures href value for backreference
			"/<li><span aria-current='page' class='current'>(\d+)<\/span><\/li>/", // '(\d+)' Captures page number for backreference
			"/<li><a href='(\S+)'>(\d+)<\/a><\/li>/", // '(\S+)' Captures href value for backreference, (\d+)' Captures page number for backreference
		];

		$preg_replace = [
			'',
			'',
			'<li class="pagination-previous"><a href="$1" aria-label="Previous page">', // '$1' Outputs backreference href value
			'<li class="pagination-next"><a href="$1" aria-label="Next page">', // '$1' Outputs backreference href value
			'<li class="current" aria-current="page"><span>$1</span></li>', // '$1' Outputs backreference page number
			'<li><a href="$1" aria-label="Page $2">$2</a></li>', // '$1' Ouputs backreference href, '$2' outputs backreference page number
		];

		$str_find = [
			'<li><span class="dots">&hellip;</span></li>',
		];

		$str_replace = [
			'<li class="ellipsis" aria-hidden="true">&hellip;</li>',
		];

		$paginate_links = preg_replace( $preg_find, $preg_replace, $paginate_links );
		$paginate_links = str_replace( $str_find, $str_replace, $paginate_links );

		echo '<nav class="pagination" aria-label="Pagination"><div class="pagination__wrapper">' . $paginate_links . '</div></nav>';
	}
}
