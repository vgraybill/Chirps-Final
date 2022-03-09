<?php
    require('config.php');
    include_once('incl/functions.php');

    include('incl/header.php');

    if( isset( $_GET['post_id'] ) ){
        //sanitize and validate
        $post_id = filter_var( $_GET['post_id'], FILTER_SANITIZE_NUMBER_INT );
        //make sure it isn't blank
        if( '' == $post_id ){
            $post_id = 0;
        }
    }else{
        $post_id = 0;
    }
    require('incl/comment-parse.php');
    ?>


    <main>
        <div class="wrapper poem-int">

    <?php
        $result = $DB->prepare(
            'SELECT posts.*, users.username, users.profile_pic, users.user_id
             FROM posts, users
             WHERE posts.user_id = users.user_id
             AND posts.is_published = 1
             AND posts.post_id = :id
			 LIMIT 1
        ');

        $result->execute(array('id' => $post_id));
        if ($result->rowCount()>0){
            while( $row = $result->fetch() ){	
				//testing the array
				//make nice pretty vars from the assoc array
				extract($row);

    ?>
            <section class="posts">
                <!-- posts go here -->
                <?php if($has_username){ ?>
                <section class="user-info">
                    <img class="profile-pic" src="<?php echo $profile_pic ?>" alt="<?php echo $username; ?>">
                    <div class="title-time">
                        <h2><a href="profile.php?user=<?php echo $user_id; ?>"><?php echo $username; ?></a></h2>
                        <div>
                            <h3><?php echo $title; ?></h3>
                            <h4><?php echo time_ago($date); ?></h4>
                        </div>
                    </div>
                </section>
                <?php } 
                else { ?>
                
                <section class="user-info">
                    <img class="profile-pic" src="img/anon.webp" alt="Anonymous User">
                    <div class="title-time">
                        <h2>Anonymous User</h2>
                        <div>
                            <h3><?php echo $title; ?></h3>
                            <h4><?php echo time_ago($date); ?></h4>
                        </div>
                    </div>
                </section>

                <?php } ?>   

                    <img class="post" src="<?php echo $post_img; ?>" alt="<?php echo $post_alt; ?>">

                <div class="under-post">
                <span class="likes">
                        <?php if($logged_in_user){
                            $viewer_id = $logged_in_user['user_id'];
                        }
                        else {
                            $viewer_id= 0;
                        }
                        if(!$logged_in_user){ ?>
                            <span class="solid-heart">❤</span><?php like_interface($post_id, $viewer_id); 
                            }else{
                                like_interface($post_id, $viewer_id);
                            }?>
                    </span>

                    <span class="next-to-likes">
                        <?php if($logged_in_user['is_admin']){
                        
                        if($logged_in_user){
                            $viewer_id = $logged_in_user['user_id'];
                        }
                        else {
                            $viewer_id= 0;
                        }
                            staff_picks($post_id, $viewer_id);
                        } ?>
                    </span>
                </div>
                <div class= "under-post">
                    <p><?php echo $body_desc; ?></p>
                </div>

            </section>

       <?php } 
       }//end DB if
       else {
           echo '<h2>No Posts Found.</h2>';
        } ?>

            <aside class= "poem-aside">
                <?php if($logged_in_user['user_id'] == $user_id){
                    if($allow_comments == 1){
                        $comments='on';
                     } else {
                         $comments = 'off';
                      } ?>
                    <h5>Toggle Comments <div class="toggle-bg"><span class="toggle-comments <?php echo $comments; ?>">●</span></div></h5>
                    <?php } ?>

                <?php if($allow_comments==1){ ?>
                    <?php include('incl/comments.php'); ?>
            
                <form id="add-comment" method="post" action="poem.php?post_id=<?php echo $post_id; ?>">
                    <label><h5>Add a Comment</h5></label>
                    <?php show_feedback( $feedback, $feedback_class, $errors ); ?>
                    <textarea name="comment"></textarea>
                    <input type="submit" value="Submit" class="button">
                    <input type="hidden" name="did_submit" value="1">
                </form>
                <?php }
                else { ?>
                        <h5>Comments are off for this post.</h2>
                 <?php }   ?>
            </aside>
            
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script>
        $('.toggle-bg').on('click', function(){
            let comments
            if($('.toggle-comments').hasClass('on')){
                $('.toggle-comments').removeClass('on').addClass('off').animate({'left':'17px'}, 200).css({'color':'#e67c69'});
                comments = 0
                
                
            }
            else if($('.toggle-comments').hasClass('off')){
                $('.toggle-comments').removeClass('off').addClass('on').animate({'left':'0px'}, 200).css({'color':'#84E296'});
                comments = 1
            }
            sendtoAjax(comments)
        });
        function sendtoAjax(i){
            let commentsVal = i
            $.ajax({
                
                    type: "POST",
                    url: "poem.php?post_id=<?php echo clean_int($_REQUEST['post_id']); ?>",
                    data: {
                        comments: commentsVal,
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
                        window.location.replace("poem.php?post_id=<?php echo $post_id; ?>");
                        });
                    }
        </script>
    </main>

    <?php
    $comments_on_off = clean_int($_REQUEST['comments']);
    $post_id = clean_int($_REQUEST['post_id']);
    $valid = clean_boolean($_REQUEST['valid']);
    if($logged_in_user['user_id'] == $user_id AND $valid == 1){

            $result = $DB->prepare(
                'UPDATE posts
                SET allow_comments = :allow
                WHERE posts.post_id = :post_id
                LIMIT 1
                ');
            
            $data = array(
                'allow'=>$comments_on_off,
                'post_id'=>$post_id
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

    <?php include('incl/footer.php'); ?>
