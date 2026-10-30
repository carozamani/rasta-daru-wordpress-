<?php

if (!function_exists('irantheme_register_menus')) {
    /**
     * Add features
     * @return void
     */
    function irantheme_register_menus()
    {
        // Register nav header
        register_nav_menu('iranthemeNavbarMainMenu', 'منوی اصلی هدر');
        register_nav_menu('iranthemeNavbarSecondaryMenu', 'منوی فرعی هدر');
        register_nav_menu('iranthemeNavigationMainMenu', 'منوی شناور');
        register_nav_menu('iranthemeDropdownMainMenu', 'منوی کشویی');
        register_nav_menu('iranthemeFullScreenMainMenu', 'منوی تمام صفحه');
        register_nav_menu('iranthemeTopbarMenu', 'منوی نوار بالا');
    }
    add_action('after_setup_theme', 'irantheme_register_menus');
}
