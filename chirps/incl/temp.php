<?php
<?php 
$valid1 = false;
$bio = '';
$user_id = 0;

if(isset($_POST['bio']) && $_POST['bio'] != ''){
	$bio = clean_string($_POST['bio']);
	$valid1 = true;
}

//if the user submitted the form
if(isset($_POST['bio']) AND $_POST['bio'] != ''){
$bio = clean_string($_POST['bio']);
$valid1 = true;
}
if($logged_in_user){
	$user_id = $logged_in_user['user_id'];
}

$did_upload = clean_boolean($_POST['did_upload'] ?? 0);
$has_upload = isset($_FILES['uploadedfile']['tmp_name']) && is_uploaded_file($_FILES['uploadedfile']['tmp_name']);

if($logged_in_user && $did_upload){
	if(!$has_upload){
		$feedback = 'Please choose an image file before submitting.';
		$feedback_class = 'error';
	}else{
		$target_directory = 'img/avatars/';
		$uploadedfile = $_FILES['uploadedfile']['tmp_name'];
		$did_save = false;
		$filepath = '';
		$valid = true;

		if(!is_dir($target_directory) || !is_writable($target_directory)){
			$valid = false;
			$errors['dir'] = 'Avatar folder is not writable.';
		}

		$dimensions = getimagesize($uploadedfile);
		if($dimensions){
			$width = $dimensions[0];
			$height = $dimensions[1];
		}else{
			$width = 0;
			$height = 0;
		}

		if($width == 0 || $height == 0){
			$valid = false;
			$errors['size'] = 'Your image does not meet the minimum size requirements.';
		}

		$has_gd = function_exists('imagecreatefromjpeg') && function_exists('imagecreatefromgif') && function_exists('imagecreatefrompng') && function_exists('imagecopyresampled') && function_exists('imagejpeg');

		if($valid && $has_gd){
			$filetype = $_FILES['uploadedfile']['type'];
			$src = false;

			switch($filetype){
				case 'image/jpg':
				case 'image/jpeg':
				case 'image/pjpeg':
					$src = imagecreatefromjpeg($uploadedfile);
				break;

				case 'image/gif':
					$src = imagecreatefromgif($uploadedfile);
				break;

				case 'image/png':
					$src = imagecreatefrompng($uploadedfile);
				break;
if($logged_in_user AND isset( $_POST['did_upload'] ) AND file_exists($_FILES['uploadedfile']['tmp_name'])){
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
	$has_gd = function_exists('imagecreatefromjpeg') && function_exists('imagecreatefrompng') && function_exists('imagecopyresampled') && function_exists('imagejpeg');
	if(!$has_gd){
		$valid = false;
		$errors['gd'] = 'Image editing is unavailable because the GD extension is not enabled.';
	}

	//get the dimensions of the image
	list( $width, $height ) = getimagesize( $uploadedfile );

	//does the image contain pixels?
	if( $width == 0 OR $height == 0 ){
		//NOT AN IMAGE
		$valid = false;
		$errors['size'] = 'Your image does not meet the minimum size requirements.';
	}

		//if valid, process and resize the image
		if($valid AND $has_gd){

		//get the filetype
		$filetype = $_FILES['uploadedfile']['type'];
		$src = false;

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

			if(!$src){
				$valid = false;
				$errors['filetype'] = 'Unsupported image type.';
			}

		if(!$src){
			$valid = false;
			$errors['filetype'] = 'Unsupported image type.';
		}

		//unique string for the final file name
		$unique_name = sha1( microtime() );

		//do the resizing
		if($valid){
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

			if(!$src){
				$valid = false;
				$errors['filetype'] = 'Unsupported image type.';
			}else{
				$pixels = 150;
				if($width > $height){
					$offset_x = round(($width - $height) / 2);
					$offset_y = 0;
					$crop_size = $height;
				}else{
					$offset_x = 0;
					$offset_y = round(($height - $width) / 2);
					$crop_size = $width;
				}

				$tmp_canvas = imagecreatetruecolor($pixels, $pixels);
				imagecopyresampled($tmp_canvas, $src, 0, 0, $offset_x, $offset_y, $pixels, $pixels, $crop_size, $crop_size);

				$filepath = $target_directory . sha1(microtime()) . '_small.jpg';
				$did_save = imagejpeg($tmp_canvas, $filepath, 70);
		}//end foreach size
		}

		//clean up old resources
		if($src){
			imagedestroy($src);
		}
		if(isset($tmp_canvas) && $tmp_canvas){
			imagedestroy($tmp_canvas);
		}

				imagedestroy($src);
				imagedestroy($tmp_canvas);
			}
		}else if($valid && !$has_gd){
			$extension = strtolower(pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION));
			if(!in_array($extension, array('jpg', 'jpeg', 'gif', 'png'))){
				$extension = 'jpg';
			}
			$filepath = $target_directory . sha1(microtime()) . '_small.' . $extension;
			$did_save = move_uploaded_file($uploadedfile, $filepath);
			if(!$did_save){
				$errors['save'] = 'Could not save uploaded image.';
			}
		}

		if($did_save){
			// Add post to Database
			if($did_save AND $valid1){
			$que = $DB->prepare(
				'SELECT users.profile_pic
				 FROM users
				 WHERE user_id = :user_id
				 LIMIT 1'
			);
			$que->execute(array('user_id' => $user_id));
			if($row = $que->fetch()){
				$old_pic = $row['profile_pic'];
				if($old_pic && $old_pic != 'img/anon.webp' && file_exists($old_pic)){
					@unlink($old_pic);
				}
			}

			if($valid1){
				$result = $DB->prepare(
					'UPDATE users
					 SET bio = :bio,
					     profile_pic = :pic
					 WHERE user_id = :user_id
					 LIMIT 1'
				);
				$data = array(
					'pic' => $filepath,
					'user_id' => $user_id,
					'bio' => $bio,
				);
			}else{
				$result = $DB->prepare(
					'UPDATE users
					 SET profile_pic = :pic
					 WHERE user_id = :user_id
					 LIMIT 1'
				);
				$data = array(
					'pic' => $filepath,
					'user_id' => $user_id,
				);
			}

			$result->execute($data);
			$feedback = 'Profile picture updated successfully.';
			$feedback_class = 'success';
		}else{
			$feedback = 'Could not update profile image.';
			$feedback_class = 'error';
		}
	}
}

if($logged_in_user && $valid1 && !$did_upload){
if($logged_in_user AND $valid1){
	$result = $DB->prepare(
		'UPDATE users
		 SET bio = :bio
		 WHERE user_id = :user_id
		 LIMIT 1'
	);

	$data = array(
		'bio' => $bio,
		'user_id' => $user_id,
	);

	$result->execute($data);
}
