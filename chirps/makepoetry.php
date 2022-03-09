<?php
    require('config.php');
    include_once('incl/functions.php');

    include('incl/header.php');

    if (!$logged_in_user){
        header('Location:index.php');
    } 
    else {
        //Querying the DB for the words avalible to the user
        $request = $DB->prepare(
            'SELECT u.user_id, cp.*, w.*, cpw.*, ucp.*
            FROM users AS u, card_packs AS cp, words AS w, card_pack_words AS cpw, user_car_packs AS ucp
            WHERE ucp.user_id = :userid
            AND ucp.user_id = u.user_id
            AND ucp.pack_id = cp.pack_id
            AND cp.pack_id = cpw.pack_id
            AND cpw.word_id = w.word_id
            ORDER BY w.word ASC
            ');

        $request->execute(array('userid'=>$logged_in_user['user_id'])); ?>


    <main>

    <?php include('incl/open-packs.php') ?>

        <div class='poetry-int'>

        <?php
                if ($request->rowCount()>0){ ?>
                <div class='word-nav'>
                    <ul>
                        <li class="noun">Nouns</li>
                        <li class="adjective">Adjectives</li>
                        <li class="verb">Verbs</li>
                        <li class="adverb">Adverbs</li>
                        <li class="preposition other">Other</li>
                        <li class="ends">Ends</li>
                        <li class="punctuation">Punctuation</li>
                        <li class="all">All</li>
                    </ul>
                </div>
            <div class="word-box noun current-type">

                <div class="words nouns">
                <?php
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'noun' OR $type == 'pronoun' OR $type==''){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box adjective">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'adjective' OR $type == '' OR $type==''){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box verb">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'verb' OR $type == '' OR $type==''){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box adverb">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'adverb' OR $type == '' OR $type==''){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box other">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'article' OR $type == 'preposition' OR $type=='conjunction'){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>
            
            <div class="word-box ends">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'ends'){    ?>
                        <div class='<?php echo $type ?> word' data-attr='<?php echo $is_inf ?>' data-word="<?php echo $word ?>" data-type='<?php echo $type ?>'><?php echo $word ?></div>
                        <?php
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box punk">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        if ($type == 'punctuation'){    
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        }
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>

            <div class="word-box all">
                <div class="words">
                <?php
                    $request->execute();
                    while( $row = $request->fetch() ){	
                        extract($row);
                        echo "<div class='$type word' data-attr='$is_inf' data-word='$word' data-type='$type'>$word</div>";
                        //TODO how does javascript know what word this is?
                        ?>
                        
                    <?php       
                    }//endwhile ?>
                </div>
            </div>
            <div class="poetry-maker">
            
            <div class="poem">
                <div class="line">
                </div>
            </div>
            <div class="controls">
                <!-- <p class="add" attr="1">add a line</p>
                <p class="remove">remove a line</p> -->
                <h5 class="make">reset</h5>
            </div>
            <span class="error poem-error"></span>
        </div>

    </div>

    <div class="submit-poem">
            <form method="post" action="">
                <div class="input">
                    <label>Title <span class='title error'></span> <input type='text' name='title' required></label>
                    <label>Description <span class="body error"></span> <textarea name='body_desc' required></textarea></label>
                </div>
                <div class="checks">
                    <div>
                        <label><input type="checkbox" name="has_username" checked>Show Username</label>
                        <label><input type="checkbox" name="allow_comments" checked>Allow Comments</label>
                        <label><input type="checkbox" name="is_published">Make Private</label>
                    </div>
                    <div>
                        <input type="button" value="Submit" class="button">
                        <input type="hidden" name="did_submit" value=1>
                    </div>
                </div>

            </form>
    </div>


               <?php } else { ?>
        <h2> Lets get you started! </h2>
        <p>Welcome to the Chirp maker! Above are the word packs avaliable for you to open. The basic pack comes with a lot of useful conjunctions, word endings and punctuation.</p>
            
        <?php }
    }//end ifloggedin ?>


    
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/html2canvas.js"></script>
    <script>
        let poem = [];
        

        $('.button').on('click', function(){
              // poem.forEach(element => console.log(element));
            let form = $('form').serializeArray()
            let checkbox = $('input:checkbox').map(function() {
            return { name: this.name, value: this.checked ? this.value : "false" };
            });

            let title = form[1].value,
                body = form[2].value;
                // hasUsername = form[3].value,
                // allowComments = form[4].value,
                // isPublished = form[5].value;
                // didSubmit = form[6].value;
            let hasUsername = checkbox[0].value,
                allowComments = checkbox[1].value,
                isPublished = checkbox[2].value;
            console.log(checkbox, form)

            let valid = true;

            if(isPublished=='on'){
                isPublished=0;
            }else{
                isPublished=1;
            }
            if(allowComments=='on'){
                allowComments=1;
            }else{
                allowComments=0;
            }
            if(hasUsername=='on'){
                hasUsername=1;
            }else{
                hasUsername=0;
            }
            if(poem!=''){
                $('.poem-error').text('');
                valid= true
            }
            if (title != ''){
                 $('.title').text('');
                valid=true;
            }
            if(poem==''){
                $('.poem-error').text('Chirp Cannot be Blank');
                valid= false
            }
            if(title == ''){
                $('.title').text('Required');
                valid = false
            }
            if (body == ''){
                body = ' ';
            }

            if(valid){
                didSubmit=1;
                poem = poem.join(' ');
                post_alt = poem.toString();
            html2canvas(document.querySelector(".poem"), {
                scale: 5
                }).then(canvas => {
                var dataURL = canvas.toDataURL('');
                
                // var img = new Image;
                // img.src = dataURL;
                // img = dataURL;
                // console.log(img.src);
                
                $.ajax({
                            type: "POST",
                            url: "makepoetry.php",
                            data: {
                                'image': dataURL,
                                'title': title,
                                'body': body,
                                'hasUsername': hasUsername,
                                'allowComments': allowComments,
                                'isPublished': isPublished,
                                'didSubmit': didSubmit,
                                'post_alt': post_alt,
                                'image': dataURL,
                                'user_id': <?php echo $logged_in_user['user_id'] ?>
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
                            window.location.replace("index.php");
                        });
                });
            }
                
    });




    $('.make').on('click', function(){
        location.reload();
    });
        //Changes the words being displayed
        $('li').on('click', function(){
            let liType= $(this)
            if (liType.hasClass('noun')){
                $('.current-type').removeClass('.current-type').fadeOut(1);
                $('.word-box.noun').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('adjective')){
                $('.current-type').removeClass('.current-type').fadeOut(1);
                $('.word-box.adjective').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('verb')){
                $('.current-type').removeClass('.current-type').fadeOut(1);
                $('.word-box.verb').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('adverb')){
                $('.current-type').removeClass('.current-type').fadeOut(1);
                $('.word-box.adverb').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('ends')){
                $('.current-type').removeClass('.current-type').css({'display':'none'});
                $('.word-box.ends').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('punctuation')){
                $('.current-type').removeClass('.current-type').css({'display':'none'});
                $('.word-box.punk').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('other')){
                $('.current-type').removeClass('.current-type').css({'display':'none'});
                $('.word-box.other').addClass('current-type').fadeIn(200);
            }
            if (liType.hasClass('all')){
                $('.current-type').removeClass('.current-type').css({'display':'none'});
                $('.word-box.all').addClass('current-type').fadeIn(200);
            }
        });

        //TODO:
        //check for if word is infinite or not, if it is, set attribute to 2, if its not, then do nothing, grey used words.
        let poemWordCount=0;

        $('.word').on('click', function(){
            let theWord = $(this).attr('data-word');
            let theType = $(this).attr('data-type');
            let theCount = $(this).attr('data-count');
            poem.push(theWord);


            if($(this).attr('data-attr') == 0){
                //add word to poem, grey out, off click, set data-attr to 2
                poemWordCount ++
                if (poemWordCount < 30){
                    $('.line').append("<div class='word-holder"+poemWordCount+" word-holder' data-count='"+poemWordCount+"'></div>");

                    $('.word-holder'+poemWordCount).html("<div class='word "+theType+"' data-attr='2' data-word='"+theWord+"'>"+theWord+"</div>");

                    // $(this).attr('data-attr','3').addClass('greyed');
                }
            }
            if($(this).attr('data-attr') == 1){
                //add word to poem
                poemWordCount ++
                if (poemWordCount < 50){
                    $('.line').append("<div class='word-holder"+poemWordCount+"' data-count='"+poemWordCount+"'></div>");

                    $('.word-holder'+poemWordCount).html("<div class='word "+theType+"' data-attr='2' data-word='"+theWord+"'>"+theWord+"</div>");
                }
            }

        });
        
    </script>
    <?php 


//incoming data (from js fetch)
$title = clean_string($_POST['title']);
$body = clean_string($_POST['body']);
$has_username = clean_boolean($_POST['hasUsername']);
$allow_comments = clean_boolean($_POST['allowComments']);
$is_published = clean_boolean($_POST['isPublished']);
$post_alt = clean_string($_POST['post_alt']);
$user_id = clean_int($_POST['user_id']);
$did_submit = clean_boolean($_POST['didSubmit']);
$uploaded_file = $_REQUEST['image'];
//if the user submitted the form
if($did_submit==1){
$target_directory = 'img/posts/';
$cnvimg = trim(strip_tags($_POST['image']));
$cnvimg = str_replace(['data:image/png;base64,', ' '], ['', '+'], $cnvimg);

//set image name from 'imgname', or unique name set with uniqid()
$imgname = sha1( microtime() );

//get image data from base64 and save it on server
$data = base64_decode($cnvimg);
$file = $target_directory . $imgname .'.png'; 
$save = file_put_contents($file, $data);
        
			$result = $DB->prepare( 'INSERT INTO posts
			( title, post_img, post_alt, body_desc, has_username, date, user_id, allow_comments, is_published )
			VALUES
			(:title, :image, :alt, :body, :has, now(), :user_id, :allow, :is_pub)' );

			$data = array(
                'title' => $title,
				'image' => $file,
                'alt' => $post_alt,
                'body' => $body,
                'has' => $has_username,
				'user_id' => $user_id,
                'allow' => $allow_comments,
                'is_pub' => $is_published,
			);

			$result->execute($data);
			//check it - if it worked, redirect to step 2, otherwise show feedback
			if( $result->rowCount() > 0 ){
				//success redirect to step 2
				$post_id = $DB->lastInsertId();
				header("Location:poem.php?post_id=$post_id");
			}else{
				$feedback = 'Your post could not be saved';
				$feedback_class = 'error';
				if(DEBUG_MODE){
					$errors[] = 'database issue';
				}
			}
        }


//end upload parser ?>

<?php
    $pack_id = clean_int($_REQUEST['pack_id']);
    $is_ava = clean_int($_REQUEST['is_ava']);
    $valid = clean_boolean($_REQUEST['valid']);
    if($valid == 1){
        $check = $DB->prepare(
            'SELECT cp.*
            FROM card_packs AS cp
            WHERE cp.pack_id = :pack_id
            ');
        
        $check->execute(array('pack_id'=>$pack_id));
        if($check->rowCount()>0){
            $result = $DB->prepare(
                'UPDATE card_packs
                SET is_ava = :is_ava
                WHERE card_packs.pack_id = :pack_id
                LIMIT 1
                ');
            
            $data = array(
                'is_ava'=>$is_ava,
                'pack_id'=>$pack_id
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
    } ?>


    <?php include('incl/footer.php'); ?>