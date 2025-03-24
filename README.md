# Simple MailerLite Integration

A lightweight WordPress plugin for easy integration with MailerLite newsletter service.

## Description

Simple MailerLite Integration is a minimalist, user-friendly WordPress plugin designed to help you grow your email list through MailerLite. With a clean, well-designed subscription form and straightforward admin settings, this plugin focuses on doing one thing well - connecting your WordPress site to MailerLite with minimal overhead.

## Features

- **Easy Setup**: Connect to MailerLite with just your API key and group ID
- **Customizable Form**: Show/hide name field, customize field labels and button text
- **Privacy Policy**: Add custom privacy policy text with HTML support
- **Success/Error Messages**: Customize messages shown to subscribers
- **Lightweight**: No bloat, minimal impact on site performance
- **Responsive Design**: Looks great on all devices
- **Shortcode Support**: Easily add the form anywhere with `[simple_mailerlite]`

## Installation

1. Upload the `simple-mailerlite` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Simple MailerLite to configure your settings

## Configuration

1. **API Settings**:
   - Enter your MailerLite API key (found in your MailerLite account under Integrations > API)
   - Enter the Group ID for the subscriber group you want to add users to

2. **Form Settings**:
   - Choose whether to show the name field
   - Customize field labels and button text
   - Add optional privacy policy text (supports HTML for links)

3. **Message Settings**:
   - Customize success and error messages

## Usage

Use the shortcode `[simple_mailerlite]` to display the subscription form anywhere on your site:

- In posts or pages: Simply add the shortcode in the content editor
- In widgets: Add a Shortcode widget and insert the shortcode
- In theme files: Use `<?php echo do_shortcode('[simple_mailerlite]'); ?>`

## Styling

The plugin comes with clean, minimal styling that works with most themes. For custom styling, you can add your own CSS to target the form elements:

```css
.simple-mailerlite-form-container { /* Form container */ }
.simple-mailerlite-form { /* Form element */ }
.simple-mailerlite-field { /* Field wrapper */ }
.simple-mailerlite-field label { /* Field labels */ }
.simple-mailerlite-field input { /* Text inputs */ }
.simple-mailerlite-submit { /* Submit button */ }
.simple-mailerlite-privacy-policy { /* Privacy policy text */ }
.simple-mailerlite-message { /* Message container */ }
.simple-mailerlite-success { /* Success message */ }
.simple-mailerlite-error { /* Error message */ }
```

## Development

This plugin is open source and contributions are welcome! The code is structured for readability and maintainability:

- **Plugin Core**: `includes/class-simple-mailerlite.php`
- **Admin Interface**: `includes/class-simple-mailerlite-admin.php`
- **Subscriber Logic**: `includes/class-simple-mailerlite-subscriber.php`
- **Templates**: `templates/form.php` and `templates/admin-settings.php`
- **CSS/JS**: Separate files in the `assets` directory

## License

This plugin is released under the Unlicense. It is free and unencumbered software released into the public domain. For more details, see the LICENSE file.

## Support

For support or feature requests, please use the plugin's GitHub issues page.
