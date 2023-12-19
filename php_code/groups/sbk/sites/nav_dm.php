<?php

if(isset($_GET['nav'])) {
  $nav = $_GET['nav'];
}
else if(isset($_POST['nav'])) {
  $nav = $_POST['nav'];
}
else $nav = "dm";

// handel events and set new nav
switch($nav) {
default: 
  include('groups/sbk/handlers/dm.php');            
}

// load next formular
switch($nav) {
default: 
  include('groups/sbk/sites/dm.php');            
}
?>