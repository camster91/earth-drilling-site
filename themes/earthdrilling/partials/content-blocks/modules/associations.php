<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$select = $module->get_prop( 'select_logos' );

hny_get_template_part( 'partials/associations', array( 'logos' => $select ? $module->get_prop( 'logos' ) : array() ) );
