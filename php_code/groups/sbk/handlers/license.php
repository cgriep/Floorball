<?php
$bool_show_table = false;
$int_id_league = 0;
// Ligen aus Datenbank laden

$arr_tmp = DBM_Ligen::get_list_Liga();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_ligen[0][$i] = $arr_tmp[0][$i];
    $arr_ligen[1][$i] = $arr_tmp[1][$i];
}

$arr_seasons[0][0] = 1;
$arr_seasons[1][0] = "Saison 2007/2008";

$arr_status_lizenz = DBMapper::get_list_status_lizenz();

if(isset($_POST['save'])) {
  foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 5) == "check") {
      $arr_check = explode(":", $value);
      // hack hier sind die lizenz id's fest verwendet
      $lizenz = 0;
      if($arr_check[0] == 0 && $arr_check[1] != 1) $lizenz = 1;
      else if($arr_check[0] == 1 && $arr_check[1] != 3) $lizenz = 3;
      else if($arr_check[0] == 2 && $arr_check[1] != 2) $lizenz = 2;
      else if($arr_check[0] == 3 && $arr_check[1] != 6) $lizenz = 6;
      else if($arr_check[0] == 4 && $arr_check[1] != 7) $lizenz = 7;
      if($lizenz)
        DBMapper::update_Lizenz($arr_check[2], $arr_check[3], $lizenz);
    }
    unset($arr_check);
  }
}

if(isset($_POST['save_all'])) {
  foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 5) == "check") {
      $arr_check = explode(":", $value);
      $arr_lizenz = DBM_Spieler::get_last_Spieler_Lizenzstatus($arr_check[2], $arr_check[3]);
      // --WARNING--
      if($arr_lizenz[0] == 2) {
        DBMapper::update_Lizenz($arr_check[2], $arr_check[3], $arr_status_lizenz[0][0]);
      }
    }
    unset($arr_check);
  }
}

if(isset($_POST['int_id_league'])) {
  $int_id_league =  $_POST['int_id_league'];
  // read licenses from database
  $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id_league);
  $arr_licenses = array();
  for($i=0; $i<count($arr_teams[0]); $i++) {
    $arr_licenses[] = DBM_Spieler::get_list_Spieler_of_Mannschaft($arr_teams[0][$i]);
  }
  $bool_show_table = true;
}

if(isset($_POST['read_file'])) {
  $str_file = $_FILES['l_file']['tmp_name'];//$_POST['l_file'];
  $int_id = $_POST['id'];
  LizenzInterface::read_from_file($str_file, $int_id);
}
?>
