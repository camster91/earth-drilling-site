<?php

if ( ! is_post_type_archive( 'industry' ) ) {
	return;
}

?>

<div class="secondary-nav secondary-nav--scroll js-sticky" data-context="dark">
	<div class="secondary-nav__scroll">
		<div class="grid-container">
			<?php hny_get_menu( 'industry-section-nav' ); ?>
		</div>
	</div>
</div>
