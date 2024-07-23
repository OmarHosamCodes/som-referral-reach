<?php
/*
Plugin Name: SOM Referral Reach
Plugin URI: https://www.somtoday.nl
Description: This plugin provides a simple referral and point systems for your website.
Version: 1.0.0
Author: Omar Hosam (School Of Marketing Team)
*/

defined('ABSPATH') or die('Hey, you can\t access this file!');

// Include the main plugin file
require_once plugin_dir_path(__FILE__) . 'view/css/main.css';
// Hook styles
wp_enqueue_style('ai-quadrillion-style', plugin_dir_path(__FILE__) . '../view/css/main.css');


// Include the SOM_Referral_Reach_Activate class
require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-activate.php';

// Include the SOM_Referral_Reach_Deactivate class
require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-deactivate.php';

// Include the SOM_Referral_Reach_Logging class
require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-logging.php';

// Initialize the SOM_Referral_Reach_Activate class
$activate = new SOM_Referral_Reach_Activate();

// Hook the functions to the respective actions
register_activation_hook(__FILE__, [$activate, 'activate']);

// Initialize the SOM_Referral_Reach_Deactivate class
$deactivate = new SOM_Referral_Reach_Deactivate();

// Hook the functions to the respective actions
register_deactivation_hook(__FILE__, [$deactivate, 'deactivate']);

// Include the SOM_Referral_Reach_Menu class
require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-menu.php';

function initialize_som_referral_reach_menu()
{

    // Initialize the SOM_Referral_Reach_Menu class
    $menu = new SOM_Referral_Reach_Menu();
    // Hook the functions to the respective actions
    add_action('admin_menu', [$menu, 'create_admin_menu']);
}
initialize_som_referral_reach_menu();


// Include the SOM_Referral_Reach_Shortcodes class
require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-shortcodes.php';

function initialize_som_referral_reach_shortcodes()
{

    // Initialize the SOM_Referral_Reach_Shortcodes class
    $shortcodes = new SOM_Referral_Reach_Shortcodes();
    // Hook the functions to the respective actions
    add_action('init', [$shortcodes, 'init']);
}
initialize_som_referral_reach_shortcodes();
