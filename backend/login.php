<?php
require_once __DIR__ . '/src/helpers.php';

checkGuest();
?>

<?php
$rememberedName = '';

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $pdo = getPDO();
    $query = "SELECT `name` FROM `users` WHERE `remember_token` = :token";
    $statement = $pdo->prepare($query);
    $statement->execute(['token' => $token]);
    $user = $statement->fetch();

    if ($user) {
        $rememberedName = $user['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php include_once __DIR__ . '/head.php' ?>

<title>Log in</title>

<body>
    <form id="login-form" class="card" action="src/actions/login.php" method="post">
        <h2>Log In</h2>

        <?php if (hasMessage('error')): ?>
            <div class="notice error"><?php echo getMessage('error') ?></div>
        <?php endif; ?>

        <label for="name">
            Username
            <input
                type="text"
                id="name"
                name="name"
                placeholder="hansen.arnoldo"
                value="<?php echo htmlspecialchars($rememberedName); ?>"
                <?php echo validationErrorAttr('name'); ?>>
            <?php if (hasValidationError('name')): ?>
                <small><?php echo validationErrorMessage('name'); ?></small>
            <?php endif; ?>
            <span class="error-message" id="nameError"></span>
        </label>

        <label for="password">
            Password
            <input
                type="password"
                id="password"
                name="password"
                placeholder="******">
            <span class="error-message" id="passwordError"></span>
        </label>

        <label>
            <input type="checkbox" name="remember_me" <?php echo isset($_COOKIE['remember_name']) ? 'checked' : ''; ?>> Remember me
        </label>

        <button type="submit" id="submit">Continue</button>
    </form>

    <p>I don't have an <a href="./register.php">account</a> yet</p>

    <script src="../assets/scripts/validation-login.js"></script>
    <script src="../assets/scripts/app.js"></script>

</body>

</html>