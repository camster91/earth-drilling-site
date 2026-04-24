<div class="listing__item">
	<article class="article">
		<header class="article__header">
			<p class="article__title"><a href="<?php the_permalink(); ?>" title="<?php echo 'Permalink to ' . the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></p>
			<?php //get_template_part( 'partials/meta', get_post_type() ); ?>
		</header>
		<div class="article__content article__content--summary">
			<?php the_excerpt(); ?>
		</div>
	</article>
</div>
