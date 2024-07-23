<?php

/**
 * Fired during plugin deactivation
 *
 * @since 1.0.0
 *
 * @package SOM Referral Reach
 * @subpackage som-referral-reach/includes
 */


class SOM_Referral_Reach_Deactivate
{

    /**
     * Fired during plugin deactivation
     *
     * @since 1.0.0
     */
    public static function deactivate()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'referrals';
        $log_table_name = $wpdb->prefix . 'points_log';

        // Drop the referrals table
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Drop the points_log table
        $wpdb->query("DROP TABLE IF EXISTS $log_table_name");

        error_log('Plugin uninstall function executed without output');
    }
}
