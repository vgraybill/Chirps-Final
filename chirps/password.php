<?php
require('config.php');
require('vendor/PHPMailer/src/Exception.php');
require('vendor/PHPMailer/src/PHPMailer.php');
require('vendor/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;

include_once('incl/functions.php');

include('incl/header.php');

$feedback = '';
$feedback_class = 'info';
$errors = array();

if (isset($_GET['reset']) && $_GET['reset'] !== '0') {
    $reset = clean_string($_GET['reset']);

    $result = $DB->prepare(
        'SELECT reset_hash
         FROM users
         WHERE reset_hash = :reset_hash
         LIMIT 1'
    );
    $result->execute(array('reset_hash' => $reset));

    if ($result->rowCount() > 0) {
        if (isset($_POST['did_reset'])) {
            $new_password_raw = clean_string($_POST['password'] ?? '');
            $did_reset = clean_boolean($_POST['did_reset'] ?? 0);
            $valid = true;

            if ($did_reset) {
                if (strlen($new_password_raw) < PASSWORD_MIN) {
                    $valid = false;
                    $errors['password'] = 'Passwords must be at least ' . PASSWORD_MIN . ' characters long.';
                }

                if ($valid) {
                    $new_password = password_hash($new_password_raw, PASSWORD_DEFAULT);
                    $result = $DB->prepare(
                        'UPDATE users
                         SET password = :newpassword,
                             reset_hash = 0
                         WHERE reset_hash = :resethash
                         LIMIT 1'
                    );
                    $result->execute(array(
                        'newpassword' => $new_password,
                        'resethash' => $reset,
                    ));

                    if ($result->rowCount() > 0) {
                        $feedback = "Password successfully reset, you may <a href='login.php?action='>log in</a> now.";
                        $feedback_class = 'success';
                    } else {
                        $feedback = 'No changes were made. Your reset link may have already been used.';
                        $feedback_class = 'info';
                    }
                } else {
                    $feedback = 'Please fix the following:';
                    $feedback_class = 'error';
                }
            }
        }
        ?>
        <div class="int-wrapper">
            <h2>Reset Your Password</h2>
            <?php show_feedback($feedback, $feedback_class, $errors); ?>
            <form action="password.php?reset=<?php echo urlencode($reset); ?>" method="post">
                <label>New Password<input type="password" name="password"></label>
                <input type="submit" value="Submit" class="button">
                <input type="hidden" name="did_reset" value="1">
            </form>
        </div>
        <?php
    } else {
        echo '<div class="int-wrapper"><h2>Invalid or expired reset link.</h2></div>';
    }
} else {
    if (isset($_POST['did_submit'])) {
        $username = clean_string($_POST['username'] ?? '');
        $email = clean_email($_POST['email'] ?? '');
        $valid = true;

        if (strlen($username) < 1) {
            $valid = false;
            $errors['user'] = 'Username Required.';
        }
        if (strlen($email) < 1) {
            $valid = false;
            $errors['email'] = 'Email Required.';
        }

        if ($valid) {
            $result = $DB->prepare(
                'SELECT u.username, u.email
                 FROM users AS u
                 WHERE username = :username
                 AND email = :email
                 LIMIT 1'
            );
            $result->execute(array(
                'username' => $username,
                'email' => $email,
            ));

            if ($result->rowCount() > 0) {
                $reset_hash = substr(bin2hex(random_bytes(16)), 0, 15);

                $result = $DB->prepare(
                    'UPDATE users
                     SET reset_hash = :reset_hash
                     WHERE username = :username
                     AND email = :email
                     LIMIT 1'
                );
                $result->execute(array(
                    'reset_hash' => $reset_hash,
                    'username' => $username,
                    'email' => $email,
                ));

                $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') .
                    '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $reset_url = $base_url . '/password.php?reset=' . urlencode($reset_hash);

                if (MAIL_PASSWORD === '') {
                    $feedback = "Email sending is not configured on this environment yet. Use this reset link directly: <a href='$reset_url'>$reset_url</a>";
                    $feedback_class = 'info';
                } else {
                    $mailer = new PHPMailer(true);
                    try {
                        $mailer->Sender = 'password@ginnygraybill.com';
                        $mailer->addReplyTo('password@ginnygraybill.com', 'Chirps Password Recovery');
                        $mailer->setFrom('password@ginnygraybill.com', 'Chirps Password Recovery');
                        $mailer->addAddress($email);
                        $mailer->Subject = 'Your Password Reset Request';
                        $mailer->msgHTML("You requested a password reset from Chirps. Please follow this link to reset your password: <a href='$reset_url'>$reset_url</a>");

                        $mailer->isSMTP();
                        $mailer->SMTPDebug = DEBUG_MODE ? 2 : 0;
                        $mailer->SMTPAuth = true;
                        $mailer->SMTPSecure = 'ssl';
                        $mailer->Port = 465;
                        $mailer->Host = 'ginnygraybill.com';
                        $mailer->Username = 'password@ginnygraybill.com';
                        $mailer->Password = MAIL_PASSWORD;

                        $mailer->send();
                        $feedback = 'Please check your e-mail. If an account exists, a link to reset your password was sent. Please check your spam folder before resubmitting a request.';
                        $feedback_class = 'success';
                    } catch (Exception $e) {
                        $feedback = "Could not send e-mail on this environment. Use this reset link directly: <a href='$reset_url'>$reset_url</a>";
                        $feedback_class = 'error';
                        if (DEBUG_MODE) {
                            $errors[] = $e->getMessage();
                        }
                    }
                }
            } else {
                $feedback = 'Please check your e-mail. If an account exists, a link to reset your password was sent. Please check your spam folder before resubmitting a request.';
                $feedback_class = 'info';
                if (DEBUG_MODE) {
                    $errors['nomatch'] = 'No username + email match.';
                }
            }
        }

        if (!$valid) {
            $feedback = 'Please fix the following:';
            $feedback_class = 'error';
        }
    }
    ?>
    <div class="int-wrapper">
        <h2>Reset Your Password</h2>
        <?php show_feedback($feedback, $feedback_class, $errors); ?>
        <form action="password.php" method="post">
            <label>Username<input type="text" name="username"></label>
            <label>E-mail<input type="text" name="email"></label>
            <input type="submit" value="Submit" class="button">
            <input type="hidden" name="did_submit" value="1">
        </form>
    </div>
    <?php
}

include('incl/footer.php');
