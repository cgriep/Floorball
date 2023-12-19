<?php

  // der lizenzstatus is ein hack

$bool_show_data = 0;
$user = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$club_id = $user->get_int_id_verein();;
$club_name = $user->get_str_verein_name();
$load = 0;
$int_t_id = 0;

if(isset($_POST['show'])) {
  // load from db
  $int_t_id = $_POST['t_id_team'];
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

if(isset($_POST['offer'])) {
  $int_t_id = $_POST['t_id'];
  $int_tmp_id = $_POST['t_id_team'];
  $bool_show_data = 1;
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  $str_t_name = $Mannschaft->get_str_name();
  $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
  $int_t_liga = $Mannschaft->get_int_id_liga();
  $Liga = DBM_Ligen::read_Liga($int_t_liga);
  $id_klasse = $Liga->get_int_id_klasse();
  //echo "id klasse: ".$id_klasse;
  $id_kategorie = $Liga->get_int_id_kategorie();
  $weiblich = $Liga->get_bool_weiblich();
  $int_id_player = $_POST['t_id_player'];
  $spieler = DBM_Spieler::read_Spieler($int_id_player);
  $str_player_name = $spieler->get_str_vorname()." ".$spieler->get_str_name();
  $write_lizenz = TRUE;
  $write_doppel = FALSE;
  $wird_doppel_b = FALSE;
  if($int_tmp_id != $int_t_id) {
    $my_message->add_warning("Bitte erst \"anzeigen\" drücken um Lizenzen für ein anderes Team zu beantragen. Es wurde keine Lizenz beantragt.");
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                              $club_id);
  }
  else {
    // alter checken ###
    // als erstes checken ob kein mann in einer damen liga spielen soll ###
    if($weiblich && $spieler->get_bool_geschlecht()) {
      $my_message->add_error("Die Liga ist als Damenliga angelegt, der Lizenzantrag bezieht sich aber auf einen männlichen Spieler.");
      $write_lizenz = FALSE;
    }
    else {
      // erstmal nach schon bestehenden Lizenzen in der gleichen Klasse suchen
      $count_lizenz = DBMapper::get_count_Lizenz_by_Klasse($int_id_player,
                                                           $id_klasse,
                                                           $id_kategorie,
                                                           $weiblich);
      if($count_lizenz > 0) {
        $my_message->add_error("Der Spieler hat bereits eine Lizenz in der gleichen Spielklasse. Bitte beim Verband melden wenn ein Fehler vorliegt.");
        $write_lizenz = FALSE;
      }
      else if($count_lizenz < 0) {
        // fehler
        $my_message->add_error("Datenbankfehler");
        $wriet_lizenz = FALSE;
      }
      else if(!$weiblich) {
        // dann auf eine zweite GroÃŸfeldlizenz prüfen
        // @todo hack
        $geb_datum = $spieler->get_date_geb_datum();
        $arr_datum = explode(".", $geb_datum);
        $saison = substr($_SESSION['str_saison'],0,4); // erstes Jahr aus Saison
        // hack kategorie GF wird nicht aus db gelesen
        if($saison - $arr_datum[2] > 18 && $id_kategorie == 1) {
          $count_double = DBMapper::get_count_Doppellizenz($int_id_player);
          if($count_double > 0) {
            $my_message->add_error("Der Spieler besitzt bereits eine Doppellizenz.");
            $write_lizenz = FALSE;
          }
          else if($count_double < 0) {
            // fehler
            $my_message->add_error("Datenbankfehler");
          }
          else {
            // nun muss noch getestet werden ob eine Doppellizenz entstehen würde
            $arr_lizenzen = DBMapper::get_list_Lizenz_of_Spieler($int_id_player);
            $found = 0;
            for($i=0; $i<count($arr_lizenzen[0]); $i++) {
              if($arr_lizenzen[3][$i] == 1 && !$arr_lizenzen[4][$i]) {
                $found += 1;
                $arr_doppel[0][] = $arr_lizenzen[5][$i]; // Verband
                $arr_doppel[1][] = $arr_lizenzen[0][$i]; // Lizenz
                $arr_doppel[2][] = $arr_lizenzen[2][$i]; // Klasse
                $arr_doppel[3][] = $arr_lizenzen[1][$i]; // Mannschaft
                if($arr_lizenzen[2][$i] <= 20) {
                  $wird_doppel_b = TRUE;
                }
              }
            }
            if($found) {
              $write_doppel = TRUE;
              if($found > 1) {
                // ist nicht plausible
              }
              // teset ob die Mannschaft eine weitere Doppellizenz haben darf
              $count_doppel = DBMapper::get_count_Doppel($int_t_id,
                                                         DBMapper::get_id_Verband());
              // auch den anderen mannschaften prüfen ###
              $count_doppel_other = 0;
              for($nm=0; $nm<count($arr_doppel[0]); $nm++) {
                $tmp_count = DBMapper::get_count_Doppel($arr_doppel[3][$nm],
                                                        $arr_doppel[0][$nm]);
                if($tmp_count > 5) {
                  $count_doppel_other = 1;
                  break;
                }
              }
              if($count_doppel >= 6) {
                $my_message->add_error("Die Mannschaft hat bereits mindestens sechs Spieler mit Doppellizenzen im Kader und darf keine weitere Doppellizenz beantragen. Bei Sonderfällen bitte den Verband kontaktieren.");
                $write_lizenz = FALSE;
              }
              else if($count_doppel_other) {
                $my_message->add_error("Eine andere Mannschaft des Spielers hat bereits mindestens sechs Spieler mit Doppellizenzen im Kader und darf keine weitere Doppellizenz erhalten die bei einer Lizenzsierung des Spielers in dieser Mannschaft entstehen würde. Bei Sonderfällen bitte den Verband kontaktieren.");
                $write_lizenz = FALSE;
              }
              else {
                if($wird_doppel_b && ($id_klasse <= 20)) {
                  $count_doppel_b = DBMapper::get_count_Doppel_B($int_t_id,
                                                                 DBMapper::get_id_Verband());
                  // auch hier den anderen verein prüfen ###
                  $count_doppel_b_other = 0;
                  for($nm=0; $nm<count($arr_doppel[0]); $nm++) {
                    $tmp_count = DBMapper::get_count_Doppel_B($arr_doppel[3][$nm],
                                                              $arr_doppel[0][$nm]);
                    if($tmp_count > 1) {
                        //print 'Pr&uml;fung: '.$int_t_id.'/'.DBMapper::get_id_Verband().'/'.$arr_doppel[3][$nm].'/'.$arr_doppel[0][$nm].'/'.$tmp_count;
                    	$count_doppel_b_other = 1;
                      break;
                    }
                  }
                  if($count_doppel_b >= 2) {
                    $my_message->add_error("Die Mannschaft hat bereits mindestens zwei Spieler mit Doppellizenzen in den Bundesligen im Kader und darf keine weitere Bundes-Doppellizenz beantragen. Bei Sonderf&auml;llen bitte den Verband kontaktieren.");
                    $write_lizenz = FALSE;
                  }
                  else if($count_doppel_b_other) {
                    $my_message->add_error("Eine andere Mannschaft des Spielers hat bereits mindestens zwei Spieler mit Doppellizenzen in den Bundesligen im Kader und darf keine weitere Doppellizenz erhalten, die bei einer Lizenzsierung des Spielers in dieser Mannschaft entstehen w&uuml;rde. Bei Sonderf&auml;llen bitte den Verband kontaktieren.");
                    $write_lizenz = FALSE;
                  }
                }
              }
            }
          }
        }
      }
    }
    if($write_lizenz) {
      if($write_doppel) {
        // der Spieler muss in die Doppellizentabelle eingetragen werden  
        $ord = 1;
        for($i=0; $i<count($arr_doppel[0]); $i++) {
          if(!DBMapper::write_Doppel($int_id_player, $arr_doppel[0][$i],
                                     $arr_doppel[1][$i], $arr_doppel[2][$i],
                                     $arr_doppel[3][$i], $ord))
            $my_message->add_error("Datenbankfehler");
          $ord += 1;
        }
      }
      if($id_lizenz = DBMapper::write_Lizenz($int_id_player,
                                             $int_t_id, $id_klasse,
                                             $id_kategorie,
                                             $weiblich, 2)) {
        $my_message->add_message("Spielerlizenz für \"".$str_player_name."\" beantragt");
        // diese Lizenz auch noch eintragen
        if($write_doppel) {
          if(!DBMapper::write_Doppel($int_id_player,
                                     DBMapper::get_id_Verband(),
                                     $id_lizenz, $id_klasse, $int_t_id,
                                     $ord))
            $my_message->add_error("Datenbankfehler");
        }
      } else {
        $my_message->add_error("Datenbankfehler");    
      }
    }
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                              $club_id);
    $str_player_name = "";
    for($i=0; $i<count($arr_player[0]); $i++) {
      if($arr_player[0][$i] == $_POST['t_id_player']) {
        $str_player_name = " für ".$arr_player[1][$i];
        break;
      }
    }
  }
}

if(isset($_POST['remove'])) {
  $int_t_id = $_POST['t_id'];
  $int_tmp_id = $_POST['t_id_team'];
  
  if($int_tmp_id != $int_t_id) {
    $my_message->add_warning("Bitte erst \"anzeigen\" drücken um Lizenzen für ein anderes Team zu beantragen. Es wurde keine Lizenz beantragt.");
  }
  else {
    if ( ! isset($_POST['t_id_rplayer'])) $_POST['t_id_rplayer'] = "";
    $arr_tmp = explode(";", $_POST['t_id_rplayer']);
    // check if we are allowed to delete the licence
    $status_of_lizenz = DBMapper::get_status_of_lizenz($arr_tmp[0]);  
    if($status_of_lizenz == 2) {
      if(DBMapper::del_Lizenz($arr_tmp[0], $arr_tmp[1])) {
        $spieler = DBM_Spieler::read_Spieler($arr_tmp[1]);
        $my_message->add_message("Die Lizenz für \"".$spieler->get_str_vorname()." ".$spieler->get_str_name()."\" wurde erfolgreich zurückgezogen.");
      }
      else $my_message->add_error("Datenbankfehler");
    }
  }
  $bool_show_data = 1;
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  $str_t_name = $Mannschaft->get_str_name();
  $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
  $int_t_liga = $Mannschaft->get_int_id_liga();
  $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                            $club_id);
}


if(isset($_POST['saveB'])) {
  $int_t_id = $_POST['t_id'];
  $int_t_id_leiter = $_POST['t_id_leiter'];
  $bool_show_data = 1;
  $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
  $Mannschaft->set_int_id_betreuer($int_t_id_leiter);
  DBMapper::update_Mannschaft($Mannschaft);
  $str_t_name = $Mannschaft->get_str_name();
  $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
  $int_t_liga = $Mannschaft->get_int_id_liga();
  $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id,
                                                            $club_id);  
}

if(isset($_POST['next'])) {
  // save all form data in the session
  $_SESSION['temp']['t_id'] = $_POST['t_id_team'];
  $_SESSION['temp']['t_id_club'] = $club_id;
  $_SESSION['temp']['t_str_club'] = $club_name;

  switch($_POST['next']) {
    case 'Neue Person anlegen':
      $nav = "persons";
    break;
    case 'Neuen Spieler anlegen':
      $nav = "player";
      $int_pl_id = -1;
      $str_pl_name = "";
      $str_pl_name2 = "";
      $bool_man = -1;
      $str_date = "";
      $str_pl_street = "";
      $str_pl_nbr = "";
      $str_pl_place = "";
      $str_pl_plz = "";
      $int_pl_nation = 4;
    break;
  }
}

?>
