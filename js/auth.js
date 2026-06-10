// тогл показа/скрытия пароля
function toggleInput(btnId, inputId) {
    var btn   = document.getElementById(btnId);
    var input = document.getElementById(inputId);

    if (!btn || !input) return;

    btn.addEventListener('click', function() {
        if (input.type === 'password') {
            input.type = 'text';
            input.placeholder = '';
        } else {
            input.type = 'password';
            input.placeholder = '··········';
        }
    });
}

toggleInput('toggle-password', 'password');
toggleInput('toggle-confirm', 'password-confirm');
