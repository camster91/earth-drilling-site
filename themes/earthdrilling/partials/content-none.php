<?php /* If there are no posts */ ?>
<div class="grid-container">
	<div class="four-oh-four not-found">
		<?php
		if ( is_search() ) {
			get_template_part( 'partials/search-header' );
			get_template_part( 'partials/groundhog' );
		} elseif ( is_404() ) {
			get_template_part( 'partials/404' );
		} else {
			echo '<p class="lead">' . __(
				"Sorry, there doesn't seem to be any content.",
				'hny'
			) . '</p>';
		}
		?>
	</div>
</div>
