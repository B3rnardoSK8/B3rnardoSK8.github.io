document.addEventListener('DOMContentLoaded', () => {
    // Floating labels: ensure placeholder exists so :placeholder-shown works
    document.querySelectorAll('.input-wrapper input').forEach(input => {
        if (!input.getAttribute('placeholder')) input.setAttribute('placeholder', ' ');
        input.addEventListener('focus', () => input.classList.add('has-focus'));
        input.addEventListener('blur', () => input.classList.remove('has-focus'));
    });

    // Password toggles: support multiple toggles (register has two)
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const wrapper = toggle.closest('.password-wrapper');
            const input = wrapper?.querySelector('input[type="password"], input[type="text"]');
            const eye = toggle.querySelector('.eye-icon');
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                eye?.classList.add('show-password');
            } else {
                input.type = 'password';
                eye?.classList.remove('show-password');
            }
        });
    });

    // Do not prevent normal form submission; server will handle authentication.
});

// Prevent video from stealing focus on some browsers
document.addEventListener('play', (e) => {
    if (e.target && e.target.id === 'auth-bg-video') e.target.setAttribute('aria-hidden','true');
}, true);
