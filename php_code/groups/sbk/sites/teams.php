<?php
$x1 = 19;
$x2 = 30;
$x3 = 38;
$player_offset = 0;

$arr_ligen = DBM_Ligen::get_list_Liga();

$arr_teams[0][0] = 0;
$arr_teams[0][1] = 0;
$arr_teams[1][0] = "Neue Mannschaft anlegen";
$arr_teams[1][1] = "";
$arr_tmp = DBMapper::get_list_Mannschaft($int_id);
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_teams[0][$i+2] = $arr_tmp[0][$i];
    $arr_teams[1][$i+2] = $arr_tmp[1][$i];
    for($k=0; $k<count($arr_ligen[0]); $k++) {
      if($arr_ligen[0][$k] == $arr_tmp[2][$i]) {
        $arr_teams[1][$i+2] .= " (".$arr_ligen[2][$k].")";
        break;
      }
    }
}

$arr_player2 = DBM_Spieler::get_list_Spieler_of_Verein($int_id);
$arr_t_id_leiter = DBMapper::get_list_Person($int_id);
$arr_t_id_leiter[0][] = 0;
$arr_t_id_leiter[1][] = "leer";
$form_id = $my_form->create_form("teams", "Vereine", -1, -1, 60, 37);
$my_form->print_text($form_id, "Mannschaften des Vereins: ".$str_k_name, 3, 1);
$my_form->input_list($form_id, "list1", $arr_teams[1], $arr_teams[0], 2, 4, 14, 20, 13, 2);
$my_form->input_hidden($form_id, "nav", "teams");
$my_form->input_hidden($form_id, "id", $int_id);
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
  $tmp_count_lizenz = DBMapper::get_count_Lizenz($int_t_id);
  if($tmp_count_lizenz > 0) $my_message->add_warning("Wenn zu einer Mannschaft bereits Lizenzen beantragt sind, wird das Ändern der Liga in diesem Formular nicht übernommen.");
  $my_form->input_hidden($form_id, "t_id", $int_t_id);
  foreach ($arr_player[0] as $key=>$value) {
        $my_form->input_hidden($form_id, "p_club_".$value, $arr_player[1][$key]);
    }
    $my_form->print_text($form_id, "Mannschaft-ID: ", $x1, 4);
    $my_form->print_text($form_id, $int_t_id, $x2, 4);
    $my_form->print_text($form_id, "Bezeichnung:", $x1, 6);
    $my_form->input_text($form_id, "t_name", $str_t_name, 38, 255, $x2+0.2, 6);
    $my_form->print_text($form_id, "Kurzbezeichnung:", $x1, 8);
    $my_form->input_text($form_id, "t_kurzname", $str_t_kurzname, 38, 19,
                         $x2+0.2, 8);

    $my_form->print_text($form_id, "Liga:", $x1, 10);
    $my_form->input_select($form_id, "id_liga", $arr_ligen[1], $arr_ligen[0],
			   $int_t_liga, 1, 0, $x2, 10, 25);
    $my_form->print_text($form_id, "Betreuer:", $x1, 12.3);
    $my_form->input_select($form_id, "t_id_leiter", $arr_t_id_leiter[1],
			   $arr_t_id_leiter[0],
			   $int_t_id_leiter, 1, 0, $x2, 12.3, 12);
    $my_form->input_button($form_id, "next", "Neue Person anlegen", $x2+17,
			   12.8, 14, 2);
    $my_form->print_text($form_id, "Spielgemeinschaft:", $x1, 14.5);
    $my_form->input_checkbox($form_id, "t_sg", "t_sg", "", $x2, 14.7, -1,
			     -1, $bool_t_sg);
    if($bool_show_player) {
      $my_form->print_text($form_id, "Spieler der Mannschaft:", $x1, 15);
      $my_form->input_list($form_id, "p_del", $arr_player[1], $arr_player[0],
                           $x1, 15, 14, 20, 15, 1.5);
      $my_form->print_text($form_id, "Spieler des Vereins:", $x3, 15);
      $my_form->input_list($form_id, "p_club", $arr_player2[1], $arr_player2[0],
                           $x3, 17, 14, 20, 13, 1.5);
      $player_offset = 20;
    }
    if($int_t_id == -1) {
      $my_form->input_button($form_id, "save", "Mannschaft anlegen", $x1+1,
                             18+$player_offset, 13, 2);
    }
    else {
      $my_form->input_button($form_id, "save", "Mannschaft speichern", $x1+1,
                             18+$player_offset, 13, 2);
      $my_form->input_button($form_id, "delete", "Mannschaft löschen", $x1+16,
                             18+$player_offset, 13, 2);

      // list pokal_ligen
      $arr_t_pokal = DBMapper::get_list_Pokalliga_of_Team($int_t_id,
                                                          DBMapper::get_id_Verband());
      $arr_pokal = DBMapper::get_list_Pokalliga(true);
      $arr_exists[0] = array();
      $arr_exists[1] = array();
      $arr_new[0] = array();
      $arr_new[1] = array();

      
      for($i=0; $i<count($arr_pokal[0]); $i++) {
        $found = false;
        for($k=0; $k<count($arr_t_pokal[0]); $k++) {
          if($arr_pokal[0][$i] == $arr_t_pokal[0][$k] &&
             $arr_pokal[1][$i] == $arr_t_pokal[1][$k]) {
            $found = true;
            $arr_exists[0][] = $arr_pokal[0][$i]."#".$arr_pokal[1][$i];
            $arr_exists[1][] = $arr_pokal[3][$i]." ".$arr_pokal[2][$i];
            break;
          }
        }
        if(!$found) {
          $arr_new[0][] = $arr_pokal[0][$i]."#".$arr_pokal[1][$i];
          $arr_new[1][] = $arr_pokal[3][$i]." ".$arr_pokal[2][$i];
        }
      }
      $pokal_off = 0;
      if(count($arr_exists[0]) > 0) {
        $pokal_off = 4;
        $my_form->input_select($form_id, "t_del_pokal_id", $arr_exists[1],
                               $arr_exists[0], 0, 1, 0, $x1,
                               21+$player_offset,18);
        $my_form->input_button($form_id, "del_pokal", "von Pokal entfernen",
                               $x2+11, 1.066*(21+$player_offset), 14, 2);
      }
      $my_form->input_select($form_id, "t_add_pokal_id", $arr_new[1],
                             $arr_new[0], 0, 1, 0, $x1,
                             21+$player_offset+$pokal_off,18);
      $my_form->input_button($form_id, "add_pokal", "zu Pokal hinzufügen",
                             $x2+11, 1.066*(21+$player_offset+$pokal_off),
                             14, 2);

    }
}
else if($bool_show_delete) {
  $my_form->input_hidden($form_id, "t_id", $int_t_id);
  foreach ($arr_player[0] as $key=>$value) {
    $my_form->input_hidden($form_id, "p_club_".$value, $arr_player[1][$key]);
  }
  $my_form->input_hidden($form_id, "t_name", $str_t_name);
  
  $my_form->print_text($form_id, "Bezeichnung:", $x1, 4);
  $my_form->print_text($form_id, $str_t_name, $x2, 4);
  $my_form->print_text($form_id, "Kurzbezeichnung:", $x1, 6);
  $my_form->print_text($form_id, $str_t_name, $x2, 6);
  $my_form->print_text($form_id, "Liga:", $x1, 8);
  for($i=0; $i<count($arr_ligen[0]); $i++) {
    if($arr_ligen[0][$i] == $int_t_liga) {
      $my_form->print_text($form_id, $arr_ligen[1][$i], $x2, 8);        
      break;
    }
  }
  $my_form->print_text($form_id, "Betreuer:", $x1, 10.3);
  for($i=0; $i<count($arr_t_id_leiter[0]); $i++) {
    if($arr_t_id_leiter[0][$i] == $int_t_id_leiter) {
      $my_form->print_text($form_id, $arr_t_id_leiter[1][$i], $x2, 10.3);
      break;
    }
  }
  
  $my_form->print_text($form_id, "Spielgemeinschaft:", $x1, 12.5);
  $my_form->input_checkbox($form_id, "t_sg", "t_sg", "", $x2, 12.7, -1,
                           -1, $bool_t_sg, 0);
  // Lizenzen zählen
  $count_lizenz = DBMapper::get_count_Lizenz($int_t_id);
  $my_form->print_text($form_id, "Anzahl der Lizenzen:", $x1, 14.8);
  $my_form->print_text($form_id, $count_lizenz, $x2+6, 14.8);
  
  // Spielgemeinschaften zählen? Muss nicht alle einträge zu der Mannschaft
  // können gelöscht werden
  
  // Begegnungen zählen
  $count_begegnung = DBMapper::get_count_Begegnung($int_t_id);
  $my_form->print_text($form_id, "Anzahl der Begegnungen:", $x1, 17.1);
  $my_form->print_text($form_id, $count_begegnung, $x2+6, 17.1);
  
  $my_form->input_button($form_id, "cancel", "Abbrechen", $x1+1, 19,
                         13, 2);
  if($count_lizenz > 0 ||
     $count_begegnung > 0) {
    $my_message->add_error("Die Mannschaft kann nicht gelöscht werden da noch Abhängigkeiten in der Datenbank bestehen!");
  }
  else {
    $my_message->add_warning("Vorsicht: Das Löschen der Mannschaft ist endgültig!");
    
    $my_form->input_button($form_id, "delete_ok", "Mannschaft löschen",
                           $x1+15, 19, 13, 2);
  }
}
$my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein", $x1+1, 
		       30+$player_offset, 13, 2);
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
