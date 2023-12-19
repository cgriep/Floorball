<?php

$bool_show_data = 0;
$int_id_league = 0;

if(isset($_POST['show'])) {
  $int_id_league = $_POST['id_league'];
}

foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 5) == "list1") {
        if($value == "Neuen Spieltag anlegen") {
            $bool_show_data = 1;
            $int_id = -1;
            $str_date = "";
            $int_id_league = $_POST['id_league'];
            $int_id_place = 0;
            $str_spieltag_nr = "";
        }
        else {
            // load from db
            $int_id = trim(substr($key,6));
            $Spieltag = DBMapper::read_Spieltag($int_id);
            if($Spieltag) {
              $bool_show_data = 1;
              $str_date = $Spieltag->get_date_datum();
              $int_id_league = $Spieltag->get_int_id_liga();
              $int_id_place = $Spieltag->get_int_id_spielort();
              $str_spieltag_nr = $Spieltag->get_str_spieltag_nr();
            }
        }
    }
}

if(isset($_POST['save'])) {
    $int_id = $_POST['id'];
    $str_date = $_POST['date'];
    $int_id_league = $_POST['id_league'];
    $int_id_place = $_POST['id_place'];
    $str_spieltag_nr = $_POST['spieltag_nr'];
    $Spieltag = new Spieltag($int_id, $int_id_place, $int_id_league,
                             $str_date, $str_spieltag_nr);
    if($int_id > 0) DBMapper::update_Spieltag($Spieltag);
    else $int_id = DBMapper::write_Spieltag($Spieltag);
    $bool_show_data = 1;
}

if(isset($_POST['new_place'])) {
  $int_id = $_POST['id'];
  $str_date = $_POST['date'];
  $int_id_league = $_POST['id_league'];
  $int_id_place = $_POST['id_place'];
  $str_spieltag_nr = $_POST['spieltag_nr'];
  $nav = "new_place";
}

if(isset($_POST['edit_games'])) {
  $int_id = $_POST['id'];
  $str_date = $_POST['date'];
  $int_id_league = $_POST['id_league'];
  $int_id_place = $_POST['id_place'];
  $str_spieltag_nr = $_POST['spieltag_nr'];
  $nav = "edit_games";
}

?>
