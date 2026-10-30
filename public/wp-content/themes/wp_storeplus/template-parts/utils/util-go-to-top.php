<!-- Go to top -->
<?php
// Get scroll-to-top checkBox
$scroll_btn = get_option('irantheme_scrollbtn_show_checkbox');
// Get scroll-to-top position radioBtn
$scroll_btn_position = get_option('irantheme_scrollbtn_position');
// Get scroll-to-top color selectBox
$scroll_btn_color = get_option('irantheme_scrollbtn_color');

// Main holder scroll-to-top
$scroll_btn_result = null;

// Check color option & assign specific class
$scroll_btn_color_class = ($scroll_btn_color == 'dark') ? 'scroll-dark' : 'scroll-white';
// Check position option & assign specific class
$scroll_btn_position_class = ($scroll_btn_position == 'left') ? 'scroll-left' : 'scroll-right';

// Check scroll-top-btn show & init
if ($scroll_btn == 'on') :
?>
    <button class="irantheme-btn-void irantheme-scroll-up <?php echo esc_attr($scroll_btn_color_class) ?> <?php echo esc_attr($scroll_btn_position_class) ?>" id="irantheme-go-to-top"><i class="fa-light fa-angle-up"></i></button>
<?php
endif;
?>