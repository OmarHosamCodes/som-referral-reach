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
            ['som-referral-reach-main.php', 'Main', 'srr_main_shortcode']
        ];


        try {
            foreach ($shortcodes as $shortcode) {
                require_once plugin_dir_path(__FILE__) . '../view/shortcodes/' . $shortcode[0];
                add_shortcode($shortcode[2], ['SOM_Referral_Reach_' . $shortcode[1], 'create']);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}
