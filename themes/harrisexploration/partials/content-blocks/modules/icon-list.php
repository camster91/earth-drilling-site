<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$items = $module->get_prop( 'items' );
$icons = array();

if ( $items ) {
	$fields = array(
		'icon',
		'heading',
	);

	for ( $i = 0; $i < $items; $i ++ ) {
		$data = array();

		foreach ( $fields as $field ) {
			$data[ $field ] = $module->get_prop( 'items_' . $i . '_' . $field );
		}

		if ( ! empty( $data['icon'] ) && ! empty( $data['heading'] ) ) {
			$icons[] = $data;
		}
	}
}

if ( ! $icons ) {
	return;
}

$classes = array( 'icon-list' );
?>

<ul class="<?php echo implode( ' ', $classes ); ?>">
	<?php
	foreach ( $icons as $index => $icon ) {
		?>
		<li class="icon-list__item">
			<div class="icon-list__icon">
				<?php echo hny_get_svg( array( 'icon' => $icon['icon'] ) ); ?>
			</div>
			<span class="icon-list__title">
				<?php echo wptexturize( $icon['heading'] ); ?>
			</span>
		</li>
	<?php } ?>
</ul>
