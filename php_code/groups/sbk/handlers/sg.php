<?php
$bool_show_data = 0;
// Ligen aus Datenbank laden

$arr_sg = DBMapper::get_list_SG();

if(isset($_POST['add_club'])) {
  $int_id = $_POST['id'];
  $int_c_id = $_POST['add_club_id'];
  // hier haben wir nicht viel zu berücksichtigen
  if(DBMapper::write_SG($int_c_id, $int_id))
    $my_message->add_message("Der Verein wurde der SG zugefügt.");
  else
    $my_message->add_error("Datenbankfehler.");
  $Team = DBMapper::read_Mannschaft($int_id);
  if($Team) {
    $bool_show_data = 1;
    $str_name = $Team->get_str_name();
    $arr_tmp = DBMapper::get_list_Verein_of_SG($int_id);
    if($arr_tmp) {
      for($i=0; $i<count($arr_tmp); $i++) {
        $tmp_club = DBMapper::read_Verein($arr_tmp[$i]);
        $arr_sg_clubs[0][] = $tmp_club->get_int_id();
        $arr_sg_clubs[1][] = $tmp_club->get_str_name();
      }
    }
  }
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    // load from db
    $int_id = trim(substr($key,6));
    $Team = DBMapper::read_Mannschaft($int_id);
    if($Team) {
      $bool_show_data = 1;
      $str_name = $Team->get_str_name();
      $arr_tmp = DBMapper::get_list_Verein_of_SG($int_id);
      if($arr_tmp) {
        for($i=0; $i<count($arr_tmp); $i++) {
          $tmp_club = DBMapper::read_Verein($arr_tmp[$i]);
          $arr_sg_clubs[0][] = $tmp_club->get_int_id();
          $arr_sg_clubs[1][] = $tmp_club->get_str_name();
        }
      }
    }
  }
  else if(substr($key, 0, 3) == "rem") {
    // Verein aus SG löschen
    // Team id
    $int_id = $_POST['id'];
    // Verein id
    $int_c_id = trim(substr($key,4));
    // sind Lizenzen von dem Verein in der SG?
    // Spieler zu dem Team holen und Verein holen
    $arr_spieler = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_id, $int_c_id);
    if($arr_spieler && count($arr_spieler[0]) == 0) {
      if(DBMapper::delete_Verein_SG($int_id, $int_c_id))
        $my_message->add_message("Verein aus Spielgemeinschaft entfehrnt.");
      else
        $my_message->add_error("Datenbankfehler");
    }
    else {
      if($arr_spieler) {
        $my_message->add_error("Der Verein kann nicht aus der Spielgemeinschaft entfernt werden da Spielerlizenzen des Vereins bei der SG bestehen.");
      }
      else {
        $my_message->add_error("Datenbankfehler.");
      }
    }
    $bool_show_data = 1;
    $Team = DBMapper::read_Mannschaft($int_id);
    if($Team) {
      $bool_show_data = 1;
      $str_name = $Team->get_str_name();
      $arr_tmp = DBMapper::get_list_Verein_of_SG($int_id);
      if($arr_tmp) {
        for($i=0; $i<count($arr_tmp); $i++) {
          $tmp_club = DBMapper::read_Verein($arr_tmp[$i]);
          $arr_sg_clubs[0][] = $tmp_club->get_int_id();
          $arr_sg_clubs[1][] = $tmp_club->get_str_name();
        }
      }
    }
  }
}

?>