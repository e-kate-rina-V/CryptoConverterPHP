document.getElementById('register-form').addEventListener('submit', function (event) {
    let valid = true;

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const date = document.getElementById('date').value.trim();
    const sex = document.querySelector('input[name="sex"]:checked');
    const avatar = document.getElementById('avatar').value.trim();
    const password = document.getElementById('password').value.trim();
    const passwordConfirmation = document.getElementById('password_confirmation').value.trim();
    const terms = document.getElementById('terms').checked;

    document.getElementById('nameError').innerText = '';
    document.getElementById('emailError').innerText = '';
    document.getElementById('dateError').innerText = '';
    document.getElementById('sexError').innerText = '';
    document.getElementById('avatarError').innerText = '';
    document.getElementById('passwordError').innerText = '';


    if (name === '') {
        document.getElementById('nameError').innerText = 'Please, enter your name.';
        valid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '' || !emailPattern.test(email)) {
        document.getElementById('emailError').innerText = 'Please enter a valid email address';
        valid = false;
    }

    if (date === '') {
        document.getElementById('dateError').innerText = 'Please, enter your birthday date.';
        valid = false;
    }

    if (!sex) {
        document.getElementById('sexError').innerText = 'Please, select your gender.';
        valid = false;
    }

    if (avatar === '') {
        document.getElementById('avatarError').innerText = 'Please, select an avatar.';
        valid = false;
    }

    if (password === '') {
        document.getElementById('passwordError').innerText = 'Please, enter your password.';
        valid = false;
    } else if (password.length < 8) {
        document.getElementById('passwordError').innerText = 'The password must contain at least 8 characters';
    } else if (!/[a-zA-Z]/.test(password) || !/[0-9]/.test(password)) {
        document.getElementById('passwordError').innerText = 'Password must contain at least one letter and one number.';
        valid = false;
    } else if (password !== passwordConfirmation) {
        document.getElementById('passwordError').innerText = 'Passwords do not match';
    }

    if (!terms) {
        alert('You must accept the terms of use.');
        valid = false;
    }

    if (!valid) {

        event.preventDefault();
    }
});