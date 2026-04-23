<?php get_header();

if ( have_posts() ) {
	get_template_part( 'partials/loop' );
} else {
	get_template_part( 'partials/content', 'none' );
}

get_footer();
