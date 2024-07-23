<?php

/**
 * Handles the shortcodes for the plugin.
 *
 * @since      1.0.0
 *
 * @package    SOM Referral Reach
 * @subpackage som-referral-reach/includes
 */



class SOM_Referral_Reach_Shortcodes
{



    public static function init()

    {


        // Hook shortcodes
        $shortcodes = [
            'som-referral-reach-main.php',
        ];


        try {
            foreach ($shortcodes as $shortcode) {
                $required_file = plugin_dir_path(__FILE__) . '../view/shortcodes/' . $shortcode;
                if (file_exists($required_file)) {
                    require_once $required_file;
                }
                $shortcode_name = str_replace('.php', '', $shortcode);
                $shortcode_name = str_replace('som-referral-reach-', '', $shortcode_name);
                $shortcode_name = str_replace('-', '_', $shortcode_name);
                $shortcode_name = 'SOM_Referral_Reach_' . ucwords($shortcode_name);

                add_shortcode('som-referral-reach-' . $shortcode_name, [$shortcode_name, 'create']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
