<?php
$x1 = 19;
$x2 = 27;
$x3 = 38;
$v_off = 0;
if(!isset($bool_show_delete)) $bool_show_delete = 0;

$my_message->add_warning("Die Option Spieler zu bearbeiten sollte nur in Sonderf&auml;llen von der SBK genutzt werden, da die Spieler von den Vereinen angelegt und verwaltet werden sollten.");
$my_message->add_warning("Spieler Transfers k&ouml;nnen nur zu Vereinen durchgef&uuml;hrt werden, wenn der Spieler nicht f&uuml;r den fraglichen Verein freigegeben ist!");
$arr_player[0][0] = 0;
$arr_player[0][1] = 0;
$arr_player[1][0] = "Neuen Spieler anlegen";
$arr_player[1][1] = "";
$arr_tmp = DBM_Spieler::get_list_Spieler_of_Verein($int_id);
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_player[0][$i+2] = $arr_tmp[0][$i];
    $arr_player[1][$i+2] = $arr_tmp[1][$i];
}
$arr_man[0][0] = -1;
$arr_man[0][1] = 1;
$arr_man[0][2] = 0;
$arr_man[1][0] = "none";
$arr_man[1][1] = "m&auml;nnlich";
$arr_man[1][2] = "weiblich";
$arr_nation = DBM_Spieler::get_list_nation();


$form_id = $my_form->create_form("teams", "Vereine", -1, -1, 60, 37);
$my_form->print_text($form_id, "Spieler des Vereins: ".$_POST['k_name']." (".$int_id.")", 3, 1);
$my_form->input_list($form_id, "list1", $arr_player[1], $arr_player[0], 2, 4, 14, 20, 13, 2);
$my_form->input_hidden($form_id, "nav", "player");
$my_form->input_hidden($form_id, "id", $int_id);
if(isset($old_nav))
  $my_form->input_hidden($form_id, "old_nav", $old_nav);
$my_form->input_hidden($form_id, "name", $str_name);
$my_form->input_hidden($form_id, "k_name", $str_k_name);
$my_form->input_hidden($form_id, "street", $str_street);
$my_form->input_hidden($form_id, "s_nbr", $str_nbr);
$my_form->input_hidden($form_id, "plz", $str_plz);
$my_form->input_hidden($form_id, "place", $str_place);
$my_form->input_hidden($form_id, "hp_club", $str_hp_club);
$my_form->input_hidden($form_id, "hp_part", $str_hp_part);
$my_form->input_hidden($form_id, "id_leiter", $int_id_leiter);
if($bool_show_data) {
  $my_form->input_hidden($form_id, "pl_id", $int_pl_id);
  if(isset($write_doppel)) 
  $my_form->input_hidden($form_id, "bestaetigt", 1);
  if ( $int_pl_id > -1 )
  {
  	$my_form->print_text($form_id, "Spieler-ID:", $x1, 2);
  	$my_form->print_text($form_id, $int_pl_id, $x2, 2);
  }
  $my_form->print_text($form_id, "Vorname:", $x1, 4);
  $my_form->input_text($form_id, "pl_name", $str_pl_name, 25, 45, $x2, 4);
  $my_form->print_text($form_id, "Nachname:", $x1, 6);
  $my_form->input_text($form_id, "pl_name2", $str_pl_name2, 25, 45, $x2, 6);
  $my_form->print_text($form_id, "Geburtsdatum:", $x1, 8);
  $my_form->input_text($form_id, "pl_date", $str_date, 25, 10, $x2, 8);
  $my_form->print_text($form_id, "Geschlecht:", $x1, 10);
  $my_form->input_select($form_id, "pl_man", $arr_man[1], $arr_man[0],
                         $bool_man, 1, 0, $x2, 10, 12);
  $my_form->print_text($form_id, "Sta&szlig;e:", $x1, 12);
  $my_form->input_text($form_id, "pl_street", $str_pl_street, 25, 100, $x2, 12);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 14);
  $my_form->input_text($form_id, "pl_nbr", $str_pl_nbr, 25, 10, $x2, 14);
  $my_form->print_text($form_id, "PLZ:", $x1, 16);
  $my_form->input_text($form_id, "pl_plz", $str_pl_plz, 25, 16, $x2, 16);
  $my_form->print_text($form_id, "Ort:", $x1, 18);
  $my_form->input_text($form_id, "pl_place", $str_pl_place, 25, 100, $x2, 18);
  $my_form->print_text($form_id, "Nationalit&auml;t:", $x1, 20);
  $my_form->input_select($form_id, "pl_nation", $arr_nation[1],
                         $arr_nation[0], $int_pl_nation, 1, 0, $x2, 20, 12);
  // baustelle
  if($int_pl_id > 0) {
    // checken: lizenzen, mitspieler
    $int_count_lizenz = DBMapper::get_count_Lizenz_of_Spieler($int_pl_id);
    // löschen: doppellizenzen, verein_spieler, spieler
    $my_form->input_button($form_id, "save", "Spieler speichern",
                           $x1+1, 24, 13, 2);
    if($int_count_lizenz == 0) {
      $my_form->input_button($form_id, "delete", "Spieler löschen",
                             $x1+15, 24, 13, 2);
    }
    $my_form->print_text($form_id,
                         "Anzahl der Lizenzen des Spielers: ".$int_count_lizenz,
                         $x1, 25);
    $ist_erstverein = false;
    $arr_verein = DBM_Spieler::get_Verein_by_Spieler($int_pl_id);
    if(!$arr_verein) {
      $my_message->add_error("Datenbankfehler");
    }
    else {
      for($i=0; $i<count($arr_verein[0]); $i++) {
        // eintrag [1] ist "erstverein"
        if($arr_verein[1][$i] == 0) {
          $verein = DBMapper::read_Verein($arr_verein[0][$i]);
          $my_form->print_text($form_id, $verein->get_str_name(), $x1,
                               28+$v_off);
          $my_form->input_button($form_id, "rem_".$arr_verein[0][$i],
                                 "Spielerfreigabe zur&uuml;ckziehen", $x2+14,
                                 29.9+$v_off*1.08, 16, 2);
          $v_off += 2;
        }
        else 
        {
        	// Erstverein gefunden
        	if ($arr_verein[0][$i] == $int_id ) 
        	{
        		$ist_erstverein = true; 
        	}
        	
        }
      }
    }
    $arr_verein_all = DBMapper::get_List_Verein_all();
    $arr_link_verein[0] = array();
    $arr_link_verein[1] = array();
    $arr_link_verein[2] = array();
    for($i=0; $i<count($arr_verein_all[0]); $i++) {
      $found = 0;
      for($k=0; $k<count($arr_verein[0]); $k++) {
        if($arr_verein[0][$k] == $arr_verein_all[0][$i]) {
          $found = 1;
          break;
        }
      }
      if(!$found) {
        $arr_link_verein[0][] = $arr_verein_all[0][$i];
        $arr_link_verein[1][] = $arr_verein_all[1][$i];
        $arr_link_verein[2][] = $arr_verein_all[2][$i];
      }
    }
    $my_form->input_select($form_id, "pl_link_verein", $arr_link_verein[1],
                           $arr_link_verein[0], 0, 1, 0, $x1, 29+$v_off, 18);
      
    $my_form->input_button($form_id, "add_link_verein",
                           "f&uuml;r Verein freigeben", $x2+14,
                           30.9+$v_off*1.08, 15, 2);

    if ( $ist_erstverein )
    {
    	$my_form->input_select($form_id, "pl_transfer_verein", $arr_link_verein[1],
                           $arr_link_verein[0], 0, 1, 0, $x1, 31+$v_off, 18);
      
    	$my_form->input_button($form_id, "transfer_verein",
                           "zu Verein transferieren", $x2+14,
                           32.9+$v_off*1.08, 15, 2);
    }                           
    $my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein",
                           $x1+1, 37+$v_off, 13, 2);
  }
  else {
    $my_form->input_button($form_id, "save", "Spieler anlegen",
                           $x1+1, 26+$v_off, 13, 2);
    $my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein",
                           $x1+1, 29+$v_off, 13, 2);    
  }
}
else if($bool_show_delete) {
  $my_form->input_hidden($form_id, "pl_id", $int_pl_id);

  $my_form->input_hidden($form_id, "pl_name", $str_pl_name);
  $my_form->input_hidden($form_id, "pl_name2", $str_pl_name2);
  $my_form->input_hidden($form_id, "pl_man", $bool_man);
  $my_form->input_hidden($form_id, "pl_date", $str_date);
  $my_form->input_hidden($form_id, "pl_street", $str_pl_street);
  $my_form->input_hidden($form_id, "pl_nbr", $str_pl_nbr);
  $my_form->input_hidden($form_id, "pl_plz", $str_pl_plz);
  $my_form->input_hidden($form_id, "pl_place", $str_pl_place);
  $my_form->input_hidden($form_id, "pl_nation", $int_pl_nation);

  $my_form->print_text($form_id, "Vorname:", $x1, 4);
  $my_form->print_text($form_id, $str_pl_name, $x2, 4);
  $my_form->print_text($form_id, "Nachname:", $x1, 6);
  $my_form->print_text($form_id, $str_pl_name2, $x2, 6);
  $my_form->print_text($form_id, "Geburtsdatum:", $x1, 8);
  $my_form->print_text($form_id, $str_date, $x2, 8);
  $my_form->print_text($form_id, "Geschlecht:", $x1, 10);
  if($bool_man) $str_man = "m&auml;nnlich";
  else $str_man = "weiblich";
  $my_form->print_text($form_id, $str_man, $x2, 10);
  $my_form->print_text($form_id, "Sta&szlig;e:", $x1, 12);
  $my_form->print_text($form_id, $str_pl_street, $x2, 12);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 14);
  $my_form->print_text($form_id, $str_pl_nbr, $x2, 14);
  $my_form->print_text($form_id, "PLZ:", $x1, 16);
  $my_form->print_text($form_id, $str_pl_plz, $x2, 16);
  $my_form->print_text($form_id, "Ort:", $x1, 18);
  $my_form->print_text($form_id, $str_pl_place, $x2, 18);
  $my_form->print_text($form_id, "Nationalit&auml;t:", $x1, 20);
  $str_nation = "";
  for($i=0; $i<count($arr_nation[0]); $i++)
    if($arr_nation[0][$i] == $int_pl_nation) {
      $str_nation = $arr_nation[1][$i];
      break;
    }
  $my_form->print_text($form_id, $str_nation, $x2, 20);
  // baustelle
  if($int_pl_id > 0) {
    // checken: lizenzen, mitspieler
    $int_count_lizenz = DBMapper::get_count_Lizenz_of_Spieler($int_pl_id);
    // löschen: doppellizenzen, verein_spieler, spieler
    $my_form->input_button($form_id, "list1_".$int_pl_id, "abbrechen",
                           $x1+1, 24, 13, 2);
    if($int_count_lizenz == 0) {
      $my_form->input_button($form_id, "delete_ok", "Spieler l&ouml;schen",
                             $x1+15, 24, 13, 2);
    }
    $my_form->print_text($form_id,
                         "Anzahl der Lizenzen des Spielers: ".$int_count_lizenz,
                         $x1, 25);

    $my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein",
                           $x1+1, 35+$v_off, 13, 2);
  }
}
else {
  $my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein",
                         $x1+1, 29+$v_off, 13, 2);
}
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
