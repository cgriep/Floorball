<?php
/**
* Logik für die Accounts
*
* @package    VM
* @subpackage Handles
*/
$bool_show_data = 0;
$a_error = 0;
$user = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$int_id_club = $user->get_int_id_verein();
$int_id = 0;
$int_type =  5;
$arr_teams[0] = array();
$arr_teams[1] = array();
$arr_letters = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                     "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
                     "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
                     "u", "v", "w", "x", "y", "z", "A", "B", "C", "D",
                     "E", "F", "G", "H", "I", "J", "K", "L", "M", "N",
                     "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
                     "Y", "Z");

if(isset($_POST['save'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $bool_show_data = 0;
  $int_id = $_POST['id'];
  $str_a_nick =  $_POST['a_nick'];
  $str_a_email =  $_POST['a_email'];
  $str_a_text =  $_POST['a_text'];
  $str_a_surname = $_POST['a_surname'];
  $str_a_lastname = $_POST['a_lastname'];
  $str_a_date = $_POST['a_date'];
  $str_a_street = $_POST['a_street'];
  $str_a_nbr = $_POST['a_nbr'];
  $str_a_plz = $_POST['a_plz'];
  $str_a_place = $_POST['a_place'];

  $arr_type = array($int_type);
  $Benutzer = new Benutzer();

  $Benutzer->set_int_id_verein($int_id_club);

  $arr_teams = DBMapper::get_list_Mannschaft($int_id_club);
  $arr_t_ids = array();
  foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 5) == "check") {
      if($value == 0) {
        $arr_t_ids[] = $arr_teams[0][substr($key, 5, strlen($key))];
      }
    }
  }
  $Benutzer->set_arr_teams($arr_t_ids);

  $Benutzer->set_arr_gruppe($arr_type);
  $Benutzer->set_str_user_name($str_a_nick);
  $Benutzer->set_str_vorname($str_a_surname);
  $Benutzer->set_str_nachname($str_a_lastname);
  $Benutzer->set_date_geb_datum($str_a_date);
  $Benutzer->set_str_beschreibung($str_a_text);
  $Benutzer->set_str_email($str_a_email);
  $Benutzer->set_str_strasse($str_a_street);
  $Benutzer->set_str_hausnummer($str_a_nbr);
  $Benutzer->set_str_plz($str_a_plz);
  $Benutzer->set_str_ort($str_a_place);
  $Benutzer->set_bool_aktiv(true);

  if($int_id == -1 || $int_id == 0 ) {
  	
  	// Dummy-Passwort generieren
  	$str_a_pass1 = $arr_letters[rand(0,61)];
  	for($i=0; $i<7; $i++) {
	    $str_a_pass1 .= $arr_letters[rand(0,61)];
  	}

  	// Passwort generieren -> Dieses PW wird zugeschickt
  	$str_a_pass2 = $arr_letters[rand(0,61)];
  	for($i=0; $i<7; $i++) {
    	$str_a_pass2 .= $arr_letters[rand(0,61)];
  	}

  	$Benutzer->set_str_passwort($str_a_pass1);
  	$Benutzer->set_str_passwort2($str_a_pass2);
  	$Benutzer->set_time_passwort2("20200101080000");

  	$my_message->add_message("Für den Benutzer \"$str_a_nick\" wurde ein neues Passwort generiert und per EMail versendet.");
  
    $int_id = DBM_Benutzer::write_Benutzer($Benutzer);
    Email::send_willkommen($int_id, $str_a_pass2, $Benutzer->get_str_email());
    $my_message->add_message("Der Benutzer \"$str_a_nick\" wurde erfolgreich angelegt.");
  }
  else
  {
  	$Benutzer->set_int_id($int_id);
    DBM_Benutzer::update_Benutzer($Benutzer);
    $my_message->add_message("Der Benutzer \"$str_a_nick\" wurde erfolgreich bearbeitet.");
  }
} // POST

$arr_accounts[0][0] = 0;
$arr_accounts[1][0] = "Neuen Account anlegen";
$arr_accounts[0][1] = 0;
$arr_accounts[1][1] = "";
$k = 2;
/*
$arr_tmp = DBM_Benutzer::get_list_Benutzer("VM", $int_id_club);
if($arr_tmp) {
  for($i=0; $i<count($arr_tmp); $i++) {
    $arr_accounts[0][$k] = $arr_tmp[$i]->get_int_id();
    $arr_accounts[1][$k] = $arr_tmp[$i]->get_str_user_name();
    $k++;
  }
}
*/
$arr_tmp = DBM_Benutzer::get_list_Benutzer("TM", $int_id_club);
if($arr_tmp) {
  for($i=0; $i<count($arr_tmp); $i++) {
    $arr_accounts[0][$k] = $arr_tmp[$i]->get_int_id();
    $arr_accounts[1][$k] = $arr_tmp[$i]->get_str_user_name();
    $k++;
  }
}

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neuen Account anlegen") {
      $bool_show_data = 1;
      $str_a_nick = "";
      $str_a_email = "";
      $str_a_text = "";
      $str_a_surname = "";
      $str_a_lastname = "";
      $str_a_date = "";
      $str_a_street = "";
      $str_a_nbr = "";
      $str_a_plz = "";
      $str_a_place = "";
    }
    else {
      // load from db
      $int_id = trim(substr($key,6));
      $Benutzer = DBM_Benutzer::read_Benutzer($int_id);
      if($Benutzer) {
        $bool_show_data = 1;
        $int_id_club = $Benutzer->get_int_id_verein();
        $str_a_nick = $Benutzer->get_str_user_name();
        $str_a_email = $Benutzer->get_str_email();
        $str_a_text = $Benutzer->get_str_beschreibung();
        $str_a_surname = $Benutzer->get_str_vorname();
        $str_a_lastname = $Benutzer->get_str_nachname();
        $str_a_date = $Benutzer->get_date_geb_datum();
        $str_a_street = $Benutzer->get_str_strasse();
        $str_a_nbr = $Benutzer->get_str_hausnummer();
        $str_a_plz = $Benutzer->get_str_plz();
        $str_a_place = $Benutzer->get_str_ort();
      }
    }
  }
}

$arr_clubs = DBMapper::get_list_Verein();
if($int_id_club) {
  $arr_teams = DBMapper::get_list_Mannschaft($int_id_club);
  $arr_teams_user = DBM_Benutzer::get_mannschaften($int_id);
}

?>