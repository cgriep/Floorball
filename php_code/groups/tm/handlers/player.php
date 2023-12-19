<?php
$bool_show_data = 0;
$user = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$club_id = $user->get_int_id_verein();
$club_str = $user->get_str_verein_name();
$load = 0;
$int_pl_id = -2;
if ( ! isset($club_name)) $club_name = "-unbekannt-";

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neuen Spieler anlegen") {
      $bool_show_data = 1;
      $int_pl_id = -1;
      $str_pl_name = "";
      $str_pl_name2 = "";
      $bool_man = -1;
      $str_date = "";
      $str_pl_street = "";
      $str_pl_nbr = "";
      $str_pl_place = "";
      $str_pl_plz = "";
      $int_pl_nation = "4";
    }
    else {
      // load from db
      $int_pl_id = trim(substr($key,6));
      $Player = DBM_Spieler::read_Spieler($int_pl_id);
      if($Player) {
        $load = 1;
        $bool_show_data = 1;
        $str_pl_name = $Player->get_str_vorname();
        $str_pl_name2 = $Player->get_str_name();
        $bool_man = $Player->get_bool_geschlecht();
        $str_date = $Player->get_date_geb_datum();
        $str_pl_street = $Player->get_str_strasse();
        $str_pl_nbr = $Player->get_str_hausnummer();
        $str_pl_place = $Player->get_str_ort();
        $str_pl_plz = $Player->get_str_plz();
        $int_pl_nation = $Player->get_int_id_nation();
      }
    }
  }
}

if(isset($_POST['save'])) {
  $int_pl_id = $_POST['pl_id'];
  $bool_show_data = 0;
  $load = 1;
  if($int_pl_id == -1) {
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
    $Player = new Spieler($int_pl_id, $str_pl_name, $str_pl_name2,
			  $bool_man, $str_date, $int_pl_nation, $str_pl_street,
			  $str_pl_nbr, $str_pl_plz, $str_pl_place);
    if(DBM_Spieler::check_Spieler($Player)) {
    $my_message->add_error("Der Spieler wurde bereits angelegt. Bitte bei dem Verband melden wenn der Spieler f√ºr den Verein: \"$club_name\" freigeschaltet werden soll.");
    }
    else {
      $int_pl_id = DBM_Spieler::write_Spieler($Player);
      DBM_Spieler::set_Spieler_of_Verein($int_pl_id, $club_id, 1);
    }
  }
  else {
    $int_count_lizenz = DBMapper::get_count_Lizenz_of_Spieler($int_pl_id);
    if($int_count_lizenz == 0) {
      /*
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
      */
      $str_pl_street = $_POST['pl_street'];
      $str_pl_nbr = $_POST['pl_nbr'];
      $str_pl_plz = $_POST['pl_plz'];
      $str_pl_place = $_POST['pl_place'];
      $int_pl_nation = $_POST['pl_nation'];
      $Player_old = DBM_Spieler::read_Spieler($int_pl_id);
      $Player_old->set_str_hausnummer($str_pl_nbr);
      $Player_old->set_str_strasse($str_pl_street);
      $Player_old->set_str_ort($str_pl_place);
      $Player_old->set_str_plz($str_pl_plz);
      $Player_old->set_int_id_nation($int_pl_nation);
      $Player_old->set_bool_geschlecht($bool_man);
      $update_spieler = 1;
      
      /*
       Nicht mehr benˆtigt. Die VM/TM d¸rfen keine ƒnderungen mehr am Namen vornehmen, daher m¸ssen nur noch die sonstigen Daten aktualisiet werden.
      $Player = new Spieler($int_pl_id, $str_pl_name, $str_pl_name2,
                            $bool_man, $str_date, $int_pl_nation,
                            $str_pl_street, $str_pl_nbr,
                            $str_pl_plz, $str_pl_place);
      
      if($Player->get_str_name() != $Player_old->get_str_name() ||
         $Player->get_str_vorname() != $Player_old->get_str_vorname() ||
         $Player->get_date_geb_datum() != $Player_old->get_date_geb_datum()) {
        if(DBM_Spieler::check_Spieler($Player)) {
          $update_spieler = 0;
        } 
      }
      */
      if($update_spieler) {
        if(DBM_Spieler::update_Spieler($Player_old)) {
          $my_message->add_message("Der Spieler wurde erfolgreich gespeichert.");
        }
        else $my_message->add_error("Datenbankfehler");
      }
      else $my_message->add_error("Ein anderer Spieler mit gleichen Angaben in den Pflichtfeldern ist bereits angelegt. Der Spieler konnte nicht gespeichert werden.");

    }
    else {
      $Player = DBM_Spieler::read_Spieler($int_pl_id);
      $Player->set_str_strasse($_POST['pl_street']);
      $Player->set_str_hausnummer($_POST['pl_nbr']);
      $Player->set_str_plz($_POST['pl_plz']);
      $Player->set_str_ort($_POST['pl_place']);
      DBM_Spieler::update_Spieler($Player);
      $str_pl_name = $Player->get_str_vorname();
      $str_pl_name2 = $Player->get_str_name();
      $bool_man = $Player->get_bool_geschlecht();
      $str_date = $Player->get_date_geb_datum();
      $str_pl_street = $Player->get_str_strasse();
      $str_pl_nbr = $Player->get_str_hausnummer();
      $str_pl_place = $Player->get_str_ort();
      $str_pl_plz = $Player->get_str_plz();
      $int_pl_nation = $Player->get_int_id_nation();
    }
  }
}

?>
