<?php

namespace Irantheme_Dev;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_JSON_Helper')) {

    /**
     * Irantheme JSON Helper
     * Central JSON encode/decode layer with backward compatibility
     * 
     * @package Wordpress
     * @subpackage Storeplus
     * @since v2.0
     * @author iranianclever
     * @see v2.0
     */
    class Irantheme_JSON_Helper
    {
        /**
         * Smart decode JSON with multi-layer fallback for legacy data
         * Handles: standard JSON, slashed JSON, double-slashed JSON
         * 
         * @param string
         * @return array
         * @author iranianclever
         * @since v2.0
         * @see v2.0
         */
        public static function decode($raw)
        {
            if (empty($raw) || !is_string($raw)) {
                return array();
            }

            // Attempt 1: standard decode (new clean data)
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            // Attempt 2: legacy decode (data with addslashes)
            $unslashed = wp_unslash($raw);
            $decoded = json_decode($unslashed, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            // Attempt 3: double unslash (very old data)
            $unslashed = wp_unslash($unslashed);
            $decoded = json_decode($unslashed, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            return array();
        }


        /**
         * Encode data as clean JSON (no addslashes)
         * 
         * @param array
         * @return string
         * @author iranianclever
         * @since v2.0
         * @see v2.0
         */
        public static function encode($data)
        {
            if (!is_array($data) && !is_object($data)) {
                return '{}';
            }

            $encoded = wp_json_encode($data);

            if ($encoded === false) {
                return '{}';
            }

            return $encoded;
        }


        /**
         * Normalize objects_json value to array
         * Handles legacy string format and new array format
         * 
         * @param mixed
         * @return array
         * @author iranianclever
         * @since v2.0
         * @see v2.0
         */
        public static function normalize_objects($value)
        {
            // Already an array (new format)
            if (is_array($value)) {
                return $value;
            }

            // String (legacy format) - decode it
            if (is_string($value)) {
                $decoded = self::decode($value);
                return is_array($decoded) ? $decoded : array();
            }

            return array();
        }


        /**
         * Normalize a whole sections structure: decode all objects_json fields
         * 
         * @param array
         * @return array
         * @author iranianclever
         * @since v2.0
         * @see v2.0
         */
        public static function normalize_sections($sections_data)
        {
            if (!is_array($sections_data) || empty($sections_data['sections_items'])) {
                return $sections_data;
            }

            foreach ($sections_data['sections_items'] as &$section) {
                if (!isset($section['section_fields']) || !is_array($section['section_fields'])) {
                    continue;
                }

                if (array_key_exists('objects_json', $section['section_fields'])) {
                    $section['section_fields']['objects_json'] = self::normalize_objects(
                        $section['section_fields']['objects_json']
                    );
                }
            }
            unset($section);

            return $sections_data;
        }


        /**
         * Check if JSON string is valid
         * 
         * @param string
         * @return bool
         * @author iranianclever
         * @since v2.0
         * @see v2.0
         */
        public static function is_valid($raw)
        {
            if (empty($raw) || !is_string($raw)) {
                return false;
            }

            json_decode($raw);
            if (json_last_error() === JSON_ERROR_NONE) {
                return true;
            }

            $unslashed = wp_unslash($raw);
            json_decode($unslashed);
            return json_last_error() === JSON_ERROR_NONE;
        }
    }
}
