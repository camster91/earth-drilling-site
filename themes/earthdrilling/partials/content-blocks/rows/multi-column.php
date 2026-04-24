<?php
$row = isset( $template_args ) && isset( $template_args['row'] ) && ! empty( $template_args['row'] ) ? $template_args['row'] : null;

if ( ! $row instanceof Theme_Content_Block_Row ) {
	return;
}

$columns            = $row->get_columns();
$first_column_width = $row->get_first_column_width();
$layout             = $row->get_layout();

foreach ( $columns as $index => $column ) {
	$cell_classes   = array( 'cell' );
	$column_classes = array( 'content-block__column' );

	if ( 'two-column' === $layout ) {
		if ( 1 === $index % 2 ) {
			$column_classes[] = 'content-block__column--even';
		} else {
			$column_classes[] = 'content-block__column--odd';
		}

		if ( 50 !== $first_column_width ) {
			if ( 25 === $first_column_width ) {
				if ( 0 === $index % 2 ) {
					$cell_classes[] = 'small-12 medium-3';
				} else {
					$cell_classes[] = 'small-12 medium-9';
				}
			} elseif ( 33 === $first_column_width ) {
				if ( 0 === $index % 2 ) {
					$cell_classes[] = 'small-12 medium-4';
				} else {
					$cell_classes[] = 'small-12 medium-8';
				}
			} elseif ( 66 === $first_column_width ) {
				if ( 0 === $index % 2 ) {
					$cell_classes[] = 'small-12 medium-8';
				} else {
					$cell_classes[] = 'small-12 medium-4';
				}
			} elseif ( 75 === $first_column_width ) {
				if ( 0 === $index % 2 ) {
					$cell_classes[] = 'small-12 medium-9';
				} else {
					$cell_classes[] = 'small-12 medium-3';
				}
			}
		}
	}
	?>
	<div
		class="<?php echo implode( ' ', array_merge( $column->get_classes(), $cell_classes ) ); ?>">
		<div class="<?php echo implode( ' ', $column_classes ); ?>">
			<?php $column->render(); ?>
		</div>
	</div>
	<?php
}
?>
