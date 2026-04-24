<?php
$link  = isset( $template_args ) && isset( $template_args['link'] ) && ! empty( $template_args['link'] ) ? $template_args['link'] : null;
$size  = isset( $template_args ) && isset( $template_args['size'] ) && ! empty( $template_args['size'] ) ? $template_args['size'] : null;
$style = isset( $template_args ) && isset( $template_args['style'] ) && ! empty( $template_args['style'] ) ? $template_args['style'] : null;

if ( $link && isset( $link['url'] ) && isset( $link['title'] ) ) {
	$attrs   = array();
	$classes = array( 'button', 'inline-icon' );
	$attrs[] = 'href="' . $link['url'] . '"';
	$attrs[] = 'title="' . $link['title'] . '"';

	if ( $size ) {
		$classes[] = $size;
	}

	if ( isset( $link['target'] ) && ! empty( $link['target'] ) ) {
		$attrs[] = 'target="' . $link['target'] . '"';
		$attrs[] = 'rel="noopener noreferrer"';
	}

	if ( $style ) {
		if ( 'secondary' === $style ) {
			$classes[] = 'secondary';
		}
	}

	$attrs[] = 'class="' . implode( ' ', $classes ) . '"';
	?>
	<a <?php echo implode( ' ', $attrs ); ?>>
		<?php if ( false !== strpos( strtolower( $link['title'] ), 'back' ) ) { ?>
			<?php echo hny_get_svg( array( 'icon' => 'chevron-left' ) ); ?>
			<span><?php echo wptexturize( $link['title'] ); ?></span>
		<?php } else { ?>
			<span><?php echo wptexturize( $link['title'] ); ?></span>
			<?php echo hny_get_svg( array( 'icon' => 'chevron-right' ) ); ?>
		<?php } ?>
	</a>
	<?php
}
