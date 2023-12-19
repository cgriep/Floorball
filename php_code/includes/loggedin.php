<?php

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}


// die verfügbaren seiten laden
if(Login::isInGroup("ADMIN")) {
  include('groups/sbk/includes/.htallow_sites.php');
  ini_set("display_errors", 1);
  //Statistik::create_tables(6);
}
if(Login::isInGroup("SBK")) {
  include('groups/sbk/includes/.htallow_sites.php');
//  ini_set("display_errors", 1);
}
else if(Login::isInGroup("SK")) {
  include('groups/sk/includes/.htallow_sites.php');
}
else if(Login::isInGroup("VM")) {
  include('groups/vm/includes/.htallow_sites.php');
}
else if(Login::isInGroup("TM")) {
  include('groups/tm/includes/.htallow_sites.php');
}

// wurde "seite" übergeben
if (isset($_GET['seite'])) {
  $str_seite = $_GET['seite'];
  // existiert die gewünschte Seite
  if (array_key_exists($str_seite, $arr_allow_sites)) {
    include ($arr_allow_sites[$str_seite]);
  } else {
    echo "<br />Die gewünschte Seite $str_seite existiert nicht oder konnte nicht
          geladen werden.<br />";
  }
} else {
  echo "<br />Bitte wählen Sie im Menü aus, was Sie tun möchten.<br />";
}

?>