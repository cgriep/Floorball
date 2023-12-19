<?php

$bool_show_data = 1;

if(isset($_POST['save'])) {
  $bool_show_data = 1;
  $nav = "";
  $int_t_id = $_SESSION['temp']['t_id'];
  $user=DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
  $club_id = $user->get_int_id_verein();;
  $club_name = $user->get_str_verein_name();

  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];

  $arr_date = explode(".", $str_date);
  if(count($arr_date) == 3) {
    if(strlen($arr_date[0]) == 1) $day = "0".$arr_date[0];
    else $day = $arr_date[0];
    if(strlen($arr_date[1]) == 1) $month = "0".$arr_date[1];
    else $month = $arr_date[1];
    if($arr_date[2] < 30 && strlen($arr_date[2]) == 2) {
      $year = "20".$arr_date[2];
    }
    else if($arr_date[2] < 100 && strlen($arr_date[2]) == 2)
      $year = "19".$arr_date[2];
    else $year = $arr_date[2];
  }
  $str_date = $day.".".$month.".".$year;

  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
  $Player = new Spieler(-1, $str_pl_name, $str_pl_name2,
                        $bool_man, $str_date, $int_pl_nation, $str_pl_street,
                        $str_pl_nbr, $str_pl_plz, $str_pl_place);
  if(DBM_Spieler::check_Spieler($Player)) {
    $my_message->add_error("Der Spieler wurde bereits angelegt. Bitte bei dem Verband melden wenn der Spieler für den Verein: \"$club_name\" freigeschaltet werden soll.");
  }
  else {
    $int_pl_id = DBM_Spieler::write_Spieler($Player);
    DBM_Spieler::set_Spieler_of_Verein($int_pl_id, $club_id, 1);
  }
  //DBMapper::write_Lizenz($int_pl_id, $int_t_id, 2);
  // load from db
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  if($Mannschaft) {
    $bool_show_data = 1;
    $str_t_name = $Mannschaft->get_str_name();
    $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
    $int_t_liga = $Mannschaft->get_int_id_liga();
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                              $club_id);
  }
}

if(isset($_POST['back'])) {
  $bool_show_data = 1;
  $nav = "";
  $int_t_id = $_SESSION['temp']['t_id'];
  $user=DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
  $club_id = $user->get_int_id_verein();;
  $club_name = $user->get_str_verein_name();

  // load from db
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  if($Mannschaft) {
    $bool_show_data = 1;
    $str_t_name = $Mannschaft->get_str_name();
    $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
    $int_t_liga = $Mannschaft->get_int_id_liga();
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                              $club_id);
  }
}

?>