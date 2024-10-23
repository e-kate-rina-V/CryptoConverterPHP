<?php
require_once __DIR__ . '/src/helpers.php';

checkAuth();

$user = currentUser();

if (!isset($_SESSION['cookies_accepted']) && isset($_COOKIE['cookies_accepted'])) {
    $_SESSION['cookies_accepted'] = true;
}

$cookiesAccepted = isset($_SESSION['cookies_accepted']) ? true : false;
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
<link rel="stylesheet" href="../assets/layout/app.css">

<header id="main-header">
    <a href="pages/converter.php">Converter</a>
    <a href="pages/profileinfo.php">Profile Info</a>
    <section class="avatar-section">
        <img class="avatar" src="<?php echo $user['avatar']; ?>">
        <h6>Welcome, <?php echo $user['name'] ?>!</h6>
    </section>
</header>

<body>
    <?php if (!$cookiesAccepted): ?>
        <div id="cookie-consent">
            <p>We use cookies to improve your experience on our site. By using this site, you accept our cookie policy.</p>
            <button id="accept-cookies">Accept</button>
        </div>
    <?php endif; ?>

    <script src="../assets/scripts/app.js"></script>
</body>

<footer>
    <img id="crypto-logos" src="../assets/img/crypto_logos.png" />
</footer>

<script src="../assets/scripts/cookie-accept.js"></script>

</html>