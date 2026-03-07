<?php
$valid1 = false;
$bio = '';
$user_id = 0;

if(isset($_POST['bio']) && $_POST['bio'] != ''){
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

		$filetype = $_FILES['uploadedfile']['type'];
		$has_gd_base = function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg');
		$can_load_type =
			(($filetype == 'image/jpg' || $filetype == 'image/jpeg' || $filetype == 'image/pjpeg') && function_exists('imagecreatefromjpeg')) ||
			($filetype == 'image/gif' && function_exists('imagecreatefromgif')) ||
			($filetype == 'image/png' && function_exists('imagecreatefrompng'));
		$has_gd = $has_gd_base && $can_load_type;

		if($valid && $has_gd){
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
			if(!$did_save && file_exists($uploadedfile)){
				$did_save = copy($uploadedfile, $filepath);
			}
			if(!$did_save){
				$upload_error = $_FILES['uploadedfile']['error'] ?? 'unknown';
				$errors['save'] = 'Could not save uploaded image.';
				if(DEBUG_MODE){
					$errors['save_debug'] = 'Upload error code: ' . $upload_error;
				}
			}
		}

		if($did_save){
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
	$feedback = 'Profile updated successfully.';
	$feedback_class = 'success';
}
