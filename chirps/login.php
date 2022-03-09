<?php 
require('config.php'); 
require_once('incl/functions.php');

require('incl/logout-parse.php');
require('incl/login-parse.php');

//doctype and visible header
require('incl/header.php');
if(isset($_GET['action'])) { $action = $_GET['action'];} 
if($_GET['action'] == 'success'){
    $feedback='Success, you can now log in!';
}
?>
<main>
    <div class="int-wrapper">
        <h2>Log In to Chirps</h2>

        <?php show_feedback( $feedback, $feedback_class, $errors ); ?>


        <form method="post" action="login.php?action=<?php echo $action; ?>">
            <label>Username <input type="text" name="username"></label>      

            <label>Password <input type="password" name="password"></label>

            <input type="submit" value="Login" class="button">
            <input type="hidden" name="did_login" value="1">
            <label><p>New User? <a href="register.php">Sign up here!</a></p></label>
            <label><p><a href="password.php">Forgot your password?</a></p></label>
        </form>

        
    </div>
    <div class="blank"></div>
</main>
<?php
if(isset($_GET['action']) AND $_GET['action'] == 'logout'){ ?>
<script>
    window.location.replace("login.php?action=")
</script>
<?php } ?>

<?php include('incl/footer.php'); ?>