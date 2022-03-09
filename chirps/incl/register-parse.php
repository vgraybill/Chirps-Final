<?php

clean_boolean($_POST['did_register']);

if(  $_POST['did_register']  ){
    //clean
    $username = clean_string($_POST['username']);
    $email = clean_email($_POST['email']);
    $password = clean_string($_POST['password']);
    $policy = clean_boolean($_POST['policy']);
    //verify
    $valid = true;
    if(strlen($username) > USERNAME_MAX OR strlen($username) < USERNAME_MIN){
        $valid=false;
        $errors['username']= 'Please choose a username between 3 and 30 characters';
    }
    else {
        //check to see if username is taken
        $query = 'SELECT username 
					FROM users 
					WHERE username = :username 
					LIMIT 1';
		$result = $DB->prepare($query);
		$result->execute( array( 'username' => $username ) );
		//if one row found, this name is already taken
		if( $result->rowCount() > 0 ){
			$valid = false;
			$errors['username'] = 'Sorry, that username is taken. Try another.';
        }
	}

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $valid= false;
        $errors['email'] = 'Please provide a valid e-mail address.';
    }
    else {
        $query = 'SELECT email
                  FROM users
                  WHERE email = :email
                  LIMIT 1';
        $result = $DB->prepare($query);
        $result->execute(array('email'=>$email));
        if($result->rowCount()>0){
            $valid=false;
            $errors['email']='This e-mail is already registered. Try logging in.';
        }
    }

    if(strlen($password)<PASSWORD_MIN){
        $valid=false;
        $errors['password']= 'Passwords must be at least'.PASSWORD_MIN.'characters long.';
    }

    if(!$policy ){
		$valid = false;
		$errors['policy'] = 'You must agree to the terms of service before registering.';
	}

    if( $valid ){
		//make an avatar
		$avatar = make_letter_avatar( $username[0], 60 );
		$query = 'INSERT INTO users
					( email, username, password, profile_pic, bio, is_admin, date_joined )
					VALUES 
					( :email, :username, :password, :image, "", 0, now() )';
		$result = $DB->prepare( $query );
		//make a uniquely salted, hashed password for storage
		$hashed_pass = password_hash( $password , PASSWORD_DEFAULT );
		$data = array(
				'username' 	=> $username,
				'email' 	=> $email,
				'password' 	=> $hashed_pass,
				'image' 	=> $avatar,
				);
		$result->execute( $data );
		//check if the row was added
		if( $result->rowCount() > 0 ){
			//success
			$feedback = 'Success! You can log in now';
			$feedback_class = 'success';
			header('Location: login.php?action=success');
		}else{
			//error: DB issue
			$feedback = 'Insert failed';
			$feedback_class = 'error';
		}
	}else{
		//error: invalid submission
		$feedback = 'Fix these problems:';
		$feedback_class = 'error';
	}


    //if valid new user to db
}//end if did register 
