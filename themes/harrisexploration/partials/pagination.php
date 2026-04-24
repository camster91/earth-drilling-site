<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php
if ( function_exists( 'hny_pagination' ) ) {
	hny_pagination();
} elseif ( is_paged() ) {
	?>
	<nav id="post-nav">
		<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'hny' ) ); ?></div>
		<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'hny' ) ); ?></div>
	</nav>
<?php } ?>
