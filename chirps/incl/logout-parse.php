<?php
if( isset($_GET['action']) AND $_GET['action'] == 'logout'){
    //TODO: remove access token from DB

    setcookie('access_token', 0, time() - 99999);
    setcookie('user_id', 0, time() - 99999);
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}//end logout