<?php

    if(isset($_GET['nav'])) {
        $nav = $_GET['nav'];
    }
    else if(isset($_POST['nav'])) {
        $nav = $_POST['nav'];
    }
    else $nav = "setup_matches";

    // handel events and set new nav
    switch($nav) {
        case "new_place":
          include("groups/sbk/handlers/new_place.php");
          break;
        case "edit_games":
          include("groups/sbk/handlers/edit_games.php");
          break;
        case "setup_matches":
        default: 
          include('groups/sbk/handlers/setup_matches.php');            
    }
    
    // load next formular
    switch($nav) {
        case "new_place":
          include("groups/sbk/sites/new_place.php");
          break;
        case "edit_games":
          include("groups/sbk/sites/edit_games.php");
          break;
        case "setup_matches":
        default: 
          include('groups/sbk/sites/setup_matches.php');            
    }
?>