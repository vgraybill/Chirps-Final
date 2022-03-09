<?php
if(isset($_POST['did_submit']) ){
    $comment = clean_string($_POST['comment']);
    $user_id = $logged_in_user['user_id'];

    $valid=true;
    if(strlen($comment) > 500 or strlen($comment) < 5){
        $valid=false;
        $errors['length']='Max 500 characters';
    }
    if($post_id == 0){
        $valid=false;
        $errors['post'] = 'Invalid post';
    } 
    if(!$logged_in_user){
        $valid=false;
        $errors['login'] = 'Log in to comment';
    }
    if($valid){
        $result = $DB->prepare(
            'INSERT INTO comments
            ( user_id, body, date, post_id )
            VALUES
            (:user, :body, now(), :post)
        ');
        $data= array( 
            'body' => $comment,
            'user' => $user_id,
            'post' => $post_id,
        );
        $result->execute($data);
        
        if($result->rowCount()>0){

        } else {
            $feedback = 'Sorry, try again....';
            $feedback_class= 'error';
        }
        
        } else {
            $feedback = 'Please fix the following:';
            $feedback_class= 'error';
        }
}