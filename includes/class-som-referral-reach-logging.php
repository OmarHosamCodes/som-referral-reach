<?php
class SOM_Referral_Reach_Logging
{


    public function log_points_transaction($user_id, $points, $context)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'som_points_log';

        $wpdb->insert($table_name, [
            'user_id' => $user_id,
            'points' => $points,
            'reason' => $context,
            'date' => current_time('mysql')
        ]);
    }
}
