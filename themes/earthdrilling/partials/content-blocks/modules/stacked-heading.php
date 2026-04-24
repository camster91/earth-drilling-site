<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

hny_get_template_part(
	'partials/heading-group',
	array(
		'heading'    => $module->get_prop( 'first_line' ),
		'subheading' => $module->get_prop( 'second_line' ),
		'level'      => 2,
		'stacked'    => true,
	)
);
