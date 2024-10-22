<?php
require_once __DIR__ . '/src/helpers.php';

checkAuth();

$user = currentUser();

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php include_once __DIR__ . '/head.php' ?>

<div id="back-section">
    <a href="index.php">◀</a>
</div>

<header>
    <div class="info">
        Profile information
    </div>
</header>

<body>
    <div class="profile-info">
        <section>
            <div class="change-info">
                <div id="change-info">
                    <p>Username: <?php echo $user['name'] ?></p>
                    <a href="#" onclick="showForm('changeNameForm')">Change</a>
                    <form id="changeNameForm" class ="change-form" style="display: <?php echo hasValidationError('name') ? 'block' : 'none'; ?>;" method="POST" action="./db/change_profile.php">
                        <div class="form">
                            <input type="text" id="name" name="new_name" placeholder="Enter new username" required <?php echo validationErrorAttr('name'); ?>>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit">Save</button>
                            <button id="cancel-btn" type="button" onclick="hideForm('changeNameForm')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php if (hasValidationError('name')): ?>
                <p class="error-message"><?php echo validationErrorMessage('name'); ?></p>
            <?php endif; ?>
        </section>
        <section>
            <div class="change-info">
                <div id="change-info">
                    <p>Email: <?php echo $user['email'] ?></p>
                    <a href="#" onclick="showForm('changeEmailForm')">Change</a>
                    <form id="changeEmailForm" class ="change-form" style="display: <?php echo hasValidationError('email') ? 'block' : 'none'; ?>;" method="POST" action="./db/change_profile.php">
                        <div class="form">
                            <input type="email" id="email" name="new_email" placeholder="Enter new email" required <?php echo validationErrorAttr('name'); ?>>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit">Save</button>
                            <button id="cancel-btn" type="button" onclick="hideForm('changeEmailForm')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php if (hasValidationError('email')): ?>
                <p class="error-message"><?php echo validationErrorMessage('email'); ?></p>
            <?php endif; ?>
        </section>

        <section>
            <form id="log-out-btn" action="src/actions/logout.php" method="post">
                <button type="submit" name="logout">Log out</button>
            </form>
        </section>

        <section id="delete-zone">
            <section id="chng-pass-section">
                <div class="change-info">
                    <div id="change-info">
                        <a id="change-password" href="#" onclick="showForm('changePasswordForm')">Change password</a>
                        <form id="changePasswordForm" class ="change-form" style="display: <?php echo hasValidationError('password') ? 'block' : 'none'; ?>;" method="POST" action="./db/change_profile.php">
                            <div class="form-pass">
                                <input type="password" id="password" name="password" placeholder="Your current password" required <?php echo validationErrorAttr('password'); ?>>
                                <input type="password" id="new_password" name="new_password" placeholder="New password" required <?php echo validationErrorAttr('new_password'); ?>>
                                <input type="password" id="new_password_confirmation" name="confirm_password" placeholder="Confirm password" required <?php echo validationErrorAttr('confirm_password'); ?>>
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit">Save</button>
                                <button id="cancel-btn" type="button" onclick="hideForm('changePasswordForm')">Cancel</button>
                            </div>
                        </form>
                        <?php if (hasValidationError('password')): ?>
                            <p class="error-message"><?php echo validationErrorMessage('password'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <section>
                <a id="delete-account" href="#" onclick="showForm('deleteAccountForm')">Delete an account</a>
                <form id="deleteAccountForm" method="POST" style="display: none;" action="./db/change_profile.php">
                    <h3>Delete account ?</h3>
                    <div class="form-del">
                        <input id="check" type="checkbox" name="delete_confirm" value="1"> Yes
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button id="del-submit" type="submit">Save</button>
                        <button id="cancel-btn" type="button" onclick="hideForm('deleteAccountForm')">Cancel</button>
                    </div>
                </form>
            </section>
        </section>
        <script src="../assets/scripts/profile-form.js"></script>
    </div>
</body>

</html>