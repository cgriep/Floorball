<?php
$x1 = 21;
$x2 = 33.2;
if($bool_show_delete) $f_off = 10;
else $f_off = 0;
$arr_id_leiter = DBMapper::get_list_Person(0);
if(isset($int_id) && $int_id > 0) {
  $arr_tmp = DBMapper::get_list_Person($int_id);
  for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_id_leiter[0][] = $arr_tmp[0][$i];
    $arr_id_leiter[1][] = $arr_tmp[1][$i];
  }
}

$arr_id_leiter[0][] = 0;
$arr_id_leiter[1][] = "leer";
$arr_vereine[0][0] = 0;
$arr_vereine[0][1] = 0;
$arr_vereine[1][0] = "Neuen Verein anlegen";
$arr_vereine[1][1] = "";
$arr_tmp = DBMapper::get_list_Verein();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_vereine[0][$i+2] = $arr_tmp[0][$i];
    $arr_vereine[1][$i+2] = $arr_tmp[1][$i];
}

$form_id = $my_form->create_form("clubs", "Vereine", -1, -1, 60, 48+$f_off);
$my_form->print_text($form_id, "Vereine", 3, 1);
$my_form->input_list($form_id, "list1", $arr_vereine[1], $arr_vereine[0], 2, 3, 14, 20, 13, 2);
if($bool_show_data) {
    $my_form->input_hidden($form_id, "id", $int_id);
    $my_form->input_hidden($form_id, "nav", "clubs");
    if($int_id == -1 || DBMapper::check_Heimatverband($int_id)) {
      if ( $int_id != -1 ) 
      {
      	$my_form->print_text($form_id, "Vereins-ID:", $x1, 1);
        $my_form->print_text($form_id, $int_id, $x2, 1);
      }
      
      $my_form->print_text($form_id, "Vereinsname:", $x1, 3);
      $my_form->input_text($form_id, "name", $str_name, 25, 255, $x2, 3);
      $my_form->print_text($form_id, "Kurzname:", $x1, 5);
      $my_form->input_text($form_id, "k_name", $str_k_name, 25, 255, $x2, 5);
      $my_form->print_text($form_id, "Stra&szlig;e:", $x1, 7);
      $my_form->input_text($form_id, "street", $str_street, 25, 255, $x2, 7);
      $my_form->print_text($form_id, "Hausnummer:", $x1, 9);
      $my_form->input_text($form_id, "s_nbr", $str_nbr, 5, 20, $x2, 9);
      $my_form->print_text($form_id, "PLZ:", $x1, 11);
      $my_form->input_text($form_id, "plz", $str_plz, 5, 5, $x2, 11);
      $my_form->print_text($form_id, "Ort:", $x1, 13);
      $my_form->input_text($form_id, "place", $str_place, 25, 255, $x2, 13);
      $my_form->print_text($form_id, "Vereins Homepage:", $x1, 15);
      $my_form->input_text($form_id, "hp_club", $str_hp_club, 25, 255, $x2, 15);
      $my_form->print_text($form_id, "Homepage Sparte:", $x1, 17);
      $my_form->input_text($form_id, "hp_part", $str_hp_part, 25, 255, $x2, 17);
      $my_form->print_text($form_id, "Ansprechpartner:", $x1, 19);
      $my_form->input_select($form_id, "id_leiter", $arr_id_leiter[1],
                             $arr_id_leiter[0], $int_id_leiter, 1, 0,
                             $x2, 19, 12);
      $my_form->input_button($form_id, "next", "Neue Person anlegen",
                             $x2+17, 20, 12, 2);
      if($int_id == -1) {
        $my_form->input_button($form_id, "save", "Verein anlegen", $x1+15, 24,
                               13, 2);    
      }
      else {
        $my_form->input_button($form_id, "save", "Verein speichern", $x1+15, 24,
                               13, 2);
        $my_form->input_button($form_id, "delete", "Verein löschen", $x1+29, 24,
                               13, 2);
        $my_form->input_button($form_id, "next", "Mannschaften bearbeiten",
                               $x1+1, 28, 13, 2);
        $my_form->input_button($form_id, "next", "Spieler bearbeiten",
                               $x1+15, 28, 13, 2);
      
        $arr_gastverbaende = DBMapper::get_list_Gastverbaende($int_id);
        if(count($arr_gastverbaende[0]) > 0) {
          $offg = 9;
          $my_form->print_text($form_id, "Verein freigegeben für weitere Verbände:",
                               $x1, 31);
          $my_form->input_select($form_id, "id_gastverband", $arr_gastverbaende[1],
                                 $arr_gastverbaende[0], 0, 1, 0, $x1, 33, 26);
          $my_form->input_button($form_id, "refreigabe",
                                 "Vereinsfreigabe zurücknehmen",
                                 $x1+13.6, 38, 16, 2);
        }
        else $offg = 0;
        $arr_tmp = DBMapper::get_list_Verband();
        for($i=0; $i<count($arr_tmp[0]); $i++) {
          if($arr_tmp[3][$i] != VERBAND) {
            $found = 0;
            for($k=0; $k<count($arr_gastverbaende[0]); $k++) {
              if($arr_tmp[0][$i] == $arr_gastverbaende[0][$k]) {
                $found = 1;
                break;
              }
            }
            if(!$found) {
              $arr_verbaende[0][] = $arr_tmp[0][$i];
              $arr_verbaende[1][] = $arr_tmp[1][$i];
              $arr_verbaende[2][] = $arr_tmp[2][$i];
            }
          }
        }
        if(isset($arr_verbaende)) {
          $my_form->print_text($form_id,
                               "Verein einem weiteren Verband freigeben:",
                               $x1, 31+$offg);
          $my_form->input_select($form_id, "id_verband", $arr_verbaende[1],
                                 $arr_verbaende[0], 0, 1, 0, $x1, 33+$offg, 26);
          $my_form->input_button($form_id, "freigabe",
                                 "Verein an Verband freigeben",
                                 $x1+13.6, 38+$offg*1.08, 16, 2);
        }
      }
    }
    else {
      $my_form->print_text($form_id, "Verein-ID:", $x1, 2);
      $my_form->print_text($form_id, $int_id, $x2, 2);
      $my_form->print_text($form_id, "Vereinsname:", $x1, 3);
      $my_form->print_text($form_id, $str_name, $x2, 3);
      $my_form->input_hidden($form_id, "name", $str_name);
      $my_form->print_text($form_id, "Kurzname:", $x1, 5);
      $my_form->print_text($form_id, $str_k_name, $x2, 5);
      $my_form->input_hidden($form_id, "k_name", $str_k_name);
      $my_form->print_text($form_id, "Stra&szlig;e:", $x1, 7);
      $my_form->print_text($form_id, $str_street, $x2, 7);
      $my_form->input_hidden($form_id, "street", $str_street);
      $my_form->print_text($form_id, "Hausnummer:", $x1, 9);
      $my_form->print_text($form_id, $str_nbr, $x2, 9);
      $my_form->input_hidden($form_id, "s_nbr", $str_nbr);
      $my_form->print_text($form_id, "PLZ:", $x1, 11);
      $my_form->print_text($form_id, $str_plz, $x2, 11);
      $my_form->input_hidden($form_id, "plz", $str_plz);
      $my_form->print_text($form_id, "Ort:", $x1, 13);
      $my_form->print_text($form_id, $str_place, $x2, 13);
      $my_form->input_hidden($form_id, "place", $str_place);
      $my_form->print_text($form_id, "Vereins Homepage:", $x1, 15);
      $my_form->print_text($form_id, $str_hp_club, $x2, 15);
      $my_form->input_hidden($form_id, "hp_club", $str_hp_club);
      $my_form->print_text($form_id, "Homepage Sparte:", $x1, 17);
      $my_form->print_text($form_id, $str_hp_part, $x2, 17);
      $my_form->input_hidden($form_id, "hp_part", $str_hp_part);
      $my_form->print_text($form_id, "Ansprechpartner:", $x1, 19);
      //$my_form->input_select($form_id, "id_leiter", $arr_id_leiter[1], $arr_id_leiter[0], $int_id_leiter, 1, 0, $x2, 19, 12);
      $str_leiter = "";
      for($i=0; $i<count($arr_id_leiter[0]); $i++) {
        if($int_id_leiter == $arr_id_leiter[0][$i]) {
          $str_leiter = $arr_id_leiter[1][$i];
          break;
        }
      }
      $my_form->print_text($form_id, $str_leiter, $x2, 19);
      $my_form->input_hidden($form_id, "id_leiter", $int_id_leiter);
    
      $my_form->input_button($form_id, "next", "Mannschaften bearbeiten", $x1+1,
                             28, 13, 2);
      $my_form->input_button($form_id, "next", "Spieler bearbeiten", $x1+15, 28,
                             13, 2);
    }
}
else if($bool_show_delete) {
    $my_form->input_hidden($form_id, "id", $int_id);
    $my_form->input_hidden($form_id, "nav", "clubs");
    $my_form->input_hidden($form_id, "name", $str_name);
    $my_form->print_text($form_id, "Vereinsname:", $x1, 3);
    $my_form->print_text($form_id, $str_name, $x2, 3);
    $my_form->print_text($form_id, "Kurzname:", $x1, 5);
    $my_form->print_text($form_id, $str_k_name, $x2, 5);
    $my_form->print_text($form_id, "Stra&szlig;e:", $x1, 7);
    $my_form->print_text($form_id, $str_street, $x2, 7);
    $my_form->print_text($form_id, "Hausnummer:", $x1, 9);
    $my_form->print_text($form_id, $str_nbr, $x2, 9);
    $my_form->print_text($form_id, "PLZ:", $x1, 11);
    $my_form->print_text($form_id, $str_plz, $x2, 11);
    $my_form->print_text($form_id, "Ort:", $x1, 13);
    $my_form->print_text($form_id, $str_place, $x2, 13);
    $my_form->print_text($form_id, "Vereins Homepage:", $x1, 15);
    $my_form->print_text($form_id, $str_hp_club, $x2, 15);
    $my_form->print_text($form_id, "Homepage Sparte:", $x1, 17);
    $my_form->print_text($form_id, $str_hp_part, $x2, 17);
    $my_form->print_text($form_id, "Ansprechpartner:", $x1, 19);
    //$my_form->input_select($form_id, "id_leiter", $arr_id_leiter[1], $arr_id_leiter[0], $int_id_leiter, 1, 0, $x2, 19, 12);
    $str_leiter = "";
    for($i=0; $i<count($arr_id_leiter[0]); $i++) {
      if($int_id_leiter == $arr_id_leiter[0][$i]) {
	$str_leiter = $arr_id_leiter[1][$i];
	break;
      }
    }
    $my_form->print_text($form_id, $str_leiter, $x2, 19);
    $my_x_off = 4;
    // spieler
    $count_spieler = DBM_Spieler::get_count_Spieler_of_Verein($int_id);
    $my_form->print_text($form_id, "Anzahl Spieler:", $x1, 21);
    $my_form->print_text($form_id, $count_spieler, $x2+$my_x_off, 21);

    // schiedsrichter
    $count_schiedsrichter = DBMapper::get_count_Schiedsrichter($int_id);
    $my_form->print_text($form_id, "Anzahl Schiedsrichter:", $x1, 23);
    $my_form->print_text($form_id, $count_schiedsrichter, $x2+$my_x_off, 23);

    // mannschaft
    $count_mannschaft = DBMapper::get_count_Mannschaft($int_id);
    $my_form->print_text($form_id, "Anzahl Mannschaften:", $x1, 25);
    $my_form->print_text($form_id, $count_mannschaft, $x2+$my_x_off, 25);

    // bei personen die vereins_id auf null setzen
    // spielgemeinschaft
    $count_sg = DBMapper::get_count_SG($int_id);
    $my_form->print_text($form_id, "Anzahl Spielgemeinschaften:", $x1, 27);
    $my_form->print_text($form_id, $count_sg, $x2+$my_x_off, 27);

    // spielort
    $count_spielort = DBMapper::get_count_Spielort($int_id);
    $my_form->print_text($form_id, "Anzahl Spielorte:", $x1, 29);
    $my_form->print_text($form_id, $count_spielort, $x2+$my_x_off, 29);

    $my_form->input_button($form_id, "cancel", "Abbrechen", $x1+15, 35,
			   13, 2);
    if($count_spieler > 0 ||
       $count_schiedsrichter > 0 ||
       $count_mannschaft > 0 ||
       $count_sg > 0 ||
       $count_spielort > 0) {
      $my_message->add_error("Der Verein kann nicht gelöscht werden da noch Abhängigkeiten in der Datenbank bestehen!");
    }
    else {
      $my_message->add_warning("Vorsicht: Das Löschen eines Vereins ist endgültig!");
      $my_form->input_button($form_id, "delete_ok", "Verein löschen",
                             $x1+29, 35, 13, 2);
    }
}
else  $my_message->add_message("Auf dieser Seiten können neue Vereine angelegt werden und bestehende bearbeitet werden. Das Bearbeiten von Vereinen beinhaltet auch das Verwalten von Mannschaften und Spielern des Vereins.");
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
