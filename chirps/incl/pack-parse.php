<?php
require('../config.php');
require_once('functions.php');
$logged_in_user = check_login();

if(isset($_REQUEST['pack_id'])){
    $incoming_pack = clean_int($_REQUEST['pack_id']);
    
    $check = $DB->prepare(
        'SELECT cp.*
        FROM card_packs AS cp
        WHERE cp.pack_id = :pack_id
    ');
    $check->execute(array('pack_id'=>$incoming_pack));
    while( $row = $check->fetch() ){	
        extract($row);
    }
    $valid=true;
    if($incoming_pack == 0 OR $pack_name == NULL){
        $valid=false;
        $errors['pack']= 'That pack does not exist.';
    } 
    if($valid){
        $check = $DB->prepare(
            'SELECT ucp.*, cp.*, u.user_id
            FROM user_car_packs AS ucp, card_packs AS cp, users AS u
            WHERE cp.pack_id = :pack_id
            AND u.user_id = :user_id
            AND ucp.user_id = u.user_id
            AND ucp.pack_id = cp.pack_id
            LIMIT 1'
            );
        $check->execute(array(
            'pack_id'=>$incoming_pack,
            'user_id'=>$logged_in_user['user_id']));
        if($check->rowCount()>0){
            $valid=false;
            $errors['pack']= 'You already have this pack.';
        }
    }//end if valid
    if($valid){
        $result = $DB->prepare( 'INSERT INTO user_car_packs
			( user_id, pack_id )
			VALUES
			(:user_id, :pack_id)' );

			$data = array(
                'user_id'=>$logged_in_user['user_id'],
                'pack_id'=>$incoming_pack
			);

			$result->execute($data);
			//check it - if it worked, redirect to step 2, otherwise show feedback
			if( $result->rowCount() > 0 ){
				header("Location:../makepoetry.php?feedback=success");
			} 
    } else {
        header("Location:../makepoetry.php?feedback=error");
    }
}//end isset