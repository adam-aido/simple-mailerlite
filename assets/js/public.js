/**
 * Simple MailerLite form handling
 */
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('simple-mailerlite-form');
        
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
    });
    
    /**
     * Handle form submission
     * 
     * @param {Event} event The form submit event
     */
    function handleFormSubmit(event) {
        event.preventDefault();
        
        // Get the form
        const form = event.target;
        
        // Get form data
        const email = form.querySelector('[name="email"]').value.trim();
        const nameField = form.querySelector('[name="name"]');
        const name = nameField ? nameField.value.trim() : '';
        
        // Get message container
        const messageContainer = form.querySelector('.simple-mailerlite-message');
        
        // Get spinner
        const spinner = form.querySelector('.simple-mailerlite-spinner');
        
        // Validate email (additional client-side validation)
        if (!isValidEmail(email)) {
            showMessage(messageContainer, 'Please enter a valid email address.', 'error');
            return;
        }
        
        // Disable submit button and show spinner
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        spinner.style.display = 'inline-block';
        
        // Prepare form data
        const formData = new FormData();
        formData.append('action', 'simple_mailerlite_subscribe');
        formData.append('nonce', simple_mailerlite_obj.nonce);
        formData.append('email', email);
        
        if (name) {
            formData.append('name', name);
        }
        
        // Send AJAX request
        fetch(simple_mailerlite_obj.ajax_url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Hide spinner
            spinner.style.display = 'none';
            
            if (data.success) {
                // Clear form
                form.reset();
                
                // Show success message
                showMessage(messageContainer, data.data.message, 'success');
            } else {
                // Show error message
                showMessage(messageContainer, data.data.message, 'error');
                
                // Re-enable submit button
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            // Hide spinner
            spinner.style.display = 'none';
            
            // Show error message
            showMessage(messageContainer, simple_mailerlite_obj.error_message, 'error');
            
            // Re-enable submit button
            submitButton.disabled = false;
            
            // Log error for debugging
            console.error('Error:', error);
        });
    }
    
    /**
     * Show a message in the container
     * 
     * @param {HTMLElement} container The message container element
     * @param {string} message        The message to display
     * @param {string} type           The message type (success or error)
     */
    function showMessage(container, message, type) {
        // Set message content
        container.textContent = message;
        
        // Set message type class
        container.className = 'simple-mailerlite-message';
        container.classList.add('simple-mailerlite-' + type);
        
        // Show message
        container.style.display = 'block';
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(function() {
                container.style.display = 'none';
            }, 5000);
        }
    }
    
    /**
     * Validate email format
     * 
     * @param {string} email The email to validate
     * @return {boolean} True if valid, false otherwise
     */
    function isValidEmail(email) {
        const regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        return regex.test(email);
    }
})();