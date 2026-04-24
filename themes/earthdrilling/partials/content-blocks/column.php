<?php
$column = isset( $template_args ) && isset( $template_args['column'] ) && ! empty( $template_args['column'] ) ? $template_args['column'] : null;

if ( ! $column instanceof Theme_Content_Block_Column ) {
	return;
}

$modules    = $column->get_modules();
$heading    = $column->get_heading();
$subheading = $column->get_subheading();
?>

<?php if ( $heading || $subheading ) { ?>
	<div class="content-block__intro">
		<?php
		hny_get_template_part(
			'partials/heading-group',
			array(
				'heading'    => $heading,
				'subheading' => $subheading,
				'drill'      => true,
				'level'      => $column->get_heading_size() && 'large' === $column->get_heading_size() ? 2 : 3,
			)
		);
		?>
	</div>
<?php } ?>

<?php if ( $modules ) { ?>
	<div class="content-block__modules">
		<?php
		foreach ( $modules as $module ) {
			?>
			<div
					class="<?php echo implode( ' ', $module->get_classes() ); ?>">
				<?php $module->render(); ?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
