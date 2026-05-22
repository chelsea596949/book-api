$(document).ready(function() {
    $('#register-form').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $errorBox = $('#error-message');
        const $registerBtn = $('#register-btn');
        const $spinner = $('#btn-spinner');

        // Clear and hide error messages
        $errorBox.addClass('d-none').empty();
        
        // Button loading state
        $registerBtn.prop('disabled', true);
        $spinner.removeClass('d-none');

        // Get form data
        const formData = $form.serialize();

        // Call registration API
        ApiService.register(formData)
            .done(function(response) {
                // Registration success
                if(response.status === 'success') {
                    // Redirect to login page
                    window.location.href = '/users/login';
                }
            })
            .fail(function(xhr) {
                // Registration failure
                const response = xhr.responseJSON;
                let message = 'Registration failed. Please try again.';

                if(response && response.messages) {
                    // If backend returns validation error array
                    if(typeof response.messages === 'object') {
                        message = Object.values(response.messages).join('<br>');
                    } else {
                        message = response.messages;
                    }
                } else if (response && response.error) {
                    message = response.error;
                }

                $errorBox.html(message).removeClass('d-none');
            })
            .always(function() {
                // Restore button state
                $registerBtn.prop('disabled', false);
                $spinner.addClass('d-none');
            });
    });
});
