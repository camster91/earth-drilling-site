<?php
$showPhone = $template_args['phone'] ?? true;
$addresses = Hny_Site_Settings::get_addresses();
?>

<address>
	<?php
	foreach ( $addresses as $address ) {
		$phone = $address['phone'];
		echo ( $address['address'] ? $address['address'] . '<br>' : '' ) . $address['city'] . ', ' . $address['province_state']. '<br>'.$address['zip_postal_code'];

		if ( ! $address['address'] ) {
			echo '<br>';
		}

		?>
		<?php
		if ( $phone && $showPhone) {
			?>
			<br />
			<a href="tel:<?php echo hny_to_tel( $phone ); ?>"
			   class="inline-icon">
				<?php echo hny_get_svg( array( 'icon' => 'phone' ) ); ?>
				<span><?php echo $phone; ?></span></a>
			<?php
		}
	}
	?>
</address>
