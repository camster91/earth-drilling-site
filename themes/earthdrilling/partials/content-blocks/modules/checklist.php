<?php
$module = isset( $template_args ) && isset( $template_args['module'] ) && ! empty( $template_args['module'] ) ? $template_args['module'] : null;

if ( ! $module instanceof Theme_Content_Block_Module ) {
	return;
}

$items      = $module->get_prop( 'items' );
$two_column = $module->get_prop( 'two_column' );
$icon       = $module->get_prop( 'icon_type' );

if ( $items ) {
	$classes = array( 'checklist' );

	if ( $two_column ) {
		$classes[] = 'checklist--two-column';
	}
	?>

	<ul class="<?php echo implode( ' ', $classes ); ?>">
		<?php
		for ( $i = 0; $i < $items; $i ++ ) {
			$text = $module->get_prop( 'items_' . $i . '_text' );
			?>
			<li>
				<?php echo hny_get_svg( array( 'icon' => $icon ? $icon : 'check' ) ); ?>
				<span><?php echo $text; ?></span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>
