<?php 
require('config.php'); 
require_once('incl/functions.php');

//register form parser 
require('incl/register-parse.php');

//doctype and visible header
require('incl/header.php');
?>
<main>
    <div class="int-wrapper">
        <h2>Create an Account</h2>

        <?php show_feedback( $feedback, $feedback_class, $errors ); ?>

        <form method="post" action="register.php">
            <label>Username <input type="text" name="username"></label>
            

            <label>Email Address <input type="email" name="email"></label>
            

            <label>Password <input type="password" name="password"></label>
            

            <label>
                <input type="checkbox" name="policy" value="1">
                I agree to the <a href="tos.php" target="_blank">Terms of Service</a>
            </label>

            <input type="submit" value="Sign Up" class="button">
            <input type="hidden" name="did_register" value="1">
        </form>
    </div>
    <div class="blank register"></div>
</main>

<?php include('incl/footer.php'); ?>