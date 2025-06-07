document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const messageDiv = document.getElementById('form-message');
            const submitText = document.getElementById('submit-text');
            const spinner = document.getElementById('spinner');
            
            // Show loading state
            submitText.textContent = 'Sending...';
            spinner.classList.remove('hidden');
            form.querySelector('button').disabled = true;
            
            // Clear previous messages
            messageDiv.classList.add('hidden');
            
            // Send data to PHP
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Show message
                messageDiv.textContent = data.message;
                messageDiv.className = `form-message ${data.success ? 'success' : 'error'}`;
                messageDiv.classList.remove('hidden');
                
                // Reset form if successful
                if (data.success) {
                    form.reset();
                }
            })
            .catch(error => {
                // Show error message
                messageDiv.textContent = 'Error: ' + error.message;
                messageDiv.className = 'form-message error';
                messageDiv.classList.remove('hidden');
            })
            .finally(() => {
                // Reset button state
                submitText.textContent = 'Send Message';
                spinner.classList.add('hidden');
                form.querySelector('button').disabled = false;
                
                // Scroll to message
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDiv.classList.add('hidden');
                }, 5000);
            });
        });
    }
});