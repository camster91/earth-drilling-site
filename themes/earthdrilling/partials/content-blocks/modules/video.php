<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$video = $module->get_prop( 'video' );

if ( $video ) { ?>
	<div class="u-box-shadow-large">
		<?php echo wp_oembed_get( $video ); ?>
	</div>
	<?php
}
