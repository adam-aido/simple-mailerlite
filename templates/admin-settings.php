<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="simple-mailerlite-admin-container">
        <form method="post" action="options.php">
            <?php
            // Output security fields
            settings_fields('simple_mailerlite_settings');
            
            // Output setting sections and their fields
            do_settings_sections('simple_mailerlite_settings');
            
            // Output save settings button
            submit_button();
            ?>
        </form>
        
        <div class="simple-mailerlite-sidebar">
            <div class="simple-mailerlite-box">
                <h3><?php _e('How to Use', 'simple-mailerlite'); ?></h3>
                <p><?php _e('Use the shortcode below to display the subscription form on your site:', 'simple-mailerlite'); ?></p>
                <code>[simple_mailerlite]</code>
                <p><?php _e('You can add this shortcode to any post, page, or widget.', 'simple-mailerlite'); ?></p>
            </div>
            
            <div class="simple-mailerlite-box">
                <h3><?php _e('Need Help?', 'simple-mailerlite'); ?></h3>
                <p><?php _e('For questions about the plugin, please contact support.', 'simple-mailerlite'); ?></p>
            </div>
        </div>
    </div>
</div>