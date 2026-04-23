<?php
/**
 * Create the placeholder value
 */

$query       = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$placeholder = $query ? $query : 'What are you looking for?';
?>

<div class="site-search js-search-form">
	<form action="<?php echo home_url(); ?>" method="get" class="site-search__form">
		<div class="site-search__input">
			<label for="s">
				<span class="u-screen-reader"><?php _e( 'Search for:', 'hny' ); ?></span>
				<?php echo hny_get_svg( array( 'icon' => 'search' ) ); ?>
				<input type="text" id="s" name="s" value="<?php echo $query; ?>"
					placeholder="<?php echo $placeholder; ?>" />
			</label>
		</div>
		<div class="site-search__submit">
			<button class="button secondary" type="submit">
				Search
			</button>
		</div>
	</form>
</div>
