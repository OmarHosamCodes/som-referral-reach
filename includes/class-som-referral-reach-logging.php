<?php

/**
 * Used for logging any points transactions
 *
 * @since 1.0.0
 *
 * @package SOM Referral Reach
 * @subpackage som-referral-reach/includes
 */

class SOM_Referral_Reach_Logging
{

    /**
     * Log points transaction
     *
     * @since 1.0.0
     */
    public static function log_points_transaction($user_id, $points, $reason)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'points_log';

        $wpdb->insert(
            $table_name,
            [
                'user_id' => $user_id,
                'points' => $points,
                'reason' => $reason
            ]
        );
    }
}
