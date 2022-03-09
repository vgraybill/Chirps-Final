<?php 

$logged_in_user = check_login();
if($logged_in_user){
    $color = $logged_in_user['color_scheme'];
} else {
    $color= 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirps - Poetry Snippets</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/colors<?php echo $color; ?>.css">
    <link rel="icon" 
      type="image/png" 
      href="../img/icon.png">
    <script src="https://kit.fontawesome.com/843ca2f650.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="wrapped">
            <div class="flex">
                <h1><a href="index.php">Chirps</a></h1>
                <div>
                    <?php
                    if($logged_in_user){ ?>
                        <a href="makepoetry.php" class="button">Make Poetry</a>
                    <?php } else { ?>
                    <a href="login.php?action=make" class="button">Make Poetry</a>
                    <?php } ?>
                </div>
            </div>
            <div class="login-search">
                <?php
                if($logged_in_user){ ?>
                    <h5><a href="profile.php?user=<?php echo $logged_in_user['user_id']; ?>">Hello, <?php echo $logged_in_user['username']; ?></a></h5>
                <?php 
                } else { ?>
                    <h5><a href="login.php?action=">Log In</a></h5>
                <?php } ?>
                <form class="searchform" method="get" action="search.php">
				<label class="screen-reader-text" for="phrase"></label>
				<input type="search" name="phrase" id="phrase">
				<input type="submit" class="hidden">
			</form>
            </div>
        <div>
    </header>
 