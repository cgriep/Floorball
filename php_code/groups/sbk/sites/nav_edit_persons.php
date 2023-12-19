<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "edit_persons";

// handel events and set new nav
  switch($nav) {
  default: 
    include('groups/sbk/handlers/edit_persons.php');
}

// load next formular
switch($nav) {
default:
  include('groups/sbk/sites/edit_persons.php');            
}
?>
