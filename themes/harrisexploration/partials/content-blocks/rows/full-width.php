<?php
$row = isset( $template_args ) && isset( $template_args['row'] ) && ! empty( $template_args['row'] ) ? $template_args['row'] : null;

if ( ! $row instanceof Theme_Content_Block_Row ) {
	return;
}

$columns = $row->get_columns();

foreach ( $columns as $column ) {
	?>
	<div class="small-12 cell cell--flex">
		<div class="content-block__column">
			<?php $column->render(); ?>
		</div>
	</div>
	<?php
}
?>
