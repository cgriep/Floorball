<?php

    if(isset($_GET['nav'])) {
        $nav = $_GET['nav'];
    }
    else if(isset($_POST['nav'])) {
        $nav = $_POST['nav'];
    }
    else $nav = "accounts";

    // handel events and set new nav
    switch($nav) {
            default: 
                include('groups/sbk/handlers/accounts.php');            
        }
    
    // load next formular
    switch($nav) {
            default: 
                include('groups/sbk/sites/accounts.php');            
        }
?>