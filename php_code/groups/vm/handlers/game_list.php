<?php
/**
 * Logik für die ...
 *
 * @package    VM
 * @subpackage Handles
 */
$bool_show_data = 0;
$int_id_liga = 0;
$int_id_menu = 0;
$int_id_matchday = 0;

if(isset($_POST['select_day']) && $_POST['list'] != 0) {
  $int_id_matchday = $_POST['list'];
  $bool_show_data = 1;
}

if(isset($_POST['gf'])) {
  $int_id_liga = 0;
  $int_id_menu = 1;
  $bool_show_data = 1;
  $arr_ligen = DBM_Ligen::get_ligen_by_sortierung(1, 1);
}

if(isset($_POST['kf'])) {
  $int_id_liga = 0;
  $int_id_menu = 2;
  $bool_show_data = 1;
  $arr_ligen = DBM_Ligen::get_ligen_by_sortierung(1, 2);
}

if(isset($_POST['jugend'])) {
  $int_id_liga = 0;
  $int_id_menu = 3;
  $bool_show_data = 1;
  $arr_ligen = DBM_Ligen::get_ligen_by_sortierung(1, 3);
}

if(isset($_POST['statistics'])) {
  $int_id_liga = $_POST['id_liga'];
  //Statistik::create_all_tables();
  Statistik::create_tables($int_id_liga);
}

if(isset($_POST['show'])) {
  $int_id_liga = $_POST['id_liga'];
  $bool_show_data = 1;
}

if(isset($_POST['laden'])) {
  $int_id_liga = $_POST['id_liga'];
  $int_id_matchday = $_POST['list'];
  $bool_show_data = 1;
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "game_") {
    $int_id_match = trim(substr($key,5));
    $int_id_matchday = $_POST['id_matchday'];
    $nav = "game_report";
  }
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 9) == "matchday_") {
   $int_id_liga = $_POST['id_liga'];
    //$int_id_menu = $_POST['id_menu'];
    $int_id_matchday = trim(substr($key, 9));
    $bool_show_data = 1;
    //$arr_ligen = DBM_Ligen::get_ligen_by_sortierung(1, $int_id_menu);
  }
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list_") {
    $int_id_matchday = trim(substr($key,5));
    $bool_show_data = 1;
    $arr_ligen = array();
  }
}

?>