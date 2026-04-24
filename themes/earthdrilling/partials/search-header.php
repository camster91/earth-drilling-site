<div class="search-header">
	<?php
	global $wp_query;
	$count = is_404() ? 0 : $wp_query->found_posts;
	if ( $count ) {
		$paged = $wp_query->query_vars['paged'] ? $wp_query->query_vars['paged'] : 1;
		$from  = ( $wp_query->query_vars['posts_per_page'] * $paged ) - ( $wp_query->query_vars['posts_per_page'] - 1 );
		if ( 1 === $count ) {
			$from_to = 1;
		} else {
			$from = ( $wp_query->query_vars['posts_per_page'] * $paged ) - ( $wp_query->query_vars['posts_per_page'] - 1 );
			if ( ( $wp_query->query_vars['posts_per_page'] * $paged ) <= ( $wp_query->found_posts ) ) {
				$to = ( $wp_query->query_vars['posts_per_page'] * $paged );
			} else {
				$to = $wp_query->found_posts;
			}
			if ( $from == $to ) {
				$from_to = $from;
			} else {
				$from_to = $from . ' - ' . $to;
			}
		}
		?>
		<div class="search-header__count">
			<p>
				<small><?php echo 'Showing ' . _n( 'result', 'results', $count, 'hny' ) . ' ' . $from_to . ' of ' . $count . ' total. '; ?></small>
			</p>
		</div>
	<?php } ?>
</div>
