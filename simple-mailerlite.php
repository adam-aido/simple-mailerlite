<?php
/**
 * Plugin Name: Simple MailerLite Integration
 * Plugin URI: https://github.com/adam-aido/simple-mailerlite
 * Description: A lightweight newsletter subscription plugin that integrates with MailerLite API.
 * Version: 1.0.0
 * Author: Adam Antoszczak + Claude.ai
 * Author URI: https://webartisan.pro/
 * Text Domain: simple-mailerlite
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('SMPLR_VERSION', '1.0.0');
define('SMPLR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMPLR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SMPLR_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once SMPLR_PLUGIN_DIR . 'includes/class-simple-mailerlite.php';
require_once SMPLR_PLUGIN_DIR . 'includes/class-simple-mailerlite-admin.php';
require_once SMPLR_PLUGIN_DIR . 'includes/class-simple-mailerlite-subscriber.php';

/**
 * Begins execution of the plugin.
 */
function run_simple_mailerlite() {
    $plugin = new Simple_MailerLite();
    $plugin->run();
}

// Initialize the plugin
run_simple_mailerlite();

// Activation hook
register_activation_hook(__FILE__, 'simple_mailerlite_activate');

/**
 * Plugin activation function
 */
function simple_mailerlite_activate() {
    // Set default options
    add_option('simple_mailerlite_api_key', '');
    add_option('simple_mailerlite_group_id', '');
    add_option('simple_mailerlite_show_name', '1');
    add_option('simple_mailerlite_success_message', 'Thank you for subscribing!');
    add_option('simple_mailerlite_error_message', 'An error occurred. Please try again.');
    add_option('simple_mailerlite_name_label', 'Name');
    add_option('simple_mailerlite_email_label', 'Email');
    add_option('simple_mailerlite_submit_label', 'Subscribe');
    add_option('simple_mailerlite_privacy_text', '');
    
    // Add capability to administrators
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('manage_simple_mailerlite');
    }
    
    // Clear rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'simple_mailerlite_deactivate');

/**
 * Plugin deactivation function
 */
function simple_mailerlite_deactivate() {
    // Clear rewrite rules
    flush_rewrite_rules();
}

// Uninstall hook (defined in a separate file)
register_uninstall_hook(__FILE__, 'simple_mailerlite_uninstall');

/**
 * Plugin uninstall function
 */
function simple_mailerlite_uninstall() {
    // Remove plugin options
    delete_option('simple_mailerlite_api_key');
    delete_option('simple_mailerlite_group_id');
    delete_option('simple_mailerlite_show_name');
    delete_option('simple_mailerlite_success_message');
    delete_option('simple_mailerlite_error_message');
    delete_option('simple_mailerlite_name_label');
    delete_option('simple_mailerlite_email_label');
    delete_option('simple_mailerlite_submit_label');
    delete_option('simple_mailerlite_privacy_text');
}