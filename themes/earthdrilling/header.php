<!doctype html>
<html class="no-js" <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebSite"
		prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="theme-color" content="#aa9767">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="off-canvas-wrapper">
	<?php get_template_part( 'partials/mobile-nav' ); ?>
	<div class="off-canvas-content" data-off-canvas-content>
		<div class="root">
			<header class="l-header">
				<div class="site-header">
					<div class="site-header__utility">
						<div class="grid-container">
							<?php hny_get_menu( 'header-utility' ); ?>
						</div>
					</div>
					<div class="site-header__masthead">
						<div class="grid-container">
							<div class="masthead">
								<div class="masthead__logo">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
											title="<?php echo get_bloginfo( 'name' ); ?>">
										<div class="u-svg-container u-svg-container--logo">
											<img src="<?php echo hny_get_theme_image( 'logo.png' ); ?>"
													alt="Earth Drilling" />
										</div>
										<span class="u-screen-reader"><?php echo get_bloginfo( 'name' ); ?> - Return to home page</span>
									</a>
								</div>
								<div class="masthead__utility">
									<div class="masthead__nav">
										<?php hny_get_menu( 'primary-nav' ); ?>
										<?php get_template_part( 'partials/navburger' ); ?>
									</div>
									<div class="masthead__search">
										<button type="button" class="js-search-toggle"
												aria-label="Search">
											<?php echo hny_get_svg( array( 'icon' => 'search' ) ); ?>
											<?php echo hny_get_svg( array( 'icon' => 'cancel' ) ); ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="site-header__search">
						<div class="grid-container">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</header>
			<?php get_template_part( 'partials/secondary-nav' ); ?>
			<?php get_template_part( 'partials/industry-nav' ); ?>

			<main class="l-main">
				<?php get_template_part( 'partials/hero' ); ?>
				<h2 class="u-screen-reader">Main Content</h2>
				<div class="page-content">
