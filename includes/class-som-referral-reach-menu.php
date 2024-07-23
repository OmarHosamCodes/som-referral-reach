<?php
class SOM_Referral_Reach_Menu
{

    public static function register()
    {
        add_action('admin_menu', [self::class, 'add_admin_pages']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_admin_pages()
    {
        add_menu_page('SOM Referral Reach', 'Referral Reach', 'manage_options', 'som_referral_reach', [self::class, 'admin_index'], 'dashicons-awards', 110);
    }

    public static function admin_index()
    {
        require_once plugin_dir_path(__FILE__) . '../view/pages/admin.php';
    }

    public static function register_settings()
    {
        register_setting('som_referral_reach_settings_group', 'referral_conditions');
    }
}

SOM_Referral_Reach_Menu::register();
