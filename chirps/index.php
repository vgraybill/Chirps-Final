<?php
    require('config.php');
    include_once('incl/functions.php');

    include('incl/header.php');
    ?>


    <main>
        <div class="wrapper">
        <div class="posts">
    <?php
        $result = $DB->prepare(
            'SELECT posts.*, users.username, users.profile_pic, users.user_id
             FROM posts, users
             WHERE posts.user_id = users.user_id
             AND posts.is_published=1
             ORDER BY posts.date DESC
			 LIMIT 50
        ');

        $result->execute();
        //"check it" - are there at least 1 row (post) in the result
        if( $result->rowCount() >= 1 ){	
            //"loop it" - go through the result set one row at a time
            while( $row = $result->fetch() ){	
                //testing the array
                //print_r( $row );
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

                <a href="poem.php?post_id=<?php echo urlencode($post_id); ?>">
                    <img class="post" src="<?php echo $post_img; ?>" alt="<?php echo $post_alt; ?>">
                </a>

                <div class="under-post">
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
                    <div class="under-post">
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


                <?php if($allow_comments==1){ ?>
                <div class="under-post">
                    <i class="fa-regular fa-message"></i>
                    <a href='poem.php?post_id=<?php echo $post_id; ?>'><p>Comments...</p></a>
                </div>
                <?php } ?>
                
                
            </section>

       <?php } //end while
       }//end DB if
       else {
           echo '<h2>No Posts Found.</h2>';
        } ?>
        </div>

            <aside>
                <div class="aside-wrapper">
                    <div class="aside-header">
                        <i class="fa-solid fa-calendar-day"></i>
                        <h4>Today's Top Post</h4>
                    </div>
                    <?php echo show_todays_top_post(); ?>
                </div>
                <div class="aside-wrapper">
                    <div class="aside-header">
                        <i class="fa-solid fa-mug-hot"></i>
                        <h4>Staff Picks</h4>
                    </div>
                    <?php echo staff_picks_display(); ?>
                </div>
            </aside>
        </div>



    </main>
    <?php include('incl/footer.php'); ?>
