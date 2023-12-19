<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "schiri";

// handel events and set new nav
  switch($nav) {
  default: 
    include('groups/sk/handlers/schiri.php');
}

// load next formular
switch($nav) {
default:
  include('groups/sk/sites/schiri.php');            
}
?>
