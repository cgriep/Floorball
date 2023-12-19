<?php
$int_id = $_POST['id'];
$str_date = $_POST['date'];
$int_id_league = $_POST['id_league'];
$Liga = DBM_Ligen::read_Liga($int_id_league);
$pokal = 0;
$id_kategorie = 0;
if($Liga) $id_kategorie = $Liga->get_int_id_kategorie();
if($id_kategorie == 3 || $id_kategorie == 4) $pokal = 1;

$int_id_place = $_POST['id_place'];
$str_spieltag_nr = $_POST['spieltag_nr'];

if(isset($_POST['save'])) {
  // erst die schiedsrichter holen
  //$schiri1 = 0;
  //$schiri2 = 0;
  //if($_POST['id_schiri1'])
  //  $schiri1 = DBMapper::read_Schiedsrichter($_POST['id_schiri1']);
  //if($_POST['id_schiri2'])
  //  $schiri2 = DBMapper::read_Schiedsrichter($_POST['id_schiri2']);
  //$str_schiri = "";
  //if($schiri1) $str_schiri .= $schiri1->get_str_name();
  //if($schiri2) $str_schiri .= ", ".$schiri2->get_str_name();
  //$str_schiri .= "#";
  //if($schiri1) $str_schiri .= $schiri1->get_int_id();
  //if($schiri2) $str_schiri .= ",".$schiri2->get_int_id();

  if($pokal) {
    $arr_t1 = explode("#", $_POST['id_team1']);
    $arr_t2 = explode("#", $_POST['id_team2']);
    $Begegnung = new Begegnung(-1, $int_id, NULL,
                               NULL, $_POST['g_time'],
                               $_POST['referees'], $_POST['g_number']);
    $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
    $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
  }
  else {
    $Begegnung = new Begegnung(-1, $int_id, $_POST['id_team1'],
                               $_POST['id_team2'], $_POST['g_time'],
                               $_POST['referees'], $_POST['g_number']);
  }
  
  $int_insert = DBMapper::write_Begegnung($Begegnung);
  if($_POST['g_playoff'] != "") {
    DBMapper::set_Playoff(0, $int_id_league, $int_insert, $_POST['g_playoff']);
  }
}

if(isset($_POST['set'])) {
  $arr_tmp = DBMapper::get_list_Begegnung($int_id);
  for($i=0; $i<count($arr_tmp[0]); $i++) {
    if($arr_tmp[6][$i] != $_POST["arr_forfeit".$i]) {
      $Begegnung = DBMapper::read_Begegnung($arr_tmp[0][$i]);
      $Begegnung->set_int_forfeit($_POST["arr_forfeit".$i]);
      DBMapper::update_Begegnung($Begegnung);
      unset($Begegnung);
    }
  }
  unset($arr_tmp);
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 4) == "del_") {
    $int_g_id = trim(substr($key,4));
    DBMapper::del_Begegnung($int_g_id);
  }
  else if(substr($key, 0, 5) == "save_") {
    $int_g_id = trim(substr($key, 5));
    $time = $_POST['t_'.$int_g_id];
    $id_t1 = $_POST['id_team1_'.$int_g_id];
    $id_t2 = $_POST['id_team2_'.$int_g_id];
    $ref = $_POST['ref_'.$int_g_id];
    $g_num = $_POST['g_num_'.$int_g_id];
    if($pokal) {
      $arr_t1 = explode("#", $id_t1);
      $arr_t2 = explode("#", $id_t2);
      $Begegnung = new Begegnung($int_g_id, $int_id, NULL,
                                 NULL, $time, $ref, $g_num);
      $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
      $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
    }
    else {
      $Begegnung = new Begegnung($int_g_id, $int_id, $id_t1, $id_t2, $time,
                                 $ref, $g_num);
    }
    DBMapper::update_Begegnung($Begegnung);
  }
}

if(isset($_POST['back'])) {
  $nav = "setup_matches";
  $bool_show_data = 1;
}

if($int_id < 1 || $int_id_league < 1) {
  echo "Der Spieltag muss mindestens einmal gespeichert worden sein eh die Begegnungen angelegt werden k&ouml;nnen. Oder es wurde keine Liga ausgew&auml;hlt.";
  $nav = "setup_matches";
}
?>