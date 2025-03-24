<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 */
class Simple_MailerLite_Admin {

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function init() {
        // Add settings page
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add settings link on plugin page
        add_filter('plugin_action_links_' . SMPLR_PLUGIN_BASENAME, array($this, 'add_settings_link'));
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Add settings link to plugin listing.
     *
     * @since    1.0.0
     * @param    array    $links    Plugin Action links
     * @return   array    Links array with our settings link added
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=simple-mailerlite') . '">' . __('Settings', 'simple-mailerlite') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Add options page.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        add_options_page(
            __('Simple MailerLite Settings', 'simple-mailerlite'),
            __('Simple MailerLite', 'simple-mailerlite'),
            'manage_options',
            'simple-mailerlite',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings, sections, and fields.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register settings
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_api_key', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_group_id', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_show_name', array(
            'sanitize_callback' => array($this, 'sanitize_checkbox'),
            'default' => '1',
        ));

        register_setting('simple_mailerlite_settings', 'simple_mailerlite_privacy_text', array(
            'sanitize_callback' => 'wp_kses_post',
            'default' => '',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_success_message', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Thank you for subscribing!',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_error_message', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'An error occurred. Please try again.',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_name_label', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Name',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_email_label', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Email',
        ));
        
        register_setting('simple_mailerlite_settings', 'simple_mailerlite_submit_label', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Subscribe',
        ));
        
        // Add API section
        add_settings_section(
            'simple_mailerlite_api_section',
            __('API Settings', 'simple-mailerlite'),
            array($this, 'render_api_section'),
            'simple_mailerlite_settings'
        );
        
        // Add form settings section
        add_settings_section(
            'simple_mailerlite_form_section',
            __('Form Settings', 'simple-mailerlite'),
            array($this, 'render_form_section'),
            'simple_mailerlite_settings'
        );
        
        // Add message settings section
        add_settings_section(
            'simple_mailerlite_message_section',
            __('Message Settings', 'simple-mailerlite'),
            array($this, 'render_message_section'),
            'simple_mailerlite_settings'
        );
        
        // API Key field
        add_settings_field(
            'simple_mailerlite_api_key',
            __('API Key', 'simple-mailerlite'),
            array($this, 'render_api_key_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_api_section'
        );
        
        // Group ID field
        add_settings_field(
            'simple_mailerlite_group_id',
            __('Group ID', 'simple-mailerlite'),
            array($this, 'render_group_id_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_api_section'
        );
        
        // Show name field
        add_settings_field(
            'simple_mailerlite_show_name',
            __('Show Name Field', 'simple-mailerlite'),
            array($this, 'render_show_name_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_form_section'
        );
        
        // Name label field
        add_settings_field(
            'simple_mailerlite_name_label',
            __('Name Field Label', 'simple-mailerlite'),
            array($this, 'render_name_label_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_form_section'
        );
        
        // Email label field
        add_settings_field(
            'simple_mailerlite_email_label',
            __('Email Field Label', 'simple-mailerlite'),
            array($this, 'render_email_label_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_form_section'
        );
        
        // Submit button label field
        add_settings_field(
            'simple_mailerlite_submit_label',
            __('Submit Button Label', 'simple-mailerlite'),
            array($this, 'render_submit_label_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_form_section'
        );
        
        // Privacy policy text field
        add_settings_field(
            'simple_mailerlite_privacy_text',
            __('Privacy Policy Text', 'simple-mailerlite'),
            array($this, 'render_privacy_text_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_form_section'
        );
        
        // Success message field
        add_settings_field(
            'simple_mailerlite_success_message',
            __('Success Message', 'simple-mailerlite'),
            array($this, 'render_success_message_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_message_section'
        );
        
        // Error message field
        add_settings_field(
            'simple_mailerlite_error_message',
            __('Error Message', 'simple-mailerlite'),
            array($this, 'render_error_message_field'),
            'simple_mailerlite_settings',
            'simple_mailerlite_message_section'
        );
    }

    /**
     * Sanitize checkbox value.
     *
     * @since    1.0.0
     * @param    mixed    $input    Value of the checkbox
     * @return   string   '1' if checked, '0' if not
     */
    public function sanitize_checkbox($input) {
        return ($input == 1) ? '1' : '0';
    }

    /**
     * Render the API section instructions.
     *
     * @since    1.0.0
     */
    public function render_api_section() {
        echo '<p>' . esc_html__('Enter your MailerLite API credentials. You can find your API key in your MailerLite account under Integrations > API.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the form section instructions.
     *
     * @since    1.0.0
     */
    public function render_form_section() {
        echo '<p>' . esc_html__('Customize the appearance of your subscription form.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the message section instructions.
     *
     * @since    1.0.0
     */
    public function render_message_section() {
        echo '<p>' . esc_html__('Customize the messages shown to users after form submission.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the API key field.
     *
     * @since    1.0.0
     */
    public function render_api_key_field() {
        $value = get_option('simple_mailerlite_api_key');
        echo '<input type="password" id="simple_mailerlite_api_key" name="simple_mailerlite_api_key" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__('Your MailerLite API key.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the group ID field.
     *
     * @since    1.0.0
     */
    public function render_group_id_field() {
        $value = get_option('simple_mailerlite_group_id');
        echo '<input type="text" id="simple_mailerlite_group_id" name="simple_mailerlite_group_id" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__('The ID of the MailerLite group you want to add subscribers to.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the show name field.
     *
     * @since    1.0.0
     */
    public function render_show_name_field() {
        $value = get_option('simple_mailerlite_show_name', '1');
        echo '<input type="checkbox" id="simple_mailerlite_show_name" name="simple_mailerlite_show_name" value="1" ' . checked('1', $value, false) . '>';
        echo '<span class="description">' . esc_html__('Show the name field in the subscription form.', 'simple-mailerlite') . '</span>';
    }

    /**
     * Render the name label field.
     *
     * @since    1.0.0
     */
    public function render_name_label_field() {
        $value = get_option('simple_mailerlite_name_label', 'Name');
        echo '<input type="text" id="simple_mailerlite_name_label" name="simple_mailerlite_name_label" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Render the email label field.
     *
     * @since    1.0.0
     */
    public function render_email_label_field() {
        $value = get_option('simple_mailerlite_email_label', 'Email');
        echo '<input type="text" id="simple_mailerlite_email_label" name="simple_mailerlite_email_label" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Render the submit label field.
     *
     * @since    1.0.0
     */
    public function render_submit_label_field() {
        $value = get_option('simple_mailerlite_submit_label', 'Subscribe');
        echo '<input type="text" id="simple_mailerlite_submit_label" name="simple_mailerlite_submit_label" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    /**
     * Render the privacy text field.
     *
     * @since    1.0.0
     */
    public function render_privacy_text_field() {
        $value = get_option('simple_mailerlite_privacy_text', '');
        wp_editor(
            $value,
            'simple_mailerlite_privacy_text',
            array(
                'textarea_name' => 'simple_mailerlite_privacy_text',
                'textarea_rows' => 5,
                'media_buttons' => false,
                'teeny'         => true,
                'quicktags'     => true,
            )
        );
        echo '<p class="description">' . esc_html__('Optional text to display between form fields and the submit button. Use this for privacy policy notices or other information. HTML is allowed for links.', 'simple-mailerlite') . '</p>';
    }

    /**
     * Render the success message field.
     *
     * @since    1.0.0
     */
    public function render_success_message_field() {
        $value = get_option('simple_mailerlite_success_message', 'Thank you for subscribing!');
        echo '<input type="text" id="simple_mailerlite_success_message" name="simple_mailerlite_success_message" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Render the error message field.
     *
     * @since    1.0.0
     */
    public function render_error_message_field() {
        $value = get_option('simple_mailerlite_error_message', 'An error occurred. Please try again.');
        echo '<input type="text" id="simple_mailerlite_error_message" name="simple_mailerlite_error_message" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Render the settings page.
     *
     * @since    1.0.0
     */
    public function render_settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Include settings template
        include SMPLR_PLUGIN_DIR . 'templates/admin-settings.php';
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @since    1.0.0
     * @param    string    $hook    The current admin page
     */
    public function enqueue_admin_assets($hook) {
        // Only enqueue on our settings page
        if ('settings_page_simple-mailerlite' !== $hook) {
            return;
        }
        
        // Enqueue admin style
        wp_enqueue_style(
            'simple-mailerlite-admin',
            SMPLR_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SMPLR_VERSION
        );
        
        // Enqueue admin script
        wp_enqueue_script(
            'simple-mailerlite-admin',
            SMPLR_PLUGIN_URL . 'assets/js/admin.js',
            array(),
            SMPLR_VERSION,
            true
        );
    }
}