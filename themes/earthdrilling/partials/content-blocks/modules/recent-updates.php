<?php
$args = array(
	'posts_per_page' => 3,
	'post_status'    => 'publish',
);

$posts_query = new WP_Query( $args );

?>

<style>
.recent-updates .article__image-wrapper {
	display: block;
	margin-bottom: 1rem;
	width: 100%;
	height: 200px;
	overflow: hidden;
}
.recent-updates .article__image-wrapper img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}
</style>

<div class="recent-updates">
	<div class="grid-x grid-padding-x grid-padding-x--small">
		<?php
		if ( $posts_query->have_posts() ) :
			while ( $posts_query->have_posts() ) : $posts_query->the_post();
				?>
				<div class="small-12 medium-4 cell cell--flex">
					<article class="article" style="margin-bottom: 2rem; display: flex; flex-direction: column; height: 100%;">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="article__image-wrapper">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'hny-medium' );
							} else {
								echo '<img src="' . hny_get_theme_image( 'placeholder.jpg' ) . '" alt="Placeholder" />';
							}
							?>
						</a>
						<h4 class="heading">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h4>
						<div class="article__content" style="flex-grow: 1;">
							<?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
						</div>
						<div style="margin-top: 1rem;">
							<a href="<?php the_permalink(); ?>" class="read-more inline-icon">
								<span>Read More</span>
								<?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?>
							</a>
						</div>
					</article>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
	</div>
</div>
