<?php

if (!function_exists('irantheme_register_sidebars_widgets')) {
    /**
     * Initialize widget of sidebars
     * @return void
     * @param void
     * @see v1.0
     */
    function irantheme_register_sidebars_widgets()
    {
        // Single sidebar
        register_sidebar(array(
            'name'           => 'سایدبار اختصاصی قالب',
            'id'             => 'irantheme_sidebar_template',
            'description'    => 'سایدبار اختصاصی قالب',
            'class'          => '',
            'before_widget'  => '<aside class="irantheme-sidebar-box-holder irantheme-border-radius-pages">',
            'after_widget'   => '</aside>',
            'before_title'   => '<div class="irantheme-sidebar-box-title">',
            'after_title'    => '</div>',
            'before_sidebar' => '<div class="irantheme-sidebar">',
            'after_sidebar'  => '</div>',
        ));
    }
    add_action('widgets_init', 'irantheme_register_sidebars_widgets');
}
