<?php
    require('config.php');
    // Include the PHPMailer library
    require('vendor/PHPMailer/src/Exception.php');
    require('vendor/PHPMailer/src/PHPMailer.php');
    require('vendor/PHPMailer/src/SMTP.php');
    use PHPMailer\PHPMailer\PHPMailer;

    include_once('incl/functions.php');

    include('incl/header.php');

    if(isset($_REQUEST['reset']) AND $_REQUEST['reset'] != '0'){
        $reset=clean_string($_REQUEST['reset']);

        $result = $DB->prepare(
            'SELECT reset_hash
            FROM users
            WHERE reset_hash = :reset_hash
            ');
        $result->execute(array('reset_hash'=>$reset));
        if($result->rowCount()>0){ ?>
    <div class="int-wrapper">
        <h2>Reset Your Password</h2>

<?php       $new_password=clean_string($_REQUEST['password']);
            $new_password=password_hash($new_password, PASSWORD_DEFAULT);
            $did_reset=clean_boolean($_REQUEST['did_reset']);
            $valid = true;
            if($did_reset){

                if(strlen($new_password)<PASSWORD_MIN){
                    $valid=false;
                    $errors['password']= 'Passwords must be at least'.PASSWORD_MIN.'characters long.';
                }
            

                if($valid){
                $result=$DB->prepare(
                    'UPDATE users
                    SET 
                    password = :newpassword,
                    reset_hash = 0
                    WHERE reset_hash = :resethash
                    ');
                $result->execute(array(
                    'newpassword'=>$new_password,
                    'resethash'=>$reset
                ));
                if($result->rowCount()>0){
                    $feedback="Password successfully reset, you may <a href='login.php?action='>log in</a> now.";
                }
            }
            } ?>

            <form action="password.php?reset=<?php echo $reset; ?>" method="post">
                <label>New Password<input type="password" name="password"></label>
                <input type=submit value="Submit" class="button">
                <input type="hidden" name="did_reset" value="1">
                <label><?php echo show_feedback($feedback,$feedback_class,$errors); ?></label>
            </form>
        </div>

        <?php

            //update to database where hash matches posted hash

            //update password to new password and reset_hash to 0
        }
        else {
            echo 'Error, hash not found';
            //no reset_hash attached to user, change to header location after testing
        }
    }
    else {         
        if (isset($_REQUEST['did_submit'])){

        $username = clean_string($_REQUEST['username']);
        $email = clean_email($_REQUEST['email']);
        $valid=true;
        if (strlen($username) < 1){
            $valid=false;
            $errors['user'] = 'Username Required.';
        }
        if (strlen($email) < 1){
            $valid=false;
            $errors['email'] = 'Email Required.';
        }

        if($valid){
        $result = $DB->prepare(
            'SELECT u.username, u.email 
            FROM users AS u
            WHERE username = :username
            AND email = :email
            LIMIT 1
            ');
        $result->execute(array(
            'username'=>$username,
            'email'=>$email
        ));
        $mailer = new PHPMailer(true);

        if($result->rowCount()>0){
            //make a reset hash
            $reset_hash = password_hash($username, PASSWORD_DEFAULT);
            $reset_hash = substr($reset_hash, 10, 10);
            //set reset hash in DB
            $result = $DB->prepare(
                'UPDATE users
                SET reset_hash = :reset_hash
                WHERE username = :username
                AND email = :email
                LIMIT 1
                ');
                $result->execute(array(
                    'reset_hash' => $reset_hash,
                    'username' => $username,
                    'email' => $email
                ));
            //send email to email with reset link
            try{
                // Set up to, from, and the message body.  The body doesn't have to be HTML; check the PHPMailer documentation for details.
                $mailer->Sender = $email;
                $mailer->AddReplyTo($email, $username);
                $mailer->SetFrom($email, 'Chirps Password Recovery');
                $mailer->AddAddress($email);
                $mailer->Subject = 'Your Password Reset Request';
                $mailer->MsgHTML("You requested a password reset from Chirps. Please follow this link to reset your password: 
                https://chirps.ginnygraybill.com/password.php?reset=".$reset_hash."
                ");
            
                // Set up our connection information.
                $mailer->IsSMTP();
                //show report when done
                $mailer->SMTPDebug = DEBUG_MODE; 
                $mailer->SMTPAuth = true;
                $mailer->SMTPSecure = 'ssl';
                $mailer->Port = 465;
                $mailer->Host = 'ginnygraybill.com';
                //Username to use for SMTP authentication - use full email address for gmail
                $mailer->Username = 'password@ginnygraybill.com';
            
                //Your gmail account password goes here
                $mailer->Password = '';
            
                // All done! send the mail and make sure it worked
                if( $mailer->Send() ){
                    //success
                    $message = 'Thank you for contacting me.';
                    $class = 'success';
                }
            }
            catch(phpmailerException $e){
                /*phpmailer exception*/
                $message = 'Sorry, the server could not send your message at this time.';
                $class = 'error';
                $errors[] =  $e->errorMessage();
            }
            catch(Exception $e) {
                $message = 'The mail could not send';
                $class = 'error';
                $errors[] = $e->getMessage(); 
            }



            $feedback = "Please check your e-mail. If an account exists, a link to reset your password was sent. Please check your spam folder before resubmitting a request.";
        } else {
            $feedback = "Please check your e-mail. If an account exists, a link to reset your password was sent. Please check your spam folder before resubmitting a request.";
            if(DEBUG_MODE){
                $errors['nomatch'] = "no match.";
            }
        }

        } else {
            $feedback = "";
        }
    }
         ?>
    <div class="int-wrapper">
        <h2>Reset Your Password</h2>
        <form action="password.php" method="post">
            <label>Username<input type="text" name="username"></label>
            <label>E-mail<input type="text" name="email"></label>
            <input type=submit value="Submit" class="button">
            <input type="hidden" name="did_submit" value="1">
            <label><?php echo show_feedback($feedback,$feedback_class,$errors); ?></label>
        </form>
    </div>
    <?php 

        //if match, send an email to db email assosiated with username with a newly hashed reset_hash
        //set reset_hash to new reset_hash
        //redirect to 'check email' message
     }

    ?>



<?php include('incl/footer.php') ?>