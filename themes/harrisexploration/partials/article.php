<?php
$id             = $template_args['id'] ?? get_the_ID();
$sidebar        = $template_args['sidebar'] ?? false;
$data           = new Hny_Post( $id );
$featured_image = $data->get_featured_image() ? get_the_post_thumbnail_url(
	$data->get_id(),
	'hny-small'
) : hny_get_theme_image( 'placeholder.jpg' );
$is_logo        = get_post_meta( $data->get_id(), 'is_logo', true );
?>

<article class="article">
	<a href="<?php echo $data->get_permalink(); ?>"
			title="<?php echo $data->get_title(); ?>">
		<figure class="article__image article__image--<?php echo $is_logo ? 'is-logo' : 'is-photo'; ?>"
				style="background-image: url('<?php echo $featured_image; ?>');"></figure>
		<div class="article__content">
			<?php
			hny_get_template_part(
				'partials/heading-group',
				array(
					'heading' => $data->get_title(),
					'level'   => $sidebar ? 6 : 5,
				)
			);
			?>
			<div class="article__meta">
				<span><?php //echo $data->get_date_created()->format( get_option( 'date_format' ) ); ?></span>
			</div>
			<?php
			if ( ! $sidebar ) {
				echo is_single( $id ) ? apply_filters(
					'the_content',
					$data->get_content()
				) : $data->get_custom_excerpt( 40, 'Read More', true );
			}
			?>
		</div>
	</a>
</article>
