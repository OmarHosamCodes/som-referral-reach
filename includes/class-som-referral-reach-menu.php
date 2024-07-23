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
        add_menu_page(
            'SOM Referral Reach',
            'Referral Reach',
            'manage_options',
            'som_referral_reach',
            [self::class, 'admin_index'],
            'dashicons-awards',
            110
        );

        add_submenu_page(
            'som_referral_reach',
            'Referral Logs',
            'Referral Logs',
            'manage_options',
            'som_referral_logs',
            [self::class, 'admin_logs']
        );
    }

    public static function admin_index()
    {
        require_once plugin_dir_path(__FILE__) . '../view/pages/admin.php';
    }

    public static function admin_logs()
    {
        require_once plugin_dir_path(__FILE__) . '../view/pages/logs.php';
    }

    public static function register_settings()
    {
        register_setting(
            'som_referral_reach_settings_group',
            'referral_conditions',
            [
                'sanitize_callback' => [SOM_Referral_Reach_Menu::class, 'sanitize_conditions'],
                'default' => []
            ]
        );
    }

    public static function sanitize_conditions($conditions)
    {
        error_log('Sanitize conditions called');
        error_log(print_r($conditions, true));

        if (!is_array($conditions)) {
            error_log('Conditions is not an array');
            return [];
        }

        $sanitized_conditions = [];
        foreach ($conditions as $index => $condition) {
            $sanitized_conditions[$index] = [
                'category' => sanitize_text_field($condition['category']),
                'trigger' => sanitize_text_field($condition['trigger']),
                'points' => intval($condition['points']),
                'min_price' => floatval($condition['min_price']),
                'custom_action' => sanitize_text_field($condition['custom_action'])
            ];
        }

        error_log('Sanitized conditions:');
        error_log(print_r($sanitized_conditions, true));

        // Clear the transient when settings are updated
        delete_transient('referral_conditions');

        return $sanitized_conditions;
    }
}
