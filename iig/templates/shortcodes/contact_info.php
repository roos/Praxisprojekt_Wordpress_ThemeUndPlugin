<?php
/*
Diese Datei ueberschreibt die contact_info.php vom parent-theme.
*/
?>

<div class="contact_info_wrap">
	<p style="text-align: center;"><?php echo do_shortcode('[icon name="custom-ac524762908369c4225229d3ade464b7" style="" color="accent1" size="42"]') ?></p>

	<?php if ( ! empty( $name ) ):?>
		<h3 style="text-align: center;"><?php echo $name // xss ok ?></h3>
	<?php endif ?>

	<?php if ( ! empty( $address ) ):?>
		<p style="text-align: center;"><span class="contact_address"><?php echo do_shortcode( '[icon name="theme-map" color="'.$color.'"]'.$address ) // xss ok ?></span></p>
	<?php endif ?>

	<?php if ( ! empty( $phone ) ) : ?>
		<p style="text-align: center;"><a href="tel:<?php echo esc_attr( $phone ) ?>" title="<?php echo esc_attr( sprintf( 'Call %s', strip_tags( $name ) ) ) ?>"><?php echo do_shortcode('[icon name="theme-phone" color="' . $color . '"]' . $phone ) // xss ok ?></a></p>
	<?php endif ?>

	<?php if ( ! empty( $cellphone ) ) : ?>
		<p><a href="tel:<?php echo esc_attr( $cellphone ) ?>" title="<?php echo esc_attr( sprintf( 'Call %s', strip_tags( $name ) ) ) ?>"><?php echo do_shortcode('[icon name="theme-cellphone" color="' . $color . '"]' . $cellphone ) // xss ok ?></a></p>
	<?php endif ?>

	<?php if ( ! empty( $email ) ):?>
		<p style="text-align: center;"><a href="mailto:<?php echo esc_attr( $email ) ?>" ><?php echo do_shortcode( '[icon name="theme-mail" color="'.$color.'"]'.$email ) // xss ok ?></a></p>
	<?php endif ?>

</div>
