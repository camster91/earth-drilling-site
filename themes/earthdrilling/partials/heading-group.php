<?php
$args       = isset( $template_args ) && ! empty( $template_args ) ? $template_args : array();
$level      = isset( $args['level'] ) ? absint( $args['level'] ) : 2;
$heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$subheading = isset( $args['subheading'] ) ? $args['subheading'] : '';
$centered   = isset( $args['centered'] ) ? $args['centered'] : false;
$alt        = isset( $args['alt'] ) ? $args['alt'] : false;
$animate    = isset( $args['animate'] ) ? $args['animate'] : true;
$stacked    = isset( $args['stacked'] ) ? $args['stacked'] : false;
$drill      = isset( $args['drill'] ) ? $args['drill'] : false;

if ( $heading || $subheading ) {
	$classes = array( 'heading' );

	if ( $alt ) {
		$classes[] = 'heading--alt';
	}

	if ( $centered ) {
		$classes[] = 'heading--centered';
	}

	if ( $stacked ) {
		$classes[] = 'heading--stacked';
	} else {
		if ( ( ( $heading && $subheading ) || $drill ) && ! $alt && ! $centered ) {
			$classes[] = 'heading--drill';
			$classes[] = 'js-in-view';
		}
	}
	?>
	<h<?php echo $level; ?> class="<?php echo implode( ' ', $classes ); ?>">
		<?php if ( $heading ) { ?>
			<span><?php echo wptexturize( trim( $heading ) ); ?></span>
		<?php } ?>
		<?php if ( $subheading ) { ?>
			<span><?php echo wptexturize( trim( $subheading ) ); ?></span>
		<?php } ?>
	</h<?php echo $level; ?>>
	<?php
}
