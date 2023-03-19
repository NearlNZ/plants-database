<?php
    //Unset all session (only this app)
    session_start();
    $prefix = 'BPCS-session';
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            unset($_SESSION[$key]);
        }
    }

    //Redirect to index page
    header('Location: login');
    exit();
?>