<?php
$bool_show_data = 0;

$int_id = $_POST['id'];
$str_name = $_POST['name'];
$str_k_name = $_POST['k_name'];
$str_street = $_POST['street'];
$str_nbr = $_POST['s_nbr'];
$str_plz = $_POST['plz'];
$str_place = $_POST['place'];
$str_hp_club = $_POST['hp_club'];
$str_hp_part = $_POST['hp_part'];
$int_id_leiter = $_POST['id_leiter'];
if(isset($_POST['old_nav']))
  $old_nav = $_POST['old_nav'];

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
      $int_pl_nation = "4"; // Deutschalnd vorausgewÃ¤hlt
    }
    else {
      // load from db
      $int_pl_id = trim(substr($key,6));
      $Player = DBM_Spieler::read_Spieler($int_pl_id);
      if($Player) {
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
  else if(substr($key, 0, 3) == "rem") {
    $int_pl_id = $_POST['pl_id'];
    $bool_show_data = 1;
    $str_pl_name = $_POST['pl_name'];
    $str_pl_name2 = $_POST['pl_name2'];
    $bool_man = $_POST['pl_man'];
    $str_date = $_POST['pl_date'];
    $str_pl_street = $_POST['pl_street'];
    $str_pl_nbr = $_POST['pl_nbr'];
    $str_pl_plz = $_POST['pl_plz'];
    $str_pl_place = $_POST['pl_place'];
    $int_pl_nation = $_POST['pl_nation'];
    
    $int_club_id = trim(substr($key,4));
    // nur lÃ¶schen wenn keine Lizenz bei anderem Verein vorhanden ist
    if(DBMapper::get_count_Lizenz_of_Spieler2($int_pl_id, $int_club_id) == 0) {
      if(DBM_Spieler::delete_Spieler_Verein($int_pl_id, $int_club_id))
        $my_message->add_message("Die Spielerfreigabe wurde f&uuml;r den Verein zur&uuml;ckgezogen.");
      else $my_message->add_error("Datenbankfehler");
    }
    else {
      $my_message->add_error("Die Spielerfreigabe kann nicht zur&uuml;ckgezogen werden da noch eine Lizenz des Spielers bei dem Verein besteht.");
    }
  }
}

if(isset($_POST['save'])) {
  $bool_show_data = 0;
  $int_pl_id = $_POST['pl_id'];
  $bool_show_data = 1;
  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];
  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
  $Player = new Spieler($int_pl_id, $str_pl_name, $str_pl_name2,
                        $bool_man, $str_date, $int_pl_nation, $str_pl_street,
                        $str_pl_nbr, $str_pl_plz, $str_pl_place);
  if($int_id != -1) {
    if($int_pl_id == -1) {
      $write_spieler = 1;
      if(!isset($_POST['bestaetigt'])) {
        if(DBM_Spieler::check_Spieler($Player)) {
          $my_message->add_error("Der Spieler existiert bereits in der Datenbank. Um ihn dennoch anzulegen muss noch einmal auf \"Spieler anlegen\" geklickt werden.");
          $write_spieler = 0;
          $bool_show_data = 1;
          $write_doppel = 1;
        }
      }
      if($write_spieler) {
        $int_pl_id = DBM_Spieler::write_Spieler($Player);
        DBM_Spieler::set_Spieler_of_Verein($int_pl_id, $int_id, 1);
        $my_message->add_message("Der Spieler \"$str_pl_name $str_pl_name2\" wurde erfolgreich angelegt.");
      }
    }
    else {
      DBM_Spieler::update_Spieler($Player);
    }
  }
  else echo "Zuerst muss ein Verein gespeichert sein, ehe Spieler editiert werden k$ouml;nnen.";
}
else if(isset($_POST['add_link_verein'])) {
  $bool_show_data = 1;
  $int_pl_id = $_POST['pl_id'];
  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];
  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
  $int_link_club = $_POST['pl_link_verein'];
  if(DBM_Spieler::set_Spieler_of_Verein($int_pl_id, $int_link_club, 0)) {
    $my_message->add_message("Der Spieler wurde erfolgreich dem Verein freigegeben.");
  }
  else {
    $my_message->add_error("Datenbankfehler");
  }
}
else if(isset($_POST['transfer_verein'])) {
  $bool_show_data = 1;
  $int_pl_id = $_POST['pl_id'];
  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];
  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
  $int_transfer_club = $_POST['pl_transfer_verein'];
  // Hinzufügen als neuer Spieler
  //$my_message->add_message("Transfer von $int_pl_id aus Verein $int_id nach $int_transfer_club angefordert");
  // Entfernen aus dem alten Verein
  if(DBM_Spieler::delete_Spieler_Verein($int_pl_id, $int_id))
  {
    	// Protokolliere den Transfer
    	// schreibe: User, Spieler, alter Verein, neuer Verein, Datum+Zeit, Verband, Saison
    	if ( DBMapper::write_TransferProtokoll($_SESSION['arr_login']['id_benutzer'], $_SESSION['id_saison'], $int_pl_id, $int_id, 
    	$int_transfer_club, array_search(VERBAND, $arr_ids_verband)) )
    	{
    		
    	}
    	else
    	  $my_message->add_error("Datenbankfehler beim Protokollieren des Transfers");    	
  	  	$my_message->add_message("Der Spieler wurde erfolgreich aus dem alten Verein $int_id entfernt.");
  	  	if(DBM_Spieler::set_Spieler_of_Verein($int_pl_id, $int_transfer_club, 1)) {
    		$my_message->add_message("Der Spieler wurde erfolgreich dem neuen Verein $int_transfer_club transferiert.");
  	  	}
  		else {
    		$my_message->add_error("Datenbankfehler beim Hinzufügen von Spieler $int_pl_id aus Club $int_id zum neuen Club $int_transfer_club - Bitte informieren Sie unbedingt die SBK unter Nennung dieser Meldung.");
  		}  
   }
   else {
      $my_message->add_error("Datenbankfehler beim Entfernen aus dem alten Club $int_id . Prüfen Sie dass keine alten Lizenzen vorhanden sind.");
   } 

}
else if(isset($_POST['delete'])) {
  $bool_show_delete = 1;
  $bool_show_data = 0;
  $int_pl_id = $_POST['pl_id'];
  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];
  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
}
else if(isset($_POST['delete_ok'])) {
  $bool_show_delete = 0;
  $bool_show_data = 0;
  $int_pl_id = $_POST['pl_id'];
  $str_pl_name = $_POST['pl_name'];
  $str_pl_name2 = $_POST['pl_name2'];
  $bool_man = $_POST['pl_man'];
  $str_date = $_POST['pl_date'];
  $str_pl_street = $_POST['pl_street'];
  $str_pl_nbr = $_POST['pl_nbr'];
  $str_pl_plz = $_POST['pl_plz'];
  $str_pl_place = $_POST['pl_place'];
  $int_pl_nation = $_POST['pl_nation'];
  
  if(DBM_Spieler::delete_Spieler($int_pl_id)) {
    $my_message->add_message("Der Spieler \"$str_pl_name $str_pl_name2\" wurde erfolgreich gelÃ¶scht.");
  }
  else {
    $my_message->add_error("Der Spieler \"$str_pl_name $str_pl_name2\" konnte nicht gelÃ¶scht werden. Wahrscheinlich existiert eine Verlinkung zu einem anderen Verband.");
    $bool_show_data = 1;
  }
}

if(isset($_POST['back'])) {
  $bool_show_data = 1;
  $bool_show_delete = 0;
  if(isset($old_nav)) $nav = $old_nav;
  else $nav = "clubs";
}

?>
