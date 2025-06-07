// Contact Form Submission
document.getElementById('contact-form').addEventListener('submit', function(e) {
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
    
    // Send data to PHP
    fetch('php/send_email.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Show success message
        messageDiv.textContent = '✓ Message sent successfully! We will contact you soon.';
        messageDiv.className = 'form-message success';
        messageDiv.classList.remove('hidden');
        
        // Reset form
        form.reset();
    })
    .catch(error => {
        // Show error message
        messageDiv.textContent = '✗ Error sending message. Please try again.';
        messageDiv.className = 'form-message error';
        messageDiv.classList.remove('hidden');
    })
    .finally(() => {
        // Reset button state
        submitText.textContent = 'Send Message';
        spinner.classList.add('hidden');
        form.querySelector('button').disabled = false;
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Hide message after 5 seconds
        setTimeout(() => {
            messageDiv.classList.add('hidden');
        }, 5000);
    });
});