<?php
$logos = isset( $template_args['logos'] ) && ! empty( $template_args['logos'] ) ? $template_args['logos'] : array();
$items = hny_get_associations();

if ( ! $items ) {
	return;
}

if ( $logos ) {
	$items = array_filter(
		$items,
		function ( $item ) use ( $logos ) {
			return in_array( $item['company'], $logos, true );
		}
	);
}
?>

<div class="associations">
	<div class="logo-list">
		<div class="logo-list__wrapper">
			<?php
			foreach ( $items as $item ) {
				?>
				<div class="logo-list__item">
					<img src="<?php echo hny_get_image_url( $item['logo'], 'hny-small' ); ?>"
						alt="<?php echo $item['company']; ?>" />
				</div>
			<?php } ?>
		</div>
	</div>
</div>
