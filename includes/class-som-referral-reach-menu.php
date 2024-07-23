<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since 1.0.0
 *
 * @package SOM Referral Reach
 * @subpackage som-referral-reach/includes
 */

// require som-refferal-reach/view/menu-ui.php
class SOM_Referral_Reach_Menu
{
    /**
     * Fired during plugin activation
     *
     * @since 1.0.0
     */

    public  function create_admin_menu()
    {

        require_once plugin_dir_path(__FILE__) . '../view/shortcodes/som-referral-reach-main.php';
        $main_ui = new SOM_Referral_Reach_Main();
        add_menu_page(
            'Referral Reach',
            'Referral Reach',
            'manage_options',
            'som-referral-reach',
            [$main_ui, 'create'],
            'dashicons-share-alt'
        );
    }
}
