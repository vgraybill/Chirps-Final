<?php 
//if the user submitted the form
if(isset($_POST['bio']) AND $_POST['bio'] != ''){
$bio = clean_string($_POST['bio']);
$valid1 = true;
}
$user_id = $logged_in_user['user_id'];



if( isset( $_POST['did_upload'] ) AND  file_exists($_FILES['uploadedfile']['tmp_name'])){
	//upload configuration 
	//this directory must exist and be writable
	$target_directory = 'img/avatars/';
	$uploadedfile = $_FILES['uploadedfile']['tmp_name'];
	$sizes = array(
		'small' 	=> 150
	);

	//grab the image that they uploaded
	

	//validate
	$valid = true;

	//get the dimensions of the image
	list( $width, $height ) = getimagesize( $uploadedfile );

	//does the image contain pixels?
	if( $width == 0 OR $height == 0 ){
		//NOT AN IMAGE
		$valid = false;
		$errors['size'] = 'Your image does not meet the minimum size requirements.';
	}

	//if valid, process and resize the image
	if($valid){

		//get the filetype
		$filetype = $_FILES['uploadedfile']['type'];

		switch( $filetype ){
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/pjpeg':
				$src = imagecreatefromjpeg( $uploadedfile );
			break;

			case 'image/gif':
				$src = imagecreatefromgif( $uploadedfile );
			break;

			case 'image/png':
				//todo: increase resources on the server
				$src = imagecreatefrompng( $uploadedfile );
			break;
		}

		//unique string for the final file name
		$unique_name = sha1( microtime() );

		//do the resizing
		foreach( $sizes AS $size_name => $pixels ){
			//square crop calculations -  landscape or portrait
			if( $width > $height ){
				//landscape
				$offset_x = round( ( $width - $height ) / 2 ) ;
				$offset_y = 0;
				$crop_size = $height;
			}else{
				//portrait or square
				$offset_x = 0;
				$offset_y = round( ( $height - $width ) / 2 );
				$crop_size = $width;
			}
			//create a new blank canvas of the desired size
			$tmp_canvas = imagecreatetruecolor( $pixels, $pixels );

			//scale down and align the original onto the tmp canvas
			//dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h
			imagecopyresampled( $tmp_canvas, $src, 0, 0, $offset_x, $offset_y, $pixels, $pixels, $crop_size, $crop_size );

			//save it into the correct directory
			//something like 	uploads/fdkuhfdghjkfdg_small.jpg
			$filepath = $target_directory . $unique_name . '_' . $size_name . '.jpg';

			$did_save = imagejpeg( $tmp_canvas, $filepath, 70 );

		}//end foreach size

		//clean up old resources
		imagedestroy($src);
		imagedestroy($tmp_canvas);


		// Add post to Database
		if($did_save AND $valid1){
			$que = $DB->prepare(
				'SELECT users.profile_pic
				FROM users
				WHERE user_id = :user_id
				LIMIT 1');
			$que->execute(array('user_id'=>$user_id));
			while ($row = $que->fetch()){
				extract($row);
			}
			unlink($profile_pic);
			$result = $DB->prepare(
				'UPDATE users
            	SET
            	bio = :bio,
            	profile_pic = :pic
            	WHERE user_id = :user_id
            LIMIT 1
				');

			$data = array(
				'pic' => $filepath,
				'user_id' => $user_id,
				'bio' => $bio
			);

			$result->execute($data);
			//check it - if it worked, redirect to step 2, otherwise show feedback
			if( $result->rowCount() > 0 ){
				// header("Location:profile.php?user_id=$user_id");
			}

			}
			else if($did_save){
				$result = $DB->prepare(
					'UPDATE users
					SET
					profile_pic = :pic
					WHERE user_id = :user_id
				LIMIT 1
					');
		
				$data = array(
					'pic' => $filepath,
					'user_id' => $user_id,
				);
		
				$result->execute($data);
				//check it - if it worked, redirect to step 2, otherwise show feedback
				if( $result->rowCount() > 0 ){
					// header("Location:profile.php?user_id=$user_id");
				}
			}
	}
}
if($valid1){
	$result = $DB->prepare(
		'UPDATE users
		SET
		bio = :bio
		WHERE user_id = :user_id
	LIMIT 1
		');

	$data = array(
		'bio' => $bio,
		'user_id' => $user_id,
	);

	$result->execute($data);
	//check it - if it worked, redirect to step 2, otherwise show feedback
	if( $result->rowCount() > 0 ){
		// header("Location:profile.php?user_id=$user_id");
	}
}
