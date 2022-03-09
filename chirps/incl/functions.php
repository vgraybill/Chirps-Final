<?php

function show_feedback( &$message, &$css_class = 'info', &$list = array() ){
	if(  isset( $message )  ){
		echo "<span class='feedback $css_class'>$message";
		//if the list isn't empty, show it 
		if(! empty( $list )){
			echo '<ul>';
			foreach( $list as $item ){
				echo "<li>$item</li>";
			}
			echo '</ul>';
		}
        echo "</span>";
	}
}

function count_likes( $post_id = 0 ){
	global $DB;
	$result = $DB->prepare( 'SELECT COUNT(*) AS total
							FROM likes
							WHERE post_id = :id' );
	$result->execute( array( 'id' => $post_id ) );
	if( $result->rowCount() > 0 ){
		while( $row = $result->fetch() ){
			return $row['total'];
		}
	}elseif( DEBUG_MODE ){
		return $DB->errorInfo();
	}
}
/**
 * display comment_count on any post ID
 * @param  integer $post_id 
 * @param  boolean $long    whether or not to include the word "comment(s)" at the end
 * @return mixed          HTML output
 */
function display_like_count( $post_id = 0, $long = true ){
	?>
	<span class="comment-count">
		<?php 
		$total = count_likes( $post_id ); 
		if( $long ) {
			echo $total == 1 ? '1 Like' : "$total Likes"; 
		}else{
			echo $total;
		}
		?>			
	</span>
	<?php
}

function like_interface( $post_id, $user_id = 0 ){
    global $DB;
    //is the viewer logged in?
      if( $user_id ){
      //does the viewer "like" this post?
       $result = $DB->prepare( "SELECT * FROM likes
                WHERE user_id = ?
                AND post_id = ?
                LIMIT 1" );
        $result->execute(array($user_id, $post_id));
     if( $result->rowCount() >= 1 ){
        //they like it
        $class = 'solid-heart';
      }else{
        //they don't like
        $class = 'lined-heart';
      }
    } //end if logged in
    
    ?>
        <?php 
        //logged in?
        if( $user_id ){ ?>
        <img class="heart-button" data-postid="<?php echo $post_id; ?>" src="img/<?php echo $class; ?>.png"><h5><?php echo display_like_count( $post_id ); ?></h5>
        <?php 
        } else{ //end if logged in
        ?>
        <img src="img/solid-heart.png"><h5><?php echo display_like_count( $post_id ); ?></h5>
        
    <?php }
  }


  function staff_picks( $post_id, $user_id = 0 ){
    global $DB;
    //is the viewer logged in?
      if( $user_id ){
      //does the viewer "like" this post?
       $result = $DB->prepare( "SELECT * FROM adminlikes
                WHERE user_id = ?
                AND post_id = ?
                LIMIT 1" );
        $result->execute(array($user_id, $post_id));
     if( $result->rowCount() >= 1 ){
        //they like it
        $class = '★';
      }else{
        //they don't like
        $class = '☆';
      }
    } //end if logged in
    
    ?>
            <?php 
            //logged in?
            if( $user_id ){ ?>
            <span class="star-button" data-postid="<?php echo $post_id; ?>"><?php echo $class; ?></span>
            <?php 
            } //end if logged in
            ?>

        <h5>Staff Pick</h5>
    <?php
  }




function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


//CLEANERS!

function clean_string( &$dirty = ''){
	return trim( strip_tags( $dirty) );
}
function clean_int( &$dirty = 0 ){
	return filter_var( $dirty, FILTER_SANITIZE_NUMBER_INT );
}
function clean_boolean( &$dirty = 0 ){
	if(! isset($dirty) OR ! $dirty ){
		return 0;
	}else{
		return 1;
	}
}
function clean_email( &$dirty = '' ){
	return filter_var( $dirty,  FILTER_SANITIZE_EMAIL );
}




function make_letter_avatar($string, $size){
	//random pastel color
    $H =   mt_rand(0, 360);
    $S =   mt_rand(25, 50);
    $B =   mt_rand(90, 96);

    $RGB = get_RGB($H, $S, $B);
    $string = strtoupper($string);

    $imageFilePath = 'img/avatars/' . $string . '_' .  $H . '_' . $S . '_' . $B . '.png';

    //base avatar image that we use to center our text string on top of it.
    $avatar = imagecreatetruecolor($size, $size);  
    //make and fill the BG color
    $bg_color = imagecolorallocate($avatar, $RGB['red'], $RGB['green'], $RGB['blue']);
    imagefill( $avatar, 0, 0, $bg_color );
    //white text
    $avatar_text_color = imagecolorallocate($avatar, 255, 255, 255);
	// Load the gd font and write 
    //$font = imageloadfont('gd-files/gd-font.gdf');
    ///imagestring($avatar, $font, 10, 10, $string, $avatar_text_color);
    
    $font = 'fonts/Baloo2-Medium.ttf';
    $x = ($size/2) - 14;
    $y = $size/2 + 15;
    imagettftext($avatar, 30, 0, $x, $y, $avatar_text_color, $font, $string);


    imagepng($avatar, $imageFilePath);

    imagedestroy($avatar);

    return $imageFilePath;
}


/*
*  Converts HSV to RGB values
*  Input:     Hue        (H) Integer 0-360
*             Saturation (S) Integer 0-100
*             Lightness  (V) Integer 0-100
*  Output:    Array red, green, blue
*/
function get_RGB($iH, $iS, $iV) {
    if($iH < 0)   $iH = 0;   // Hue:
    if($iH > 360) $iH = 360; //   0-360
    if($iS < 0)   $iS = 0;   // Saturation:
    if($iS > 100) $iS = 100; //   0-100
    if($iV < 0)   $iV = 0;   // Lightness:
    if($iV > 100) $iV = 100; //   0-100

    $dS = $iS/100.0; // Saturation: 0.0-1.0
    $dV = $iV/100.0; // Lightness:  0.0-1.0
    $dC = $dV*$dS;   // Chroma:     0.0-1.0
    $dH = $iH/60.0;  // H-Prime:    0.0-6.0
    $dT = $dH;       // Temp variable

    while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
    $dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link

    switch(floor($dH)) {
        case 0:
        $dR = $dC; $dG = $dX; $dB = 0.0; break;
        case 1:
        $dR = $dX; $dG = $dC; $dB = 0.0; break;
        case 2:
        $dR = 0.0; $dG = $dC; $dB = $dX; break;
        case 3:
        $dR = 0.0; $dG = $dX; $dB = $dC; break;
        case 4:
        $dR = $dX; $dG = 0.0; $dB = $dC; break;
        case 5:
        $dR = $dC; $dG = 0.0; $dB = $dX; break;
        default:
        $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
    }

    $dM  = $dV - $dC;
    $dR += $dM; $dG += $dM; $dB += $dM;
    $dR *= 255; $dG *= 255; $dB *= 255;

    return  array(
        'red' =>  round($dR),
        'green'=> round($dG),
        'blue' => round($dB)
    );
}

function check_login(){
    global $DB;
    //if the cookie is valid, turn it into session data
    if(isset($_COOKIE['hashbrowns']) AND isset($_COOKIE['user_id'])){
        $_SESSION['hashbrowns'] = $_COOKIE['hashbrowns'];
        $_SESSION['user_id'] = $_COOKIE['user_id'];
    }

   //if the session is valid, check their credentials
   if( isset($_SESSION['hashbrowns']) AND isset($_SESSION['user_id']) ){
        //check to see if these keys match the DB     

       $data = array(
       	'hashbrowns' =>$_SESSION['hashbrowns']
       );

        $result = $DB->prepare(
        	"SELECT * FROM users
                WHERE hashbrowns = :hashbrowns
                LIMIT 1");
        $result->execute( $data );
       
        if($result->rowCount() > 0){
            $row = $result->fetch();
            if(password_verify($row['user_id'], $_SESSION['user_id'])){
            //success! return all the info about the logged in user
            return $row;

            }else{
                return false;
            }

        }else{
            return false;
        }
    }else{
        //not logged in
        return false;
    }
}

function most_liked(){
    global $DB;
    //get all posts posted from the last 24 hours
    $result=$DB->prepare(
        'SELECT posts.*, likes.*
        FROM posts, likes
        WHERE posts.date > DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        AND posts.post_id = likes.post_id
        ');
        $result->execute();
    //check like count on all post
    if($result->rowCount()>0){
        $data = array();
    while($row = $result->fetch()){
        extract($row);
        $data[$post_id] = array(count_likes($post_id),$post_id);
    }
    //show highest liked post
    $data=max($data);
    return next($data);
    
    } else {
       echo 'No posts from yesterday.';
    }

}

function count_total_likes(){
        global $DB;
        $user_id = clean_int($_REQUEST['user']);
        $result = $DB->prepare( 
            'SELECT COUNT(*) AS total
            FROM likes, posts
            WHERE likes.post_id = posts.post_id
            AND posts.user_id = :user_id
            ' );
        $result->execute( array( 'user_id' => $user_id ) );
        if( $result->rowCount() > 0 ){
            while( $row = $result->fetch() ){
                return $row['total'];
            }
        }elseif( DEBUG_MODE ){
            return $DB->errorInfo();
        }
}
function count_total_posts(){
    global $DB;
    $logged_in_user=check_login();
    $user_id = clean_int($_REQUEST['user']);
    if($logged_in_user['user_id'] == $user_id){
        $result = $DB->prepare(
            'SELECT COUNT(*) AS total
            FROM posts
            WHERE user_id = :user_id
            ');
            $result->execute( array( 'user_id' => $user_id ) );
            if( $result->rowCount() > 0 ){
                while( $row = $result->fetch() ){
                    return $row['total'];
                }
            }elseif( DEBUG_MODE ){
                return $DB->errorInfo();
            }
    }
    else {
        $result = $DB->prepare(
            'SELECT COUNT(*) AS total
            FROM posts
            WHERE user_id = :user_id
            AND is_published = 1
            AND has_username = 1
            ');
            $result->execute( array( 'user_id' => $user_id ) );
            if( $result->rowCount() > 0 ){
                while( $row = $result->fetch() ){
                    return $row['total'];
                }
            }elseif( DEBUG_MODE ){
                return $DB->errorInfo();
            }
    }
}

function current_theme(){
    global $DB;
    $user_id = clean_int($_REQUEST['user']);
    $result = $DB->prepare(
        'SELECT color_scheme
        FROM users
        WHERE user_id = :user_id
        ');
        $result->execute( array( 'user_id' => $user_id ) );
        if( $result->rowCount() > 0 ){
            extract($result->fetch());
            if ($color_scheme == 0){
                echo "<div class='colorselect color0 smol'><i class='fa-solid fa-crow'></i></div>";
            }
            if ($color_scheme == 1){
                echo "<div class='colorselect color1 smol'><i class='fa-solid fa-heart'></i></div>";
            }
            if ($color_scheme == 2){
                echo "<div class='colorselect color2 smol'><i class='fa-solid fa-fish'></i></div>";
            }
            if ($color_scheme == 3){
                echo "<div class='colorselect color3 smol'><i class='fa-solid fa-sun'></i></div>";
            }
        }
}
function current_theme_choose(){
    global $DB;
    $user_id = clean_int($_REQUEST['user']);
    $result = $DB->prepare(
        'SELECT color_scheme
        FROM users
        WHERE user_id = :user_id
        ');
        $result->execute( array( 'user_id' => $user_id ) );
        if( $result->rowCount() > 0 ){
            extract($result->fetch());
            if ($color_scheme == 0){
                echo "<div class='colorselect color0 current'><i class='fa-solid fa-crow'></i></div>";
                echo "<div class='colorselect color1'><i class='fa-solid fa-heart'></i></div>";
                echo "<div class='colorselect color2'><i class='fa-solid fa-fish'></i></div>";
                echo "<div class='colorselect color3'><i class='fa-solid fa-sun'></i></div>";
            }
            if ($color_scheme == 1){
                echo "<div class='colorselect color0'><i class='fa-solid fa-crow'></i></div>";
                echo "<div class='colorselect color1 current'><i class='fa-solid fa-heart'></i></div>";
                echo "<div class='colorselect color2'><i class='fa-solid fa-fish'></i></div>";
                echo "<div class='colorselect color3'><i class='fa-solid fa-sun'></i></div>";
            }
            if ($color_scheme == 2){
                echo "<div class='colorselect color0'><i class='fa-solid fa-crow'></i></div>";
                echo "<div class='colorselect color1'><i class='fa-solid fa-heart'></i></div>";
                echo "<div class='colorselect color2 current'><i class='fa-solid fa-fish'></i></div>";
                echo "<div class='colorselect color3'><i class='fa-solid fa-sun'></i></div>";
            }
            if ($color_scheme == 3){
                echo "<div class='colorselect color0'><i class='fa-solid fa-crow'></i></div>";
                echo "<div class='colorselect color1'><i class='fa-solid fa-heart'></i></div>";
                echo "<div class='colorselect color2'><i class='fa-solid fa-fish'></i></div>";
                echo "<div class='colorselect color3 current'><i class='fa-solid fa-sun'></i></div>";
            }
        }
}

function show_todays_top_post(){
    $most_liked=most_liked();
    $logged_in_user=check_login();
    global $DB;
    $result=$DB->prepare(
        'SELECT posts.*, likes.*
        FROM posts, likes
        WHERE posts.post_id = :mostliked
        AND posts.post_id = likes.post_id
        LIMIT 1
        ');
        $result->execute(array('mostliked'=>$most_liked));
        
        while($row = $result->fetch()){
            extract($row); ?>

        <a href="poem.php?post_id=<?php echo urlencode($most_liked); ?>">
                    <img class="post" src="<?php echo $post_img; ?>" alt="<?php echo $post_alt; ?>">
                </a>

                <div class="under-aside">
                    <span class="likes">
                        <?php if($logged_in_user){
                            $viewer_id = $logged_in_user['user_id'];
                        }
                        else {
                            $viewer_id= 0;
                        }
                        if(!$logged_in_user){ ?>
                            <?php echo like_interface($most_liked, $viewer_id); ?><?php
                            }else{
                                like_interface($most_liked, $viewer_id);
                            }?>
                    </span>
                </div>
<?php
        
        }
}

function staff_picks_display(){
    global $DB;
    $logged_in_user=check_login();
    $result=$DB->prepare(
        'SELECT adminlikes.*, posts.*
        FROM posts, adminlikes
        WHERE posts.post_id = adminlikes.post_id
        ORDER BY posts.date DESC
        LIMIT 5 
        ');
    $result->execute();
    if($result->rowCount()>0){
        while($row = $result->fetch()){
            extract($row) ?>
        <a href="poem.php?post_id=<?php echo urlencode($post_id); ?>">
                    <img class="post" src="<?php echo $post_img; ?>" alt="<?php echo $post_alt; ?>">
                </a>

                <div class="under-aside">
                    <span class="likes">
                        <?php if($logged_in_user){
                            $viewer_id = $logged_in_user['user_id'];
                        }
                        else {
                            $viewer_id= 0;
                        }
                        if(!$logged_in_user){ ?>
                            <?php echo like_interface($post_id, $viewer_id); ?><?php
                            }else{
                                like_interface($post_id, $viewer_id);
                            }?>
                    </span>
                </div>

    <?php
            }
        } else {
            echo 'Nothing to display.';
        }
    }