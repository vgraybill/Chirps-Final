<footer>
	<ul>
		<li><a href="index.php">Home</li>
		<li><a href="aboutus.php">About Us</li>
		<li><a href="tos.php">Terms of Service</li>
		<li><a href="login.php?action=logout">Log Out</li>
	</ul>
</footer>

    <?php if($logged_in_user['is_admin']){ ?>
		<script type="text/javascript">
		//LIKE/UNLIKE	
		document.body.addEventListener('click', function(e){
			if (e.target.className == 'star-button'){
			//console.log(e.target.dataset.postid)
			adminLike(e.target)
			}
		});

		async function adminLike( el ){
			let postId = el.dataset.postid
			let userId = <?php echo $logged_in_user['user_id']; ?>;
			//get the container that will be updated after liking
			let container = el.closest('.next-to-likes')

			//console.log(postId, userId)
			let formData = new FormData()
			formData.append('postId', postId)
			formData.append('userId', userId)
			
			let response = await fetch("fetch/staff-like.php", {
				method:'POST',
					body: formData
			})
			if (response.ok) {
				let result = await response.text()
					// console.log('ok')
				container.innerHTML = result
				
			} else {
				console.log(response.status)
			}
		}
		</script>
	<?php } ?>
	<!-- end script  Additions -->
	<?php if($logged_in_user['is_admin']){ ?>
		<script type="text/javascript">
		//LIKE/UNLIKE	
		$('.toggle').on('click', function(e){
			button=$(this);
			packid=button.attr('data-pack')
			if($(this).hasClass('pack1')){
				valid=true;
             $.ajax({
                    type: "GET",
                    url: "makepoetry.php",
                    data: {
                            is_ava: 0,
							pack_id: packid,
							valid:1
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
						button.removeClass('pack1').addClass('pack0');
                        },
                        'error': function(xhr, text_status, error_thrown) {
                        console.log('ERROR!', text_status, error_thrown);
                        }
                    }
                        ).done(function(o) {
                            location.reload();
                        });
			}
			if($(this).hasClass('pack0')){
				valid=true;
             $.ajax({
                    type: "GET",
                    url: "makepoetry.php",
					dataType: 'text',
                    data: {
                            is_ava: 1,
							pack_id: packid,
							valid:1
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
						button.removeClass('pack0').addClass('pack1');
                        },
                        'error': function(xhr, text_status, error_thrown) {
                        console.log('ERROR!', text_status, error_thrown);
                        }
                    }
                        ).done(function(o) {
							location.reload();
                        });
			}
		});


                
</script>
<?php } ?>
	<!-- end script Pack on and Off -->

    <!-- Additions to the footer. Deferred JS scripts. Add this before </body>-->
<?php if($logged_in_user){ ?>
		<script type="text/javascript">
		//LIKE/UNLIKE	
		document.body.addEventListener('click', function(e){
			if (e.target.className == 'heart-button'){
			//console.log(e.target.dataset.postid)
			likeUnlike(e.target)
			}
		});

		async function likeUnlike( el ){
			let postId = el.dataset.postid
			let userId = <?php echo $logged_in_user['user_id']; ?>;
			//get the container that will be updated after liking
			let container = el.closest('.likes')

			//console.log(postId, userId)
			let formData = new FormData()
			formData.append('postId', postId)
			formData.append('userId', userId)
			
			let response = await fetch("fetch/like-unlike.php", {
				method:'POST',
					body: formData
			})
			if (response.ok) {
				let result = await response.text()
					// console.log('ok')
				container.innerHTML = result
				
			} else {
				console.log(response.status)
			}
		}
		</script>
        
	<?php } ?>

	<!-- end script  Additions -->
</body>
</html>