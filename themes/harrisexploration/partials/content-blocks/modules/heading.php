<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

hny_get_template_part(
	'partials/heading-group',
	array(
		'heading' => $module->get_prop( 'heading' ),
		'level'   => false === boolval( $module->get_prop( 'drill' ) ) ? 5 : 4,
		'drill'   => false === boolval( $module->get_prop( 'drill' ) ) ? false : true,
	)
);
