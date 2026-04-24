<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$content = $module->get_prop( 'content' );

if ( $content ) {
	echo apply_filters( 'the_content', $content );
}
