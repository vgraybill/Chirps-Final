<?php

if ($logged_in_user['is_admin']) { 
    $check = $DB->prepare(
        'SELECT cp.*
        FROM card_packs AS cp
    ');
    $check->execute(); ?>
    
        <div class="openpacks"> 
            <ul>
                <li>Today's Avaliable Packs:</li>
        
    <?php
    
    while( $row = $check->fetch()){	
        extract($row);?>
    <li class='pack'><a href="incl/pack-parse.php?pack_id=<?php echo $pack_id; ?>"><?php echo $pack_name; ?></a></li>
    <li class="pack<?php echo $is_ava ?> toggle" data-pack="<?php echo $pack_id; ?>">☀</li>
<?php //TODO Make fetch file to switch avalibility
    }
}
else {
    $check = $DB->prepare(
        'SELECT cp.*
        FROM card_packs AS cp
        WHERE cp.is_ava = 1
    ');
    $check->execute(); ?>
    
        <div class="openpacks"> 
            <ul>
                <li>Today's Avaliable Packs:</li>
        
    <?php
    
    while( $row = $check->fetch()){	
        extract($row);
        ?>
            <li class="pack"><a href="incl/pack-parse.php?pack_id=<?php echo $pack_id; ?>"><?php echo $pack_name; ?></a></li>
        
        <?php } 
} //end while loop?>


    
</ul>

<?php
if(isset($_REQUEST['feedback'])){
    $feedback = clean_string($_REQUEST['feedback']);
    $errors = "<h2><span class='error'>You already have this pack</span></h2>";

    if($feedback=='success'){
        echo "<h2>You opened a pack!</h2>";
    }
    if($feedback=='error'){
        echo $errors;
    }
    
} ?>

</div>

