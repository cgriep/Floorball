<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "vmanager";

// handel events and set new nav
  switch($nav) {
  case "persons":
    include('groups/tm/handlers/new_persons.php');
    break;
  case "player":
    include('groups/tm/handlers/new_player.php');
    break;
  default: 
    include('groups/tm/handlers/vmanager.php');            
}

// load next formular
switch($nav) {
case "persons":
  include('groups/tm/sites/new_persons.php');
  break;
case "player":
  include('groups/tm/sites/new_player.php');
  break;
default: 
  include('groups/tm/sites/vmanager.php');            
}
?>
