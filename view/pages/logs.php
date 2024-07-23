<div class="wrap">
    <h1>SOM Referral Reach Logs</h1>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'som_points_log';
    $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC");

    if ($logs) :
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Points</th>
                    <th>Action</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log) : ?>
                    <tr>
                        <td><?php echo esc_html($log->id); ?></td>
                        <td><?php echo esc_html($log->user_id); ?></td>
                        <td><?php echo esc_html($log->points); ?></td>
                        <td><?php echo esc_html($log->action); ?></td>
                        <td><?php echo esc_html($log->date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No logs found.</p>
    <?php endif; ?>
</div>