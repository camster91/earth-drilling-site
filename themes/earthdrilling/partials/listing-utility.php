<div class="listing__utility">
	<div class="grid-container">
		<?php
		hny_get_template_part(
			'partials/heading-group',
			array(
				'heading' => hny_get_page_title(),
				'drill'   => true,
				'level'   => 2,
			)
		);
		?>
		<p>Check our <a href="<?php echo Hny_Site_Settings::get_linkedin(); ?>" target="_blank" rel="noreferrer noopener"><?php echo hny_get_svg( array( 'icon' => 'linkedin' ) ); ?><span>LinkedIn page</span></a> for recent updates and announcements.</p>
		<?php get_template_part( 'partials/post-years' ); ?>
	</div>
</div>
