<?php
$args = array(
	'post_type'      => 'team_member',
	'posts_per_page' => 100,
	'fields'         => 'ids',
);

$members = get_posts( $args );

?>
	<div class="team-members">
		<div class="grid-x grid-padding-x grid-padding-x--large">
			<?php
			foreach ( $members as $index => $member ) {
				$email    = get_post_meta( $member, 'email', true );
				$phone    = get_post_meta( $member, 'phone', true );
				$contacts = array_filter(
					array(
						array(
							'label' => 'Phone',
							'slug'  => 'phone',
							'data'  => $phone,
							'link'  => 'tel:' . hny_to_tel( $phone ),
						),
						array(
							'label' => 'Email',
							'slug'  => 'paper-airplane',
							'data'  => $email,
							'link'  => 'mailto:' . $email,
						),
					),
					function ( $service ) {
						return $service['data'];
					}
				);
				?>
				<div class="small-12 medium-6 cell">
					<?php
					hny_get_template_part(
						'partials/heading-group',
						array(
							'heading' => get_the_title( $member ) . '<small>' . get_post_meta(
								$member,
								'job_title',
								true
							) . '</small>',
							'level'   => 4,
							'alt'     => true,
						)
					);
					?>
					<?php
					if ( $contacts ) {
						?>
						<ul class="contact-list">
							<?php foreach ( $contacts as $contact ) { ?>
								<li>
									<a href="<?php echo $contact['link']; ?>"
											class="inline-icon">
										<?php echo hny_get_svg( array( 'icon' => $contact['slug'] ) ); ?>
										<span><?php echo $contact['data']; ?></span></a>
								</li>
							<?php } ?>
						</ul>
						<?php
					}
					?>
					<?php
					echo apply_filters(
						'the_content',
						get_post_field( 'post_content', $member )
					);
					?>
				</div>
			<?php } ?>
		</div>
	</div>
<?php

