<?php
/**
 * Contact Info
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Get content from theme mod
$content = get_theme_mod( 'ciah_contactinfo_text', 'Important information about your company could be always visible for your customers.');

// Get button
$button = get_theme_mod( 'ciah_contactinfo_button', true );

// Get button url
$button_url = get_theme_mod( 'ciah_contactinfo_button_url', '#' );

// Get button text
$button_text = get_theme_mod( 'ciah_contactinfo_button_txt', 'Call To Action' );

// If button is defined set target and rel
if ( $button ) {

	// Button target
	$target	= get_theme_mod( 'ciah_contactinfo_button_target', 'blank' );
	$target	= ( 'blank' == $target ) ? '_blank' : '_self';

	// Button rel
	$rel = get_theme_mod( 'ciah_contactinfo_button_rel', false );
	$rel = $rel ? $rel : '';

	if ( $rel == 'nofollow' ) {
		$rel = 'nofollow';
	}
	else if ( $rel == 'noopnoref' ) {
		$rel = 'noopener noreferrer';
	}
	else if ( $rel == 'nofnopnorr' ) {
		$rel = 'nofollow noopener noreferrer';
	}
	else {
		$rel = '';
	}

}

$close = get_theme_mod( 'ciah_contactinfo_close_button', true );

// Translate theme mods
$content 		= oceanwp_tm_translation( 'ciah_contactinfo_text', $content );
$button_url 	= oceanwp_tm_translation( 'ciah_contactinfo_button_url', $button_url );
$button_text 	= oceanwp_tm_translation( 'ciah_contactinfo_button_txt', $button_text );

// Button classes
$classes = array( 'contact-info-button', 'clr' );

// Custom classes
$custom_classes = get_theme_mod( 'ciah_contactinfo_button_classes' );
if ( ! empty( $custom_classes ) ) {
	$classes[] = $custom_classes;
}

// Turn classes into space seperated string
$classes = implode( ' ', $classes ); ?>
	
<div id="contact-info-wrap" class="clr">

	<?php if ($close) : ?>

	<span class='contact-info-close' onclick="document.getElementById('contact-info-wrap').className='hidden'">+</span>

	<?php endif; ?>

	<div id="contact-info" class="container clr">

		<div id="contact-info-left" class="contact-info-content clr <?php if ( ! $button ) echo 'full-width'; ?>">

			<?php

			// Display template content
			echo do_shortcode( $content ); 
			
			?>

		</div><!-- #contact-info-left -->

		<?php
		// Display Contact Info button if contactinfo button & text options are not blank in the admin
		if ( $button ) : ?>

			<div id="contact-info-right" class="<?php echo esc_attr( $classes ); ?>">

				<a href="<?php echo esc_url( $button_url ); ?>" class="contactinfo-button" target="<?php echo esc_attr($target); ?>" <?php if ($rel) { ?> rel="<?php echo esc_attr($rel);?>"<?php } ?>><?php echo esc_html( $button_text ); ?></a>
			<?php
					// Display screen reader text
					if ( $target == '_blank' ) { ?>
						<span class="screen-reader-text"><?php
						echo esc_attr( $button_text );
						echo esc_attr__(' Opens in a new tab', 'ocean-contact-info' ); ?>
						</span>
			<?php	}
			?>
			</div><!-- #contact-info-right -->

		<?php endif; ?>

	</div><!-- #contact-info -->

</div><!-- #contact-info-wrap -->	