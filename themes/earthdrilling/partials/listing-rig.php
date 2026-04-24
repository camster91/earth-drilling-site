<?php
global $wp_query;
global $post;
$tab_id   = uniqid();
$posts    = $wp_query->posts;
$first    = current( $posts );
$is_first = get_the_ID() === $first->ID;
$classes  = array( 'listing__item' );
$index    = array_search( $post, $posts, true );
wp_enqueue_script( 'sliders' );
ob_start();
hny_get_template_part(
	'partials/heading-group',
	array(
		'heading'    => '<span>' . get_post_meta(
			get_the_ID(),
			'unit_numbers',
			true
		) . '</span>',
		'subheading' => get_the_title(),
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
$content      = ob_get_clean();
$other_fields = array(
	array(
		'label' => 'Benefits',
		'field' => 'benefits',
	),
	array(
		'label' => 'Customized Features',
		'field' => 'customized_features',
	),
);
?>
<div class="<?php echo implode( ' ', $classes ); ?>"
		id="<?php echo get_post_field( 'post_name', get_the_ID() ); ?>">
	<?php
	$photo   = array( hny_get_featured_image( get_the_ID(), 'hny-large' ) );
	$gallery = get_post_meta( get_the_ID(), 'gallery', true ) ?? array();
	$photos  = $gallery ? array_merge(
		$photo,
		array_map(
			function ( $id ) {
				return hny_get_image_url( $id, 'hny-large' );
			},
			$gallery
		)
	) : $photo;
	?>
	<div class="listing__split">
		<div class="grid-container">
			<div class="grid-x grid-padding-x">
				<div class="small-12 tablet-6 tablet-order-<?php echo 0 === $index % 2 ? 1 : 2; ?> cell cell--flex">
					<div class="listing__content" data-context="light">
						<?php echo $content; ?>
					</div>
				</div>
				<div class="small-12 tablet-6 tablet-order-<?php echo 0 === $index % 2 ? 2 : 1; ?> cell cell--flex">
					<div class="listing__photo">
						<div class="listing__slider slick-slider--flex js-rig-photos"
								data-slick-slider>
							<?php foreach ( $photos as $photo_url ) { ?>
								<div class="photo" data-slick-slide>
									<figure class="u-photo-bg"
											style="background-image: url('<?php echo $photo_url; ?>');"></figure>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	ob_start();
	$spec_sheet = get_post_meta( get_the_ID(), 'spec_sheet', true );

	if ( $spec_sheet ) {
		?>
		<div class="spec-button">
			<a href="<?php echo hny_get_protected_file_link( $spec_sheet ); ?>"
			   class="button button--download inline-icon inline-icon--large"
			   target="_blank">
				<span>
					<span>Download</span>
					<span>Spec Sheet</span>
				</span>
				<?php echo hny_get_svg( array( 'icon' => 'pdf-round' ) ); ?>
			</a>
		</div>
		<?php
	}
	$specs_button = ob_get_clean();

	$specs_output = '';
	$rig_specs    = trim(
		hny_get_template_part(
			'partials/rig-specs',
			array(
				'id'     => get_the_ID(),
				'return' => true,
			)
		)
	);

	if ( $rig_specs ) {
		$specs_output .= $rig_specs;
	}

	ob_start();
	foreach ( $other_fields as $field ) {
		$data = array();
		$rows = get_post_meta( get_the_ID(), $field['field'], true );

		if ( $rows ) {
			for ( $i = 0; $i <= $rows; $i ++ ) {
				$bullet = get_post_meta(
					get_the_ID(),
					$field['field'] . '_' . $i . '_bullet',
					true
				);

				if ( $bullet ) {
					$data[] = array(
						'bullet' => $bullet,
					);
				}
			}
		}

		if ( $data ) {
			?>
			<div class="small-12 large-6 cell">
				<?php
				hny_get_template_part(
					'partials/heading-group',
					array(
						'heading' => $field['label'],
						'level'   => 4,
					)
				);
				?>
				<ul class="checklist">
					<?php foreach ( $data as $spec ) { ?>
						<li>
							<?php echo hny_get_svg( array( 'icon' => 'check' ) ); ?>
							<span><?php echo $spec['bullet']; ?></span>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<?php
	}
	$other_specs = trim( ob_get_clean() );

	if ( $other_specs ) {
		$specs_output .= '<div class="grid-x grid-padding-x">' . $other_specs . '</div>';
	}

	ob_start();

	$cc = get_post_meta( get_the_ID(), 'close_crop', true );

	if ( $cc ) {
		$cc = hny_get_image_url( $cc, 'hny-large' );
		?>
		<div class="grid-x grid-padding-x">
			<div class="small-12 cell">
				<figure class="u-photo-bg u-photo-bg--rig-close-crop"
						style="background-image: url('<?php echo $cc; ?>');"></figure>
			</div>
		</div>
		<?php
	}

	$close_crop = trim( ob_get_clean() );

	if ( $close_crop ) {
		$specs_output .= $close_crop;
	}
	?>

	<?php if ( $specs_output ) { ?>
		<div class="listing__utility">
			<div class="grid-container">
				<div class="listing__specs">
					<div class="l-container">
						<?php if ( $specs_button ) { ?>
							<?php echo $specs_button; ?>
						<?php } ?>
						<?php echo $specs_output; ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
