document.getElementById('emailForm').addEventListener('submit', function(event) {
    var emailField = document.getElementById('email');
    var emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
    var errorContainer = document.getElementById('errorContainer');

    if (!emailPattern.test(emailField.value)) {
        errorContainer.textContent = 'Por favor, ingresa un correo electronico valido';
        event.preventDefault();
    } else {
        errorContainer.textContent = '';
    }
});