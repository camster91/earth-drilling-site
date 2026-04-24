<div class="l-container">
	<div class="grid-container">
		<div class="grid-x grid-padding-x">
			<div class="small-12 tablet-7 large-8 cell">
				<?php
				$data           = new Hny_Post( get_the_ID() );
				$featured_image = $data->get_featured_image() ? get_the_post_thumbnail_url(
					$data->get_id(),
					'hny-large'
				) : hny_get_theme_image( 'placeholder.jpg' );
				$is_logo        = get_post_meta( $data->get_id(), 'is_logo', true );
				$src            = wp_get_attachment_image_src(
					$data->get_featured_image(),
					'hny-large'
				);
				$url            = $src[0];
				$width          = $src[1];
				$height         = $src[2];
				$vertical       = $height > $width;
				?>

				<article class="article">
					<div class="article__content">
						<?php
						hny_get_template_part(
							'partials/heading-group',
							array(
								'heading'    => '<span>Press Releases</span>',
								'subheading' => $data->get_title(),
								'level'      => 2,
							)
						);
						?>
						<div class="article__meta">
							<span><?php // echo $data->get_date_created()->format( get_option( 'date_format' ) ); ?></span>
						</div>
						<figure class="article__image article__image--<?php echo $vertical ? 'is-vertical' : 'is-horizontal'; ?>"
								style="background-image: url('<?php echo $featured_image; ?>');"></figure>
						<?php
						echo apply_filters( 'the_content', $data->get_content() );
						?>
						<div class="article__utility">
							<small>
								<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"
										class="inline-icon">
									<?php echo hny_get_svg( array( 'icon' => 'chevron-left' ) ); ?>
									<span>Return to Press Releases</span>
								</a>
							</small>
						</div>
					</div>
				</article>
			</div>
			<div class="small-12 tablet-5 large-4 cell">
				<?php
				$args = array(
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'fields'         => 'ids',
				);

				$others = get_posts( $args );
				?>
				<div class="sidebar">
					<div class="sidebar__wrapper">
						<?php
						hny_get_template_part(
							'partials/heading-group',
							array(
								'heading' => 'More Press Releases',
								'level'   => 5,
							)
						);
						?>
						<div class="sidebar__posts">
							<?php foreach ( $others as $other ) { ?>
								<?php
								echo hny_get_template_part(
									'partials/article',
									array(
										'id'      => $other,
										'sidebar' => true,
									)
								);
								?>
							<?php } ?>
						</div>
					</div>
					<div class="sidebar__bottom-shapes"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="<?php echo $gallery_id; ?>" class="reveal reveal--photo full js-gallery-modal"
		data-reveal>
	<?php get_template_part( 'partials/reveal-close' ); ?>
	<div class="reveal__content">
		<div data-photo></div>
		<button class="reveal__arrow reveal__arrow--prev" data-prev
				aria-label="Previous"><?php echo hny_get_svg( array( 'icon' => 'chevron-left' ) ); ?></button>
		<button class="reveal__arrow reveal__arrow--next" data-next
				aria-label="Next"><?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?></button>
	</div>
</div>
