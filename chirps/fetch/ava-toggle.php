<?php
    $pack_id = clean_int($_REQUEST['pack_id']);
    $is_ava = clean_int($_REQUEST['is_ava']);

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