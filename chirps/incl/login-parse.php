<?php

if(isset($_POST['did_login']) ){
    //sanitize!
    $username = clean_string($_POST['username']);
    $password= clean_string($_POST['password']);
    //validate!
    $valid=true;
    //--username wrong length
    if( strlen($username) < USERNAME_MIN OR strlen($username) > USERNAME_MAX){
        $valid=false;
        //only show the detailed error in debug mode
        if( DEBUG_MODE){
            $errors[]= 'Username wrong length';
        }
    }
    //--password wrong length
    if( strlen($password) < PASSWORD_MIN) {
        $valid = false;
        if( DEBUG_MODE){
            $errors[]= 'Password too short';
        }
    }
    //check for combo in the DB if valid
    if($valid){
        //look up username
        $result = $DB->prepare('SELECT user_id, password
                                FROM users
                                WHERE username = ?
                                LIMIT 1');
        $result->execute( array($username));
        //if found varify the hashed password
        if($result->rowCount() >0){
            $row = $result->fetch();

            if(password_verify($password, $row['password'])){
                    //login for a week if found


                    //generate a random token
                    $hashbrowns = bin2hex(random_bytes(30));
                    //store it for this user!

                    $result = $DB->prepare('UPDATE users
                                            SET hashbrowns = :token
                                            WHERE user_id = :id
                                            LIMIT 1');
                    $result->execute( array(
                                        'token' => $hashbrowns,
                                        'id' => $row['user_id']
                    ));
                    //if worked, store cookie and session
                    if($result->rowCount() >0){
                        $expire = time() + 60 * 60 *24 * 7;
                        setcookie('hashbrowns', $hashbrowns, $expire);
                        $_SESSION['hashbrowns'] = $hashbrowns;

                        $hashed_id = password_hash($row['user_id'], PASSWORD_DEFAULT);
                        setcookie('user_id',$hashed_id , $expire);
                        $_SESSION['user_id'] = $hashed_id;

                        if( isset($_GET['action']) AND $_GET['action'] == 'make'){
                            header('Location:makepoetry.php');
                        }else {
                            header('Location:index.php');
                        }

                    } else {
                            $feedback = 'Login Failed.';
                            $feedback_class = 'error';
                    }


            } else {
                $feedback = 'Incorrect Login, Try Again';
                $feedback_class = 'error';
                if( DEBUG_MODE){
                    $errors[]= 'Password is wrong';
                }
            }
        }else{
            //nobody found with this username
            $feedback = 'Incorrect Login, Try Again';
            $feedback_class = 'error';
            if( DEBUG_MODE){
                $errors[]= 'Username not found';
            }
        }
    }else{
        //invalid form submission
        $feedback = 'Incorrect Login, Try Again';
        $feedback_class = 'error';
    }

    //handle user feedback
}