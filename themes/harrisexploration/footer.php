</div>
<?php
if ( hny_has_sidebar() ) {
	get_sidebar();
}

$addresses = Hny_Site_Settings::get_addresses();
?>
</main>

<footer class="l-footer" data-context="dark">
	<div class="site-footer">
		<div class="site-footer__contact">
			<div class="site-footer__background"></div>
			<div class="site-footer__content">
				<div class="site-footer__columns">
					<div class="grid-container site-footer__card">
						<div class="grid-x grid-padding-x">
							<div class="small-12 large-10 xlarge-9 cell">
								<div class="grid-x grid-padding-x grid-padding-x--small">
									<div class="small-12 large-4 large-shrink cell">
										<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
												title="<?php bloginfo( 'name' ); ?>" class="site-footer__logo">
											<div class="u-svg-container u-svg-container--footer-logo">
												<img src="<?php echo hny_get_theme_image( 'logo-foot.png' ); ?>"
														alt="Earth Drilling" />
											</div>
											<span class="u-screen-reader"><?php bloginfo( 'name' ); ?> - Return to home page</span>
										</a>
									</div>
									<div class="small-12 large-auto cell">
										<p class="site-footer__intro">Contact the Harris Exploration Office nearest to you</p>
										<div class="grid-x grid-padding-x grid-padding-x--small">
											<?php
											foreach ( $addresses as $address ) {
												$phone = $address['phone'];
												?>
												<div class="small-6 large-auto large-shrink cell">
													<?php
													hny_get_template_part(
														'partials/heading-group',
														array(
															'heading' => $address['city'],
															'level'   => 5,
														)
													);
													?>
													<address>
														<?php
														echo ( $address['address'] ? $address['address'] . '<br>' : '' ) . $address['city'] . ', ' . $address['province_state'] . ' ' . $address['zip_postal_code'];
														?>
														<?php
														if ( $phone ) {
															?>
															<br />
															<a href="tel:<?php echo hny_to_tel( $phone ); ?>"
																	class="inline-icon">
																<?php echo hny_get_svg( array( 'icon' => 'phone' ) ); ?>
																<span><?php echo $phone; ?></span></a>
															<?php
														}
														?>
													</address>
												</div>
												<?php
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="site-footer__colophon">
			<div class="grid-container">
				<div class="colophon">
					<div class="colophon__legalese">
						<p>&copy; <?php echo gmdate( 'Y' ); ?> <?php bloginfo( 'name' ); ?></p>
						</p>
					</div>
					<?php hny_get_menu( 'legal-nav' ); ?>
				</div>
			</div>
		</div>
	</div>
</footer>

<button class="back-to-top js-back-to-top" aria-label="Back to Top">
	<?php echo hny_get_svg( array( 'icon' => 'chevron-up' ) ); ?>
</button>

<div class="loader-overlay">
	<?php get_template_part( 'partials/loader-overlay' ); ?>
</div>

</div>
</div>
</div>
<?php
wp_enqueue_script( 'main' );
wp_footer();
?>
</body>
</html>
