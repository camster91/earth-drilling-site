<?php

if ( ! is_tax( 'rig_category' ) ) {
	return;
}

global $wp_query;

$term = get_queried_object();
$rigs = $wp_query->posts;
?>

<div class="secondary-nav js-sticky" data-context="dark">
	<div class="grid-container">
		<div class="secondary-nav__inner">
			<div class="secondary-nav__heading">
				<?php
				$icon = get_term_meta( $term->term_id, 'icon', true );
				hny_get_template_part(
					'partials/heading-group',
					array(
						'heading' => implode(
							' ',
							array_map(
								function ( $part ) {
									return '<span>' . $part . '</span>';
								},
								explode( ' ', $term->name )
							)
						),
						'level'   => 1,
					)
				);
				echo hny_get_svg( array( 'icon' => $icon ) );
				?>
			</div>
			<ul class="secondary-nav__items">
				<?php
				foreach ( $rigs as $index => $rig ) {
					$classes = array( 'js-waypoint-link' );

					if ( 0 === $index ) {
						$classes[] = 'is-active';
					}
					?>
					<li class="secondary-nav__item">
						<button type="button" class="<?php echo implode( ' ', $classes ); ?>" data-scroll-target="<?php echo get_post_field( 'post_name', $rig->ID ); ?>">
							<?php
							echo implode(
								' ',
								array_map(
									function ( $part ) {
										return '<span>' . $part . '</span>';
									},
									hny_split_half( get_the_title( $rig->ID ) )
								)
							);
							?>
						</button>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
