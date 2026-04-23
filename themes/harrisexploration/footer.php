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
				<div class="site-footer__heading">
					<div class="grid-container">
						<?php
						hny_get_template_part(
							'partials/heading-group',
							array(
								'heading' => 'Contact ',
								'level'   => 4,
								'drill'   => true,
							)
						);
						?>
					</div>
				</div>
				<div class="site-footer__columns">
					<div class="grid-container">
						<div class="grid-x grid-padding-x">
							<div class="small-12 large-10 xlarge-9 cell">
								<div class="grid-x grid-padding-x grid-padding-x--small">
									<div class="small-12 large-4 large-shrink cell">
										<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
												title="<?php echo get_bloginfo( 'name' ); ?>" class="site-footer__logo">
											<div class="u-svg-container u-svg-container--footer-logo">
												<img src="<?php echo hny_get_theme_image( 'logo-foot.png' ); ?>"
														alt="Earth Drilling" />
											</div>
											<span class="u-screen-reader"><?php echo get_bloginfo( 'name' ); ?> - Return to home page</span>
										</a>
									</div>
									<?php
									hny_get_template_part( 'partials/address');
									?>
									<div class="small-12 large-auto cell">
										<?php hny_get_template_part(
											'partials/associations',
										);
										?>
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
						<p>
							<a href="https://www.honeycombcreative.com" target="_blank"
									rel="noopener noreferrer"
									title="Vancouver Website Design by Honeycomb Creative">
								<?php
								_e(
									'Website Design by Honeycomb Creative',
									'hny'
								);
								?>
							</a>
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
