<?php
/**
 * The core plugin class.
 *
 * @since      1.0.0
 */
class Simple_MailerLite {

    /**
     * The admin class instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Simple_MailerLite_Admin
     */
    protected $admin;

    /**
     * The subscriber class instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Simple_MailerLite_Subscriber
     */
    protected $subscriber;

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->load_dependencies();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        $this->admin = new Simple_MailerLite_Admin();
        $this->subscriber = new Simple_MailerLite_Subscriber();
    }

    /**
     * Register all of the hooks related to the plugin functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    public function run() {
        // Load plugin text domain
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        
        // Initialize components
        $this->admin->init();
        $this->subscriber->init();
        
        // Register shortcode
        add_shortcode('simple_mailerlite', array($this, 'shortcode_form'));
        
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'simple-mailerlite',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    /**
     * Register public-facing scripts and styles.
     *
     * @since    1.0.0
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'simple-mailerlite-style',
            SMPLR_PLUGIN_URL . 'assets/css/public.css',
            array(),
            SMPLR_VERSION
        );

        // Register scripts
        wp_register_script(
            'simple-mailerlite-script',
            SMPLR_PLUGIN_URL . 'assets/js/public.js',
            array(),
            SMPLR_VERSION,
            true
        );

        // Localize script with AJAX URL and nonce
        wp_localize_script('simple-mailerlite-script', 'simple_mailerlite_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('simple_mailerlite_nonce'),
            'success_message' => esc_html(get_option('simple_mailerlite_success_message', 'Thank you for subscribing!')),
            'error_message' => esc_html(get_option('simple_mailerlite_error_message', 'An error occurred. Please try again.'))
        ));
    }

    /**
     * Shortcode callback to display the subscription form.
     * 
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes
     * @return   string   HTML output of the subscription form
     */
    public function shortcode_form($atts) {
        // Enqueue styles and scripts
        wp_enqueue_style('simple-mailerlite-style');
        wp_enqueue_script('simple-mailerlite-script');
        
        // Get plugin settings
        $show_name = get_option('simple_mailerlite_show_name', '1');
        $name_label = get_option('simple_mailerlite_name_label', 'Name');
        $email_label = get_option('simple_mailerlite_email_label', 'Email');
        $submit_label = get_option('simple_mailerlite_submit_label', 'Subscribe');
        
        // Start output buffer
        ob_start();
        
        // Include form template
        include SMPLR_PLUGIN_DIR . 'templates/form.php';
        
        // Return the contents of the output buffer
        return ob_get_clean();
    }
}