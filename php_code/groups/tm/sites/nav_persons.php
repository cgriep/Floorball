<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "persons";

// handel events and set new nav
  switch($nav) {
  default: 
    include('groups/tm/handlers/persons.php');
}

// load next formular
switch($nav) {
default:
  include('groups/tm/sites/persons.php');            
}
?>
