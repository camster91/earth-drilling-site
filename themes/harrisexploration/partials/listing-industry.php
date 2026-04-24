<?php
global $wp_query;
global $post;
$tab_id   = uniqid();
$posts    = $wp_query->posts;
$first    = current( $posts );
$is_first = get_the_ID() === $first->ID;
$classes  = array( 'listing__item' );
$index    = array_search( $post, $posts, true );

$subheading = get_post_meta( get_the_ID(), 'subheading', true );
$services   = get_post_meta( get_the_ID(), 'services', true );
$equipment  = get_post_meta( get_the_ID(), 'equipment', true );

ob_start();
hny_get_template_part(
	'partials/heading-group',
	array(
		'heading'    => get_the_title(),
		'subheading' => $subheading ? '<span>' . get_post_meta(
			get_the_ID(),
			'subheading',
			true
		) . '</span>' : '',
		'level'      => 2,
		'drill'      => true,
	)
);
echo apply_filters(
	'the_content',
	get_post_field( 'post_content', get_the_ID() )
);
?>
<?php
if ( $equipment && 'rig' === get_post_type( $equipment ) ) {
	$link = hny_get_rig_skiplink( $equipment );

	if ( $link ) {
		?>
		<?php
		hny_get_template_part(
			'partials/button',
			array(
				'link' => array(
					'url'   => $link,
					'title' => 'Equipment',
				),
			)
		);
		?>
		<?php
	}
}
?>
<?php
$content = ob_get_clean();
?>
<div class="<?php echo implode( ' ', $classes ); ?>"
		id="<?php echo get_post_field( 'post_name', get_the_ID() ); ?>">
	<?php
	$photo = hny_get_featured_image( get_the_ID(), 'hny-xlarge' );
	?>
	<div class="listing__split">
		<div class="grid-container">
			<div class="grid-x grid-padding-x">
				<div class="small-12 tablet-6 tablet-order-2 cell cell--flex">
					<div class="listing__content" data-context="light">
						<?php echo $content; ?>
						<?php if ( $services ) { ?>
							<div class="listing__supplementary">
								<?php
								hny_get_template_part(
									'partials/heading-group',
									array(
										'heading' => get_the_title() . ' Drilling Services',
										'level'   => 5,
									)
								);
								?>
								<div class="wysiwyg">
									<ul>
										<?php
										for ( $i = 0; $i <= $services; $i ++ ) {
											$service = get_post_meta(
												get_the_ID(),
												'services_' . $i . '_service',
												true
											);

											if ( $service ) {
												?>
												<li><?php echo $service; ?></li>
												<?php
											}
										}
										?>
									</ul>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="small-12 tablet-6 tablet-order-1 cell cell--flex">
					<div class="listing__photo">
						<div class="photo">
							<figure class="u-photo-bg u-photo-bg--extend-left"
									style="background-image: url('<?php echo $photo; ?>');"></figure>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
