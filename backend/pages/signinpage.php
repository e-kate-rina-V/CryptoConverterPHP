<?php
include_once __DIR__ . '/../../assets/layout/head.php';
require_once __DIR__ . '/../src/helpers.php';

require_once __DIR__ . '/../src/actions/greeting_classes/Greeting.php';

$isLoggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<title>Welcome page</title>

<body>
    <div class="welcome-text">
        <h1 id="welcome-text"><?php $greeting->greeting(); ?></h1>
        <a href="../index.php" id="profile-link">Profile</a>

        <div id="alert-container">
            Please <a href="login.php">log in</a> or <a href="register.php">register</a> to access your profile.
        </div>
    </div>

    <script>
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    </script>

    <script src="../../assets/scripts/check-login.js"></script>
</body>

</html>