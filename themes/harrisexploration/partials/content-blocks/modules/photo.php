<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$photo        = $module->get_prop( 'photo' );
$aspect_ratio = $module->get_prop( 'aspect_ratio' );
$extend       = $module->get_prop( 'extend' );

hny_get_template_part(
	'partials/photo',
	array(
		'photo'        => $photo,
		'aspect_ratio' => $aspect_ratio,
		'extend'       => $extend,
	)
);
