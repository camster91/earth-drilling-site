<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$gallery     = $module->get_prop( 'gallery' );
$button_text = $module->get_prop( 'button_text' );
$gallery_id  = uniqid();
$urls        = array_map(
	function ( $image ) {
		return hny_get_image_url( $image, 'hny-xlarge' );
	},
	$gallery
);

$first = array_shift( $urls );
?>

<a href="<?php echo $first; ?>" class="button small" data-photo>
	<span><?php echo $button_text ? $button_text : 'View Photos'; ?></span>
	<?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?>
</a>

<?php

foreach ( $urls as $url ) {
	?>
	<a href="<?php echo $url; ?>" data-photo class="u-screen-reader"></a>
	<?php
}
?>

<div id="<?php echo $gallery_id; ?>" class="reveal reveal--photo full js-gallery-modal" data-reveal>
	<?php get_template_part( 'partials/reveal-close' ); ?>
	<div class="reveal__content">
		<div data-photo></div>
		<button class="reveal__arrow reveal__arrow--prev" data-prev
				aria-label="Previous"><?php echo hny_get_svg( array( 'icon' => 'chevron-left' ) ); ?></button>
		<button class="reveal__arrow reveal__arrow--next" data-next
				aria-label="Next"><?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?></button>
	</div>
</div>
