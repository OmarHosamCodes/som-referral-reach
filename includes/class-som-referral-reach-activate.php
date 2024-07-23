<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    SOM Referral Reach
 * @subpackage som-referral-reach/includes
 */

class SOM_Referral_Reach_Activate
{

    /**
     * Fired during plugin activation
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'referrals';
        $log_table_name = $wpdb->prefix . 'points_log';

        $charset_collate = $wpdb->get_charset_collate();

        // Check and create the referrals table
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                referrer_id BIGINT(20) NOT NULL,
                referee_id BIGINT(20) NOT NULL,
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        // Check and create the points_log table
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $log_table_name)) != $log_table_name) {
            $sql_log = "CREATE TABLE $log_table_name (
                id BIGINT(20) NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) NOT NULL,
                points INT(11) NOT NULL,
                reason VARCHAR(255) NOT NULL,
                date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql_log);
        }



        error_log('Plugin install function executed without output');
    }
}
