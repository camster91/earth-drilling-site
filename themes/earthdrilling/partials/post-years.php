<?php
if ( ! hny_is_blog() ) {
	return;
}
$type    = 'post';
$years   = hny_get_years( $type, null );
$current = is_single() ? get_the_date( 'Y', get_the_ID() ) : get_query_var( 'year' );
if ( $years && 1 < count( $years ) ) {
	?>
	<div class="year-links">
		<ul class="year-links__items">
			<?php
			foreach ( $years as $year ) {
				$link = get_post_type_archive_link( $type );

				if ( $year !== $years[0] ) {
					$link .= $year . '/';
				}

				$active = (int) $current === (int) $year;
				$class  = $active ? 'year-links__btn-link year-links__btn-link--active' : 'year-links__btn-link';
				?>
				<li class="year-links__item">
					<a class="<?php echo $class; ?>" href="<?php echo $link; ?>">
						<span>
							<?php echo $year; ?>
						</span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<select class="year-links__dropdown js-year-select" name="year-select"
				aria-label="Select Year">
			<?php
			foreach ( $years as $year ) {
				$link = get_post_type_archive_link( $type );
				if ( $year !== $years[0] ) {
					$link .= $year . '/';
				}
				$active = (int) $current === (int) $year;
				?>
				<option class="year-links__dropdown-link"
						value="<?php echo $link; ?>"
					<?php
					if ( $active ) {
						?>
						selected <?php } ?> >
					<?php echo $year; ?>
				</option>
			<?php } ?>
		</select>
	</div>
	<?php
}
