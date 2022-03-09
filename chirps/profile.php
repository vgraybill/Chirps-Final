<?php
    require('config.php');
    include_once('incl/functions.php');

    include('incl/header.php');
    include('incl/temp.php');

    if(isset($_REQUEST['user']) AND is_numeric($_REQUEST['user']) ){
        $profile = clean_int($_REQUEST['user']);
    } else {
        header('Location:index.php');
    }
    $result = $DB->prepare(
        'SELECT users.*
         FROM users
         WHERE users.user_id = :userid
         LIMIT 1
    ');

    $result->execute(array('userid'=>$profile));

    if($result->rowCount()>0){

    while ($row = $result->fetch()){
        extract($row);
?>

<main>
    <div class="profile-int">
        <?php if($logged_in_user['user_id'] == $profile){ ?>

        <section class="user-info">
            <div class="please">
                <img class="profile-pic" src="<?php echo $profile_pic ?>" alt="<?php echo $username; ?>">
                <div class="title-time">
                        <div>
                            <h2><?php echo $username; ?></a></h2>
                            <h4><a class="edit">edit profile</a></h4>
                            <h4><a class="cancel">cancel</a></h4>
                        </div>
                                <p><?php echo $bio; ?></p>
                </div>
            </div>
            <hr class=hidden>
            <div class="total">
                <h4>Total Likes <span><?php echo count_total_likes(); ?></span></h4>
                <h4>Total Posts <span><?php echo count_total_posts(); ?></span></h4>
                <h5>Select a Color Scheme</h5>
                <div class="select">
                    <?php echo current_theme_choose(); ?>
                </div>
                
            </div>
            <div class="slide-out">
                    <form enctype="multipart/form-data" action="profile.php?user=<?php echo $profile; ?>" method="post">
                        <label>Update Your Bio<textarea name="bio"><?php echo $bio; ?></textarea></label>
                        <label>Update Your Profile Pic <input class="thisone" type="file" name="uploadedfile" id="uploadedfile" accept="image/*">

                        <input type="hidden" name="did_upload" value="1">
                        <input type="submit" value="Submit" class="button">
                        
                    </form>
            </div>
                            
        </section>

        <hr>
        <section class="poetry-gallery">
            

        <?php $result = $DB->prepare(
            'SELECT users.*, posts.*
            FROM users, posts
            WHERE users.user_id = :userid
            AND users.user_id = posts.user_id
            ORDER BY posts.date DESC
            ');
            $result->execute(array('userid' => $profile));
            while($row = $result->fetch()){ 
                extract($row); ?>
                <a href="poem.php?post_id=<?php echo urlencode($post_id); ?>">
                    <img src="<?php echo $post_img; ?>" class="profile-img">
                </a>
           <?php }?>
        </section>
        
            

        <?php } else { ?>

        <section class="user-info">
            <div class="please">
                <img class="profile-pic" src="<?php echo $profile_pic ?>" alt="<?php echo $username; ?>">
                <div class="title-time">
                        <div>
                            <h2><?php echo $username; ?></a></h2>
                        </div>
                                <p><?php echo $bio; ?></p>
                </div>
            </div>
            <hr class="hidden">
            <div class="total-other">
                <h4>Total Likes <span><?php echo count_total_likes() ?></span></h4>
                <h4>Total Posts <span><?php echo count_total_posts(); ?></span></h4>
                <h4>Current Theme <span><?php echo current_theme(); ?></span></h4>
            </div>
        </section>

        <hr>
        
            

        <?php $result = $DB->prepare(
            'SELECT users.*, posts.*
            FROM users, posts
            WHERE users.user_id = :userid
            AND users.user_id = posts.user_id
            AND posts.is_published = 1
            AND posts.has_username = 1
            ORDER BY posts.date DESC
            ');
            $result->execute(array('userid' => $profile));
            if ($result->rowCount()>0){ ?>
                    <section class="poetry-gallery">
                        <?php 
                while($row = $result->fetch()){ 
                    extract($row); ?>
                    <a href="poem.php?post_id=<?php echo urlencode($post_id); ?>">
                        <img src="<?php echo $post_img; ?>" class="profile-img">
                    </a>
            <?php }?>
            </section>
                
            <?php } else { ?>
                <section>
                    <h3>Nothing to display.</h3>
                </section>
               <?php } ?>
        <?php }?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
            $('.edit').on('click', function(){
                $('.slide-out').slideDown(300)
                $(this).fadeOut(0)
                $('.cancel').fadeIn(300)
                $('.total').css({'display':'none'})
            });
            $('.cancel').on('click', function(){
                $('.slide-out').slideUp(300, function(){
                    $('.total').fadeIn(100)
                 })
                $(this).fadeOut(0)
                $('.edit').fadeIn(300)
                
            })
            <?php if($logged_in_user['user_id'] == $profile){?>
            $('.colorselect').on('click', function(){
                let theButton = $(this)
                if (theButton.hasClass('color0')){
                    theColor = 0;
                    $('.current-color').removeClass('current-color')
                    theButton.addClass('current-color')
                }
                if (theButton.hasClass('color1')){
                    theColor = 1;
                    $('.current-color').removeClass('current-color')
                    theButton.addClass('current-color')
                }
                if (theButton.hasClass('color2')){
                    theColor = 2;
                    $('.current-color').removeClass('current-color')
                    theButton.addClass('current-color')
                }
                if (theButton.hasClass('color3')){
                    theColor = 3;
                    $('.current-color').removeClass('current-color')
                    theButton.addClass('current-color')
                }
                sendtoAjax(theColor)
            })
            function sendtoAjax(i){
            let theColor = i
            $.ajax({
                
                    type: "POST",
                    url: "profile.php?user=<?php echo clean_int($_REQUEST['user']); ?>",
                    data: {
                        color_scheme: theColor,
                        valid: 1
                            }
                                                    ,
                    'beforeSend': function(xhr, settings) {
                        console.log('ABOUT TO SEND');
                    },
                    'success': function(result, status_code, xhr) {
                        console.log('SUCCESS!');
                    },
                        'complete': function(xhr, text_status) {
                        console.log('Done.');
                    },
                        'error': function(xhr, text_status, error_thrown) {
                        console.log('ERROR!', text_status, error_thrown);
                    }
                            
                    }
                    ).done(function(o) {
                        window.location.replace("profile.php?user=<?php echo $profile; ?>");
                        });
                    }
                    <?php } ?>

        </script>
</main>
<?php
    $color_scheme = clean_int($_REQUEST['color_scheme']);
    $user_id = clean_int($_REQUEST['user']);
    $valid = clean_boolean($_REQUEST['valid']);
    if($logged_in_user['user_id'] == $user_id AND $valid == 1){

            $result = $DB->prepare(
                'UPDATE users
                SET color_scheme = :color
                WHERE users.user_id = :user_id
                LIMIT 1
                ');
            
            $data = array(
                'color'=>$color_scheme,
                'user_id'=>$user_id
            );

            $result->execute($data);

            if($result->rowCount() > 0){
                $feedback = 'Changes successfully made.';
                $feedback_class = 'success';
            }
            else {
                $feedback = 'No changes made to your post.';
                $feedback_class = 'info';
            }
        }

    ?>
<?php }
} else {
    header('Location:index.php');
}
?>


<?php include('incl/footer.php'); ?>