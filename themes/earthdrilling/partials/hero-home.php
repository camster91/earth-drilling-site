<?php
$hero = isset( $template_args['instance'] ) && ! empty( $template_args['instance'] ) ? $template_args['instance'] : null;

if ( ! $hero instanceof Theme_Hero ) {
	return;
}

wp_enqueue_script( 'sliders' );
$items = $hero->get_items();
?>

<div class="hero hero--<?php echo is_front_page() ? 'home' : 'landing'; ?>">
	<div class="hero__backgrounds js-hero-backgrounds" data-slick-slider>
		<?php
		foreach ( $items as $item ) {
			$bg_url = hny_get_image_url( $item['photo'], 'hny-wide' );
			?>
			<figure class="hero__bg"
					style="background-image: url('<?php echo $bg_url; ?>');"></figure>
		<?php } ?>
	</div>
	<div class="hero__content">
		<div class="hero__content-wrapper">
			<div class="grid-container">
				<div class="hero__captions js-hero-captions slick-slider--flex" data-slick-slider>
					<?php
					foreach ( $items as $item ) {
						?>
						<div data-slick-slide>
							<div class="hero__inner">
								<div>
									<div data-animation="enter-right">
										<?php
										hny_get_template_part(
											'partials/heading-group',
											array(
												'heading' => is_tax() ? implode(
													' ',
													array_map(
														function ( $part ) {
															return '<strong>' . trim( $part ) . '</strong>';
														},
														hny_split_half( $item['heading'] )
													)
												) : $item['heading'],
												'subheading' => $item['subheading'] ?? '',
												'level'   => 1,
												'stacked' => is_page() || is_post_type_archive(),
												'alt'     => true,
											)
										);
										?>
									</div>
									<div data-animation="enter-right" data-delay="0.5s">
										<?php
										hny_get_template_part(
											'partials/button',
											array(
												'link' => $item['button'] ?? null,
												'size' => 'large',
											)
										);
										?>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	$item = current( $items );
	if ( isset( $item['text'] ) && ! empty( $item['text'] ) ) {
		?>
		<div class="hero__bottom" data-context="dark">
			<div class="hero__decoration"></div>
			<div class="hero__copy">
				<div class="grid-container grid-container--narrow">
					<?php
					hny_get_template_part(
						'partials/heading-group',
						array(
							'heading' => $item['text'],
							'level'   => 4,
							'drill'   => true,
						)
					);
					?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

