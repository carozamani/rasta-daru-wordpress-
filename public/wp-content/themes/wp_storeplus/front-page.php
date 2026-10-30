<?php

use Irantheme_Dev\Irantheme_Page_Builder;


if (is_home() && is_front_page()) {
    // Get blog template (Index)
    Irantheme_Archives::get_template_index();
} else {
    // Call page builder methods
    get_header();
    Irantheme_Page_Builder::display_section();
    get_footer();
}
