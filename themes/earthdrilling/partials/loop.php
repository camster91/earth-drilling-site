<?php
$type = is_search() ? 'search' : get_post_type();

if ( hny_is_listing() ) {
	echo '<div class="listing listing--' . $type . '">';
	echo '<div class="listing__wrapper">';

	if ( hny_is_blog() ) {
		echo '<div class="l-container">';
		get_template_part( 'partials/listing-utility' );
	} else {
		if ( ! is_tax( 'rig_category' ) && ! is_post_type_archive( 'industry' ) ) {
			echo '<div class="grid-container">';
		}
	}

	if ( is_search() ) {
		get_template_part( 'partials/search-header' );
	}

	echo '<div class="listing__items">';

	if ( hny_is_blog() ) {
		echo '<div class="l-container">';
		echo '<div class="grid-container">';
		echo '<div class="grid-x grid-padding-x grid-padding-x--small">';
	}
}

while ( have_posts() ) {
	the_post();
	/*
	 * Include the post type-specific template for the content. If you want to
	 * use this in a child theme, then include a file called called content-___.php
	 * (where ___ is the post type) and that will be used instead. For post type content layout
	 * create a new partials/content-POSTTYPE.php file. If none is found content.php will be used.
	 *
	 * partials/listing is used on archives and search results, while content is used for
	 * full content post/page views.
	 */

	if ( hny_is_listing() ) {
		get_template_part( 'partials/listing', $type );
	} else {
		get_template_part( 'partials/content', $type );
	}
}

if ( hny_is_listing() ) {
	if ( hny_is_blog() ) {
		echo '</div>';
		echo '</div>';
		echo '</div>';
	} else {
		if ( ! is_tax( 'rig_category' ) && ! is_post_type_archive( 'industry' ) ) {
			echo '</div>';
		}
	}

	ob_start();
	get_template_part( 'partials/pagination' );
	$pagination = ob_get_clean();

	if ( is_paged() || $pagination ) {
		echo '<div class="listing__pagination">';
		echo $pagination;
		echo '</div>';
	}

	echo '</div>';

	if ( is_tax( 'rig_category' ) ) {
		echo '<div class="listing__sub-nav">';
		echo '<div class="l-container">';
		echo '<div class="grid-container grid-container--narrow">';
		echo '<div class="text-center">';
		hny_get_template_part(
			'partials/heading-group',
			array(
				'heading' => 'Drilling Equipment',
				'level'   => 3,
			)
		);
		echo '</div>';

		get_template_part( 'partials/drills-quick-links' );
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	if ( hny_is_blog() ) {
		echo '</div>';
	}

	echo '</div>';
	echo '</div>';
}

if ( Theme()->get_content_blocks()->is_enabled() ) {
	get_template_part( 'partials/content-blocks/loop' );
}
