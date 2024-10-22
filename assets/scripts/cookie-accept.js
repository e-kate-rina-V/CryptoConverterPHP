document.getElementById('accept-cookies').addEventListener('click', function () {

    document.cookie = "cookies_accepted=true; path=/; max-age=" + (30 * 24 * 60 * 60);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'set_cookie_session.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('cookies_accepted=true');

    document.getElementById('cookie-consent').style.display = 'none';
});