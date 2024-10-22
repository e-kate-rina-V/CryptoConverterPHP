document.getElementById('login-form').addEventListener('submit', function (event) {
    let valid = true;

    const name = document.getElementById('name').value.trim();
    const password = document.getElementById('password').value.trim();


    document.getElementById('nameError').innerText = '';
    document.getElementById('passwordError').innerText = '';

    if (name === '') {
        document.getElementById('nameError').innerText = 'Please, enter your name.';
        valid = false;
    }

    if (password === '') {
        document.getElementById('passwordError').innerText = 'Please, enter your password.';
        valid = false;
    }

    if (!valid) {
        event.preventDefault();
    }
});