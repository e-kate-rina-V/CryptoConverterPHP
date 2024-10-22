<?php
if (isset($_POST['cookies_accepted']) && $_POST['cookies_accepted'] === 'true') {
    $_SESSION['cookies_accepted'] = true;
}
