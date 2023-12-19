<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "clubs";

// handel events and set new nav
switch($nav) {
  case 'teams':
    include('groups/sbk/handlers/teams.php');
  break;
  case 'player':
    include('groups/sbk/handlers/player.php');
  break;
  case 'persons':
    include('groups/sbk/handlers/persons.php');
  break;
  case 'clubs':
   default: 
    include('groups/sbk/handlers/clubs.php');            
}

// load next formular
  switch($nav) {
    case 'teams':
      include('groups/sbk/sites/teams.php');
    break;
    case 'player':
      include('groups/sbk/sites/player.php');
    break;
    case 'persons':
      include('groups/sbk/sites/persons.php');
    break;
    case 'clubs':
     default: 
      include('groups/sbk/sites/clubs.php');            
}
?>
