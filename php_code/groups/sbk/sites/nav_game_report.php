<?php

    if(isset($_GET['nav'])) {
        $nav = $_GET['nav'];
    }
    else if(isset($_POST['nav'])) {
        $nav = $_POST['nav'];
    }
    else $nav = "game_list";

    // handel events and set new nav
    switch($nav) {
        case "game_report":
          include("groups/sbk/handlers/game_report.php");
          break;
        case "game_list":
        default: 
          include('groups/sbk/handlers/game_list.php');            
    }
    
    // load next formular
    switch($nav) {
        case "game_report":
          include("groups/sbk/sites/game_report.php");
          break;
        case "game_list":
        default: 
          include('groups/sbk/sites/game_list.php');            
    }
?>