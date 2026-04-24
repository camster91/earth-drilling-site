<?php
ob_start();
$template_args = $template_args ?? array();
$id            = $template_args['id'] ?? 0;
$spec_fields   = array(
	array(
		'label' => 'Rig Dimensions',
		'field' => 'dimensions',
	),
	array(
		'label' => 'Specifications',
		'field' => 'specifications',
	),
	array(
		'label' => 'Trailer',
		'field' => 'trailer',
	),
);
foreach ( $spec_fields as $field ) {
	$data = array();
	$rows = get_post_meta( $id, $field['field'], true );

	if ( $rows ) {
		for ( $i = 0; $i <= $rows; $i ++ ) {
			$label   = get_post_meta(
				$id,
				$field['field'] . '_' . $i . '_label',
				true
			);
			$value_1 = get_post_meta(
				$id,
				$field['field'] . '_' . $i . '_value_1',
				true
			);
			$value_2 = get_post_meta(
				$id,
				$field['field'] . '_' . $i . '_value_2',
				true
			);

			if ( $label ) {
				$data[] = array(
					'label'   => $label,
					'value_1' => $value_1,
					'value_2' => $value_2,
				);
			}
		}
	}

	if ( $data ) {
		?>
		<div class="small-12 large-6 cell">
			<h4><?php echo $field['label']; ?></h4>
			<ul class="spec-table">
				<?php foreach ( $data as $spec ) { ?>
					<li>
						<strong class="spec-table__label">
							<?php echo $spec['label']; ?>:
						</strong>
						<span class="spec-table__value">
												<?php echo $spec['value_1']; ?>
											</span>
						<span class="spec-table__value">
												<?php echo $spec['value_2']; ?>
											</span>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
	<?php
}
$output = trim( ob_get_clean() );

if ( ! $output ) {
	return;
}
?>

<div class="grid-x grid-padding-x">
	<?php echo $output; ?>
</div>
