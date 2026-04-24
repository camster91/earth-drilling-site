<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$link_to_equipment = $module->get_prop( 'link_to_equipment' );

if ( $link_to_equipment ) {
	$equipment = $module->get_prop( 'equipment' );

	if ( $equipment ) {
		$link = array(
			'url'   => hny_get_rig_skiplink( $module->get_prop( 'equipment' ) ),
			'title' => 'Equipment',
		);
	}
} else {
	$link = $module->get_prop( 'link' );
}

if ( $link ) {
	hny_get_template_part(
		'partials/button',
		array(
			'link' => $link,
			'size' => $module->get_prop( 'size' ),
		)
	);
}
