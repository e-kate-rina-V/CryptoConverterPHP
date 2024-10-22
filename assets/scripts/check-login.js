const profileLink = document.getElementById('profile-link');
const alertContainer = document.getElementById('alert-container');

console.log("User logged in:", isLoggedIn);

profileLink.addEventListener('click', function (event) {
    if (!isLoggedIn) {
        event.preventDefault();
        alertContainer.style.display = 'block';
    }
});