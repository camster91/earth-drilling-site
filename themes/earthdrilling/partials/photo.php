<?php
$photo = isset( $template_args ) && isset( $template_args['photo'] ) && ! empty( $template_args['photo'] ) ? $template_args['photo'] : null;

if ( ! $photo ) {
	return;
}

$aspect_ratio = isset( $template_args ) && isset( $template_args['aspect_ratio'] ) && ! empty( $template_args['aspect_ratio'] ) ? $template_args['aspect_ratio'] : null;
$extend       = isset( $template_args ) && isset( $template_args['extend'] ) && ! empty( $template_args['extend'] ) ? $template_args['extend'] : false;
$styles       = array();
$padding      = '';

if ( $photo && 'natural' === $aspect_ratio ) {
	$media = wp_get_attachment_image_src( $photo, 'hny-medium' );

	if ( isset( $media[1] ) && isset( $media[2] ) ) {
		$width   = $media[1];
		$height  = $media[2];
		$padding = ( $height / $width ) * 100 . '%';
	}
}

if ( $padding ) {
	$styles[] = 'padding-bottom: ' . $padding . ';';
}

$classes   = array( 'u-photo-bg' );
$mime_type = get_post_mime_type( $photo );

$photo_class = array( 'photo' );
$caption     = wp_get_attachment_caption( $photo );

if ( $caption ) {
	$photo_class[] = 'photo--has-caption';
} else {
	$photo_class[] = 'photo--no-caption';
}

if ( $extend && 'default' !== $extend ) {
	$photo_class[] = 'photo--extended';
	$classes[]     = 'u-photo-bg--extend-' . $extend;
	$url           = hny_get_image_url( $photo, 'hny-xlarge' );
} else {
	$url = hny_get_image_url( $photo, 'hny-medium' );
}

$styles[] = 'background-image: url(\'' . $url . '\');';
?>
<div class="<?php echo implode( ' ', $photo_class ); ?>">
	<figure
			class="<?php echo implode( ' ', $classes ); ?>"
			style="<?php echo implode( ' ', $styles ); ?>"></figure>
	<?php if ( $caption ) { ?>
		<div class="photo__caption">
			<p class="lead">
				<?php echo $caption; ?>
			</p>
		</div>
	<?php } ?>
</div>
