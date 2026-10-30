<?php

use Irantheme_Dev\Irantheme_Common_Static;
use Irantheme_Dev\Irantheme_Header_Main;
use Irantheme_Dev\Irantheme_Section_Service;

?>

<?php
// Content of 404 --------------------------------------------------------------
$content_404 = get_option('irantheme_404_content_text');

// Header checkbox --------------------------------------------------------------
$header_checkbox = get_option('irantheme_404_header_checkbox');

// Footer checkbox --------------------------------------------------------------
$footer_checkbox = get_option('irantheme_404_footer_checkbox');

if ($header_checkbox == 'on') {
	get_header();
} else {
	// Get header head
	Irantheme_Header_Main::header_head();
}

// Base color --------------------------------------------------------------
$base_color =
	get_option('irantheme_404_base_color_select');
// Set base color class
$base_color_class = Irantheme_Common_Static::get_base_color_class($base_color);

// Get product content alignment --------------------------------------------------------------
$content_alignment = get_option('irantheme_404_align_content_radio');
$content_alignment_class = Irantheme_Section_Service::get_alignment_class($content_alignment);

// Set section height option --------------------------------------------------------------
$fullscreen_checkbox =
	get_option('irantheme_404_fullscreen_checkbox');

// Get section height option
$section_height =
	get_option('irantheme_404_height_range');
// Init holder padding full style
$section_height_full = '';
$section_fullscreen_class = '';
// Check full screen checkbox
if ($fullscreen_checkbox != 'on') {
	// Set range height (padding)
	$section_height_full = 'padding-top:' . ($section_height) . 'rem;padding-bottom:' . $section_height . 'rem;';
} else {
	$section_fullscreen_class = 'irantheme-hero-fullscreen';
}

// Get background color
$background_color = get_option('irantheme_404_bg_color_select');
// Get background status
$background_checkbox = get_option('irantheme_404_bg_checkbox');
$jarallax_data = '';
// Check background checkbox
if ($background_checkbox == 'on') {
	// Background url
	$image_src =
		wp_get_attachment_image_src(get_option('irantheme_404_bg'), 'full')[0];
	// Enable jarallax?
	$jarallax_checkbox = get_option('irantheme_404_bg_scroll_checkbox');
	// Check jarallax checkbox
	if ($jarallax_checkbox == 'on') {
		// Assignment jarallax data
		$jarallax_data = 'data-image-url="' . $image_src . '"';
	} else {
		$style_bg_url = 'background-image:url("' . $image_src . '");';
	}
}
?>
<div class="irantheme-404-not-found irantheme-background-jarallax <?php echo esc_attr($section_fullscreen_class); ?> <?php echo esc_attr($base_color); ?>" style="<?php echo esc_attr(Irantheme_Common_Static::get_bg_color_css($background_color)); ?><?php echo esc_attr($style_bg_url . $section_height_full); ?>" <?php echo $jarallax_data; ?>>
	<?php
	// Set mask display status --------------------------------------------------------------
	$mask_checkbox
		= get_option('irantheme_404_mask_checkbox');
	if ($background_checkbox == 'on' && $base_color == 'dark' && $mask_checkbox == 'on') :
	?>
		<!-- Mask dark -->
		<div class="irantheme-mask-dark"></div>
	<?php
	elseif ($background_checkbox == 'on' && $base_color == 'white' && $mask_checkbox == 'on') :
	?>
		<!-- Mask white -->
		<div class="irantheme-mask-white"></div>
	<?php
	endif;
	?>
	<div class="irantheme-container-inner">
		<?php
		// Display tinymce content
		Irantheme_Common_Static::get_snippet_tinymce_simple($content_404, $base_color_class, '', $content_alignment_class, '', '');

		// Get social media status checkbox --------------------------------------------------------
		$social_status_checkbox = get_option('irantheme_404_social_checkbox');
		if ($social_status_checkbox == 'on') :
		?>
			<!-- Social media -->
			<div class="irantheme-404-not-found-social-media <?php echo esc_attr($content_alignment_class); ?>">
				<?php
				// Get social media
				Irantheme_Common_Static::get_social_media();
				?>
			</div>
		<?php
		endif;
		?>
	</div>
</div>

<?php
if ($footer_checkbox == 'on') :
	Irantheme_Footer_Generator::display_footer();
endif;
get_footer();
?>