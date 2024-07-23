<?php
/*
Plugin Name: SOM Referral Reach
Description: This plugin provides a simple referral and point system for your website.
Version: 2.0.0
Author: Omar Hosam (School Of Marketing Team)
*/

defined('ABSPATH') or die('Hey, you can\'t access this file!');

class SOM_Referral_Reach
{

    private $conditions;

    public function __construct()
    {
        // Hook activation and deactivation functions
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Initialize hooks
        add_action('init', [$this, 'hooks']);
    }

    public function activate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-activate.php';
        (new SOM_Referral_Reach_Activate())->activate();
    }

    public function deactivate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-som-referral-reach-deactivate.php';
        (new SOM_Referral_Reach_Deactivate())->deactivate();
    }

    private function get_conditions()
    {
        if (false === ($conditions = get_transient('referral_conditions'))) {
            $conditions = get_option('referral_conditions', []);
            set_transient('referral_conditions', $conditions, 12 * HOUR_IN_SECONDS);
        }
        return $conditions;
    }

    public function handle_order_state_change($order_id, $new_status)
    {
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        $conditions = $this->get_conditions();

        foreach ($conditions as $condition) {
            if ($condition['category'] === 'on_order' && $new_status === $condition['trigger']) {
                if ($new_status === 'cancelled' || $new_status === 'refunded') {
                    $this->remove_points($user_id, $condition['points'], 'order_' . $new_status);
                } elseif ($order->get_total() >= $condition['min_price']) {
                    $this->award_points($user_id, $condition['points'], 'order_' . $new_status);
                }
            }
        }
    }

    private function remove_points($user_id, $points, $context)
    {
        $previously_awarded = get_user_meta($user_id, 'points_awarded', true);
        if ($previously_awarded >= $points) {
            SOM_Referral_Reach_Logging::log_points_transaction($user_id, -$points, $context);
            update_user_meta($user_id, 'points_awarded', $previously_awarded - $points);
        }
    }

    private function award_points($user_id, $points, $context)
    {
        SOM_Referral_Reach_Logging::log_points_transaction($user_id, $points, $context);
    }

    public function handle_user_action($user_id, $action)
    {
        $conditions = $this->get_conditions();

        foreach ($conditions as $condition) {
            if ($condition['category'] === 'on_user_actions' && $condition['trigger'] === $action) {
                $this->award_points($user_id, $condition['points'], 'user_' . $action);
            }
        }
    }

    public function hooks()
    {
        // Enqueue styles
        wp_enqueue_style('som_referral_reach_style', plugin_dir_url(__FILE__) . 'view/css/main.css');

        // Include and initialize classes
        $this->include_and_initialize('class-som-referral-reach-menu.php', 'SOM_Referral_Reach_Menu', 'register');
        // $this->include_and_initialize('class-som-referral-reach-shortcodes.php', 'SOM_Referral_Reach_Shortcodes', 'init');
        // $this->include_and_initialize('class-som-referral-reach-logging.php', 'SOM_Referral_Reach_Logging', 'init');

        // Hook actions for reviews
        add_action('review_status_changed', [$this, 'handle_review_status_change'], 10, 2);

        // Hook actions for WooCommerce order status changes
        $order_statuses = ['pending', 'processing', 'completed', 'cancelled', 'refunded'];
        foreach ($order_statuses as $status) {
            add_action("woocommerce_order_status_$status", [$this, 'handle_order_state_change'], 10, 2);
        }

        // Hook actions for user actions
        add_action('user_register', [$this, 'handle_user_action'], 10, 1);
        add_action('wp_login', [$this, 'handle_user_action'], 10, 2);
        add_action('user_birthday', [$this, 'handle_user_action'], 10, 1);
    }

    private function include_and_initialize($file, $class, $method)
    {
        require_once plugin_dir_path(__FILE__) . "includes/$file";
        (new $class())->$method();
    }

    public function handle_review_status_change($review_id, $status)
    {
        $conditions = $this->get_conditions();
        $user_id = get_post_meta($review_id, 'reviewer_id', true);

        foreach ($conditions as $condition) {
            if ($condition['category'] === 'on_review') {
                $trigger = $condition['trigger'];

                if ($trigger === 'on_approved' && $status === 'approved') {
                    $this->award_points($user_id, $condition['points'], 'review_approved');
                } elseif ($trigger === 'on_disapproved' && $status === 'disapproved') {
                    // Optionally handle disapproved cases
                } elseif ($trigger === 'on_approved_and_purchased') {
                    $purchase_made = get_post_meta($review_id, 'purchase_made', true);
                    if ($status === 'approved' && $purchase_made) {
                        $this->award_points($user_id, $condition['points'], 'review_approved_and_purchased');
                    }
                }
            }
        }
    }
}

// Initialize the plugin
new SOM_Referral_Reach();
