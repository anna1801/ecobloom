// Form Answer check for Math Captcha
let expectedCaptchaAnswer = 0;

function generateCaptcha() {
    const num1 = Math.floor(Math.random() * 10) + 1; // 1 to 10
    const num2 = Math.floor(Math.random() * 10) + 1; // 1 to 10
    expectedCaptchaAnswer = num1 + num2;
    
    const operator = '+';
    document.getElementById('captchaQuestion').textContent = `${num1} ${operator} ${num2} =`;
    document.getElementById('captchaAnswer').value = '';
}


// Initialize captcha when page loads
/*
document.addEventListener('DOMContentLoaded', generateCaptcha);

function submitContactForm(e) {
    e.preventDefault();
    const btn = document.getElementById('contactSubmitBtn');

    // Check Math Captcha
    const userAnswer = parseInt(document.getElementById('captchaAnswer').value, 10);
    if (isNaN(userAnswer) || userAnswer !== expectedCaptchaAnswer) {
        alert('Security check failed. Please answer the math question correctly.');
        generateCaptcha(); // regenerate on fail
        return false;
    }

    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    btn.disabled = true;

    // Simulate submission (replace with real backend POST)
    setTimeout(() => {
        document.getElementById('contactForm').innerHTML = `
            <div class="text-center py-5">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2.5rem;color:#059669;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Message Sent!</h4>
                <p class="text-muted mb-4">Thank you for contacting EcoBloom. Our care team will respond within 12 hours.</p>
                <a href="index.html" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">Back to Home</a>
            </div>
        `;
    }, 1200);

    return false;
}
*/


document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('contactForm');

    if (!form) {
        return;
    }

    const captchaQuestion = document.getElementById('captchaQuestion');
    const captchaAnswer = document.getElementById('captchaAnswer');
    const btn = document.getElementById('contactSubmitBtn');

    let expectedCaptchaAnswer;

    function generateCaptcha() {
        const num1 = Math.floor(Math.random() * 9) + 1;
        const num2 = Math.floor(Math.random() * 9) + 1;

        expectedCaptchaAnswer = num1 + num2;

        captchaQuestion.textContent = num1 + ' + ' + num2 + ' =';
        captchaAnswer.value = '';

        captchaAnswer.setAttribute('aria-invalid', 'false');

        const wrapper = captchaAnswer.closest('.wpcf7-form-control-wrap');
        if (wrapper) {
            const error = wrapper.querySelector('.wpcf7-not-valid-tip');
            if (error) {
                error.remove();
            }
        }
    }

    generateCaptcha();

    form.addEventListener('submit', function (e) {

        const userAnswer = parseInt(captchaAnswer.value, 10);

        if (isNaN(userAnswer) || userAnswer !== expectedCaptchaAnswer) {

            // Stop normal browser submission
            //e.preventDefault();

            // Stop Contact Form 7 from receiving the submit event
            //e.stopImmediatePropagation();

            captchaAnswer.setAttribute('aria-invalid', 'true');
            captchaAnswer.classList.add('wpcf7-not-valid');

            const wrapper = captchaAnswer.closest('.wpcf7-form-control-wrap');

            if (wrapper) {
                const existingError = wrapper.querySelector('.wpcf7-not-valid-tip');

                if (existingError) {
                    existingError.remove();
                }

                const errorMessage = document.createElement('span');
                errorMessage.className = 'wpcf7-not-valid-tip';
                errorMessage.setAttribute('aria-hidden', 'true');
                errorMessage.textContent = 'Please answer the security question correctly.';

                wrapper.appendChild(errorMessage);
            }

            captchaAnswer.focus();

            generateCaptcha();

            return false;
        }

        captchaAnswer.setAttribute('aria-invalid', 'false');
        captchaAnswer.classList.remove('wpcf7-not-valid');

        const wrapper = captchaAnswer.closest('.wpcf7-form-control-wrap');

        if (wrapper) {
            const error = wrapper.querySelector('.wpcf7-not-valid-tip');

            if (error) {
                error.remove();
            }
        }

    }, true); 

    document.addEventListener('wpcf7mailsent', function (event) {

        if (event.detail.contactFormId != 456) {
            return;
        }

        form.innerHTML = `
            <div class="text-center py-5">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2.5rem;color:#059669;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Message Sent!</h4>
                <p class="text-muted mb-4">Thank you for contacting EcoBloom. Our care team will respond within 12 hours.</p>
                <a href="index.html" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">Back to Home</a>
            </div>
        `;

    }, false);

});