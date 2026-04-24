<?php
$row = isset( $template_args ) && isset( $template_args['row'] ) && ! empty( $template_args['row'] ) ? $template_args['row'] : null;

if ( ! $row instanceof Theme_Content_Block_Row ) {
	return;
}

$block_theme = $row->get_block_theme();
$heading     = $row->get_heading();

if ( ! $heading ) {
	$columns = $row->get_columns();

	foreach ( $columns as $column ) {
		$modules = $column->get_modules();

		if ( $column->get_heading() ) {
			$heading = $column->get_heading();
			break;
		}

		if ( $modules ) {
			foreach ( $modules as $module ) {
				if ( 'heading' === $module->get_name() ) {
					$heading = $module->get_prop( 'heading' );
					break;
				}
			}
		}

		if ( $heading ) {
			break;
		}
	}
}

$decoration    = $row->get_decoration();
$bottom_shapes = $row->get_bottom_shapes();
?>

<div class="<?php echo implode( ' ', $row->get_row_classes() ); ?>"
		data-context="<?php echo $row->get_block_theme(); ?>">
	<?php
	if ( $decoration ) {
		?>
		<div class="content-block__decoration"></div>
	<?php } ?>
	<div class="content-block__container">
		<?php
		$container_classes = array( 'l-container' );

		if ( $row->get_setting( 'remove_spacing' ) ) {
			$container_classes[] = 'l-container--no-padding';
		}
		?>
		<div class="<?php echo implode( ' ', $container_classes ); ?>">
			<div class="<?php echo implode( ' ', $row->get_container_classes() ); ?>">
				<div class="content-block__wrapper">
					<div class="content-block__content">
						<?php if ( $row->get_heading() || $row->get_subheading() ) { ?>
							<div class="<?php echo implode( ' ', $row->get_header_classes() ); ?>">
								<?php
								$large = false;
								$size  = $row->get_setting( 'block_heading_size' );
								if ( $size && 'large' === $size ) {
									$large = true;
								}
								$stacked = $row->get_setting( 'block_heading_stake' );
								?>
								<?php
								hny_get_template_part(
									'partials/heading-group',
									array(
										'heading'    => $row->get_heading(),
										'subheading' => $row->get_subheading(),
										'level'      => $large || $stacked ? '2' : '3',
										'centered'   => $row->get_setting( 'block_heading_centered' ),
										'stacked'    => $stacked,
									)
								);
								?>
							</div>
						<?php } ?>
						<?php if ( $row->get_columns() ) { ?>
							<div
									class="content-block__layout content-block__layout--<?php echo $row->get_layout(); ?>">
								<div class="
								<?php
								echo implode(
									' ',
									$row->get_grid_classes()
								);
								?>
									">
									<?php
									$template = 'full-width' !== $row->get_layout() ? 'multi-column' : 'full-width';
									hny_get_template_part(
										'partials/content-blocks/rows/' . $template,
										array( 'row' => $row )
									);
									?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	if ( $bottom_shapes ) {
		?>
		<div class="content-block__bottom-shapes"></div>
	<?php } ?>
</div>
