// Newsletter form handler for Netlify Functions
document.addEventListener('DOMContentLoaded', function () {
    const newsletterForms = document.querySelectorAll('form[action*="newsletter"], .newsletter-form form, form.php-email-form');

    newsletterForms.forEach(form => {
        // Skip contact form
        if (form.id === 'contactForm') return;

        // Only handle newsletter forms
        const emailInput = form.querySelector('input[name="email"]');
        if (!emailInput) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const email = formData.get('email');

            const loading = form.querySelector('.loading');
            const errorMsg = form.querySelector('.error-message');
            const sentMsg = form.querySelector('.sent-message');
            const submitBtn = form.querySelector('input[type="submit"], button[type="submit"]');

            // Hide messages
            if (errorMsg) errorMsg.style.display = 'none';
            if (sentMsg) sentMsg.style.display = 'none';
            if (loading) loading.style.display = 'block';
            if (submitBtn) submitBtn.disabled = true;

            try {
                const response = await fetch('/.netlify/functions/newsletter', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const result = await response.json();

                if (loading) loading.style.display = 'none';

                if (response.ok && result.success) {
                    if (sentMsg) sentMsg.style.display = 'block';
                    form.reset();
                } else {
                    if (errorMsg) {
                        errorMsg.textContent = result.error || 'Unable to subscribe. Please try again.';
                        errorMsg.style.display = 'block';
                    }
                }
            } catch (error) {
                if (loading) loading.style.display = 'none';
                if (errorMsg) {
                    errorMsg.textContent = 'Network error. Please try again.';
                    errorMsg.style.display = 'block';
                }
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    });
});
