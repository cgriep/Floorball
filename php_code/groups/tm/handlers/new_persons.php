<?php

$club_id = $_SESSION['temp']['t_id_club'];
if(isset($_POST['save'])) {
  $person = new Person(-1, $club_id, $_POST['p_name'],
		       $_POST['p_n_name'], $_POST['p_street'], $_POST['p_nbr'],
		       $_POST['p_plz'], $_POST['p_place'], $_POST['p_tel'],
		       $_POST['p_handy'], $_POST['p_mail'], $_POST['p_date']);
  $int_insert = DBMapper::write_Person($person);
  // Objekt erstellen und an db übergeben
  $nav = "";
  $int_t_id_leiter = $int_insert;
  $int_t_id = $_SESSION['temp']['t_id'];
  $user=DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
  $club_id = $user->get_int_id_verein();;
  $club_name = $user->get_str_verein_name();

  // load from db
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  if($Mannschaft) {
    $bool_show_data = 1;
    $str_t_name = $Mannschaft->get_str_name();
    $int_t_liga = $Mannschaft->get_int_id_liga();
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id);
  }
}

if(isset($_POST['back'])) {
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
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id);
  }
}

$bool_show_data = 1;
?>