<?php
/**
 * The subscriber functionality of the plugin.
 *
 * @since      1.0.0
 */
class Simple_MailerLite_Subscriber {

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function init() {
        // Register AJAX actions
        add_action('wp_ajax_simple_mailerlite_subscribe', array($this, 'process_ajax_subscription'));
        add_action('wp_ajax_nopriv_simple_mailerlite_subscribe', array($this, 'process_ajax_subscription'));
    }

    /**
     * Process AJAX subscription requests.
     *
     * @since    1.0.0
     */
    public function process_ajax_subscription() {
        // Check the nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_mailerlite_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed.', 'simple-mailerlite')
            ));
        }

        // Get and sanitize form data
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        
        // Validate email
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array(
                'message' => __('Please enter a valid email address.', 'simple-mailerlite')
            ));
        }

        // Get API settings
        $api_key = get_option('simple_mailerlite_api_key', '');
        $group_id = get_option('simple_mailerlite_group_id', '');
        
        // Check if API key is set
        if (empty($api_key)) {
            wp_send_json_error(array(
                'message' => __('API key is not configured.', 'simple-mailerlite')
            ));
        }
        
        // Process the subscription
        $result = $this->subscribe_to_mailerlite($email, $name, $api_key, $group_id);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array(
                'message' => $result->get_error_message()
            ));
        } else {
            wp_send_json_success(array(
                'message' => get_option('simple_mailerlite_success_message', 'Thank you for subscribing!')
            ));
        }
        
        wp_die();
    }

    /**
     * Subscribe a user to MailerLite.
     *
     * @since    1.0.0
     * @param    string    $email       The subscriber's email
     * @param    string    $name        The subscriber's name
     * @param    string    $api_key     The MailerLite API key
     * @param    string    $group_id    The MailerLite group ID
     * @return   mixed     WP_Error or true on success
     */
    private function subscribe_to_mailerlite($email, $name, $api_key, $group_id) {
        // Prepare the subscriber data
        $subscriber_data = array(
            'email' => $email
        );
        
        // Add name if provided
        if (!empty($name)) {
            $subscriber_data['name'] = $name;
        }
        
        // MailerLite API endpoint for adding a subscriber to a group
        $endpoint = "https://api.mailerlite.com/api/v2/groups/{$group_id}/subscribers";
        
        // Prepare the request
        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'X-MailerLite-ApiKey' => $api_key
            ),
            'body'    => json_encode(array(
                'email' => $email,
                'name' => $name,
                'resubscribe' => true
            )),
            'timeout' => 30
        );
        
        // Make the request
        $response = wp_remote_post($endpoint, $args);
        
        // Check for errors
        if (is_wp_error($response)) {
            return new WP_Error('api_error', $response->get_error_message());
        }
        
        // Get response code
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        // Check response code
        if ($response_code < 200 || $response_code >= 300) {
            $error_message = isset($response_body['error']['message']) 
                ? $response_body['error']['message'] 
                : __('Unknown error occurred.', 'simple-mailerlite');
                
            return new WP_Error('api_error', $error_message);
        }
        
        // Log success (for debugging purposes)
        error_log('Successfully subscribed: ' . $email . ' to MailerLite group ' . $group_id);
        
        return true;
    }
}