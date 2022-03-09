               
                    
                    <div class="all-comments">
                        <?php //START COMMENT LOOP
                        $result = $DB->prepare(
                            'SELECT comments.*, users.username, users.user_id
                             FROM comments, users
                             WHERE comments.user_id = users.user_id
                             AND comments.post_id = :id
                             ORDER BY comments.date DESC
			                 LIMIT 20
                             '); 
                        $result->execute(array('id'=>$post_id));

                        if ($result->rowCount()>0){
                            while( $row = $result->fetch() ){	
                                //make nice pretty vars from the assoc array
                                extract($row);

                        ?>
                        <div class="single-comment">
                        <h5><a href="profile.php?user=<?php echo $username; ?>"><?php echo $username; ?></a></h5>
                            <p><?php echo $body; ?></p>
                        </div>
                        <?php } //end while
                        } ?>
                    </div>