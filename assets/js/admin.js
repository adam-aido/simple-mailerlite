/**
 * Simple MailerLite admin functionality
 */
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const showNameCheckbox = document.getElementById('simple_mailerlite_show_name');
        const nameLabelField = document.getElementById('simple_mailerlite_name_label');
        const nameLabelRow = nameLabelField ? nameLabelField.closest('tr') : null;
        
        if (showNameCheckbox && nameLabelRow) {
            // Initial state
            updateNameLabelVisibility();
            
            // Add event listener for the checkbox
            showNameCheckbox.addEventListener('change', updateNameLabelVisibility);
        }
        
        /**
         * Update the visibility of the name label field based on the show name checkbox
         */
        function updateNameLabelVisibility() {
            if (showNameCheckbox.checked) {
                nameLabelRow.style.display = 'table-row';
            } else {
                nameLabelRow.style.display = 'none';
            }
        }
    });
})();