<title>Registration</title>

<?php
require_once __DIR__ . '/src/helpers.php';
checkGuest();
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php include_once __DIR__ . '/head.php' ?>

<body>
    <form id="register-form" class="card" action="src/actions/register.php" method="post" enctype="multipart/form-data">
        <h2>Registration</h2>

        <label for="name">
            Username
            <input
                type="text"
                id="name"
                name="name"
                placeholder="hansen.arnoldo"
                value="<?php echo old('name') ?>"
                <?php echo validationErrorAttr('name'); ?>>
            <?php if (hasValidationError('name')): ?>
                <small><?php echo validationErrorMessage('name'); ?></small>
            <?php endif; ?>
            <span class="error-message" id="nameError"></span>
        </label>

        <label for="email">
            E-mail
            <input
                type="email"
                id="email"
                name="email"
                placeholder="hansen.arnoldo@outlook.com"
                value="<?php echo old('email') ?>"
                <?php echo validationErrorAttr('email'); ?>>
            <?php if (hasValidationError('email')): ?>
                <small><?php echo validationErrorMessage('email'); ?></small>
            <?php endif; ?>
            <span class="error-message" id="emailError"></span>
        </label>

        <label for="date">
            Birthday
            <input
                type="date"
                id="date"
                name="date"
                value="<?php echo old('date') ?>"
                <?php echo validationErrorAttr('date'); ?>>
            <?php if (hasValidationError('date')): ?>
                <small><?php echo validationErrorMessage('date'); ?></small>
            <?php endif; ?>
            <span class="error-message" id="dateError"></span>
        </label>

        <label for="sex">Sex</label>
        <div>
            <input
                type="radio"
                id="male"
                name="sex"
                value="male"
                <?php echo old('sex') === 'male' ? 'checked' : ''; ?>
                <?php echo validationErrorAttr('sex'); ?>>
            <label for="male">Male</label>

            <input
                type="radio"
                id="female"
                name="sex"
                value="female"
                <?php echo old('sex') === 'female' ? 'checked' : ''; ?>
                <?php echo validationErrorAttr('sex'); ?>>
            <label for="female">Female</label>
            <span class="error-message" id="sexError"></span>
        </div>
        <?php if (hasValidationError('sex')): ?>
            <small class="error-message"><?php echo validationErrorMessage('sex'); ?></small>
        <?php endif; ?>

        <label for="avatar">
            Profile image
            <input
                type="file"
                id="avatar"
                name="avatar"
                <?php echo validationErrorAttr('avatar'); ?>>
        </label>
        <?php if (old('avatar')): ?>
            <p>Uploaded file: <?php echo old('avatar'); ?></p>
        <?php endif; ?>
        <?php if (hasValidationError('avatar')): ?>
            <small class="error-message"><?php echo validationErrorMessage('avatar'); ?></small>
        <?php endif; ?>
        <span class="error-message" id="avatarError"></span>

        <div class="grid">
            <label for="password">
                Password
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="******"
                    <?php echo validationErrorAttr('password'); ?>>
                <?php if (hasValidationError('password')): ?>
                    <small><?php echo validationErrorMessage('password'); ?></small>
                <?php endif; ?>
                <span class="error-message" id="passwordError"></span>
            </label>

            <label for="password_confirmation">
                Confirm password
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="******">
            </label>
        </div>

        <fieldset>
            <label for="terms">
                <input
                    type="checkbox"
                    id="terms"
                    name="terms">
                I accept all terms of use
            </label>
        </fieldset>

        <button
            type="submit"
            id="submit">Continue</button>
    </form>

    <p>I already have an <a href="./login.php">account</a></p>

    <script src="../assets/scripts/validation-register.js"></script>

</body>

</html>