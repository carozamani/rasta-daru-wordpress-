<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

/**
 * A Base of Sections and Generation
 * The Parent Of Sections
 * 
 * @package Wordpress
 * @subpackage Storeplus
 * @since v1.0
 * @author iranianclever
 * @see v1.0
 * 
 * This class is a custom settings theme for admin page.
 */
if (!class_exists('Irantheme_Section')) {
    class Irantheme_Section
    {
        protected static $static_textdomain = IRANTHEME_TEXTDOMAIN;


        public function __construct() {}
    }
}
