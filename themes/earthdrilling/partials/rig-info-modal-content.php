<?php
$template_args = $template_args ?? array();
$rig           = $template_args['rig'] ?? 0;

if ( ! $rig ) {
	return;
}

$photo = hny_get_featured_image( $rig, 'hny-large' );
?>

<div class="reveal__content" data-context="light">
	<div class="rig-modal">
		<div class="grid-container">
			<div class="rig-modal__heading">
				<?php
				$terms      = get_the_terms( $rig, 'rig_category' );
				$term_names = array_map(
					function ( $term ) {
						return $term->name;
					},
					$terms
				);
				hny_get_template_part(
					'partials/heading-group',
					array(
						'heading'    => '<span>' . implode( ', ', $term_names ) . '</span>',
						'subheading' => get_the_title( $rig ),
						'level'      => 2,
						'drill'      => true,
					)
				);
				?>
				<figure class="u-photo-bg"
						style="background-image: url('<?php echo $photo; ?>');"></figure>
			</div>
			<?php
			hny_get_template_part(
				'partials/rig-specs',
				array( 'id' => $rig )
			);
			?>
		</div>
	</div>
</div>
<?php get_template_part( 'partials/reveal-close' ); ?>
