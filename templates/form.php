<div class="simple-mailerlite-form-container">
    <form id="simple-mailerlite-form" class="simple-mailerlite-form">
        <div class="simple-mailerlite-message" style="display: none;"></div>
        
        <?php if ($show_name == '1'): ?>
        <div class="simple-mailerlite-field">
            <label for="simple-mailerlite-name"><?php echo esc_html($name_label); ?></label>
            <input type="text" id="simple-mailerlite-name" name="name" placeholder="<?php echo esc_attr($name_label); ?>">
        </div>
        <?php endif; ?>
        
        <div class="simple-mailerlite-field">
            <label for="simple-mailerlite-email"><?php echo esc_html($email_label); ?></label>
            <input type="email" id="simple-mailerlite-email" name="email" placeholder="<?php echo esc_attr($email_label); ?>" required>
        </div>
        
        <?php
        // Display privacy policy text if it exists
        $privacy_text = get_option('simple_mailerlite_privacy_text', '');
        if (!empty($privacy_text)) {
            echo '<div class="simple-mailerlite-privacy-policy">';
            echo wp_kses_post($privacy_text);
            echo '</div>';
        }
        ?>
        
        <div class="simple-mailerlite-field">
            <button type="submit" class="simple-mailerlite-submit"><?php echo esc_html($submit_label); ?></button>
            <span class="simple-mailerlite-spinner" style="display: none;"></span>
        </div>
    </form>
</div>