<?php

$x1 = 6;
$x2 = 36.7;

$arr_teams = DBMapper::get_list_Mannschaft_of_Pokal($int_id,
                                                    DBMapper::get_id_Verband());
$pokal = 1;
for($i=0; $i<count($arr_teams[0]); $i++) {
  $arr_teams[0][$i] .= "#".$arr_teams[2][$i];
}
$arr_teams[0][] = "0#0";
$arr_teams[1][] = "leer";


$form_id = $my_form->create_form("dm", "DM", -1, -1, 60, 96,
                                 'enctype="multipart/form-data"');
$my_form->print_text($form_id, "DM Ligen:", 3, 1);

$my_form->input_select($form_id, "list1", $arr_ligen[1], $arr_ligen[0],
                       $int_id, 1, 0, $x1+3, 1-0.2, 15);

$my_form->input_button($form_id, "load", "Laden", $x1+20.4, 0.7, 7, 2);

//$my_form->input_list($form_id, "list1", $arr_ligen[1], $arr_ligen[0], 2, 3,
//                     20, 20, 19, 2);

if($bool_show_data) {
  if($int_id > 0) $my_message->add_message("Wenn zu einer Liga bereits Lizenzen beantragt sind werden die Felder: \"Klasse\", \"Kategorie\" und \"Damen/Herren Liga\" nicht mehr überschrieben.");
  $off = -2;
  $my_form->input_hidden($form_id, "id", $int_id);
  $my_form->input_hidden($form_id, "nav", "league");
  $my_form->print_text($form_id, "Bezeichnung der Liga:", $x1, 7+$off);
  $my_form->input_text($form_id, "l_name", $str_l_name, 25, 255, $x2, 7+$off);
  $my_form->print_text($form_id, "Kurzname:", $x1, 9+$off);
  $my_form->input_text($form_id, "l_kurzname", $str_l_kurzname, 25, 255, $x2, 9+$off);
  //$my_form->print_text($form_id, "Saison:", $x1, 9+$off);
  //$my_form->print_text($form_id, $_SESSION['str_saison'], $x2, 9+$off);
  //$my_form->input_select($form_id, "id_season", $arr_seasons[1],
  // 	 $arr_seasons[0],$int_id_season, 1, 0,
  //			 $x2-0.1, 8.9+$off, 12);
  $my_form->print_text($form_id, "Kategorie:", $x1, 11+$off);
  if($int_id > 0) {
    if($int_id_kate == 100)
      $my_form->print_text($form_id, $arr_kate[1][0], $x2, 11+$off);  
    else
      $my_form->print_text($form_id, $arr_kate[1][1], $x2, 11+$off);  
  }
  else {
    $my_form->input_select($form_id, "id_kate", $arr_kate[1], $arr_kate[0],
                           $int_id_kate, 1, 0, $x2-0.1, 10.9+$off, 12);
  }
  /*
  $my_form->print_text($form_id, "Klasse:", $x1, 13+$off);
  $my_form->input_select($form_id, "id_klasse", $arr_klasse[1], $arr_klasse[0],
			 $int_id_klasse, 1, 0, $x2-0.1, 12.9+$off, 12);
  */
  $my_form->print_text($form_id, "Spielsystem:", $x1, 13+$off);
  $my_form->input_select($form_id, "id_spielsystem", $arr_spielsysteme[1],
			 $arr_spielsysteme[0],$int_id_spielsystem, 1, 0,
			 $x2-0.1, 12.9+$off, 12);
  $arr_weiblich[0][] = 0;
  $arr_weiblich[1][] = "Herren Liga";
  $arr_weiblich[0][] = 1;
  $arr_weiblich[1][] = "Damen Liga";
  $my_form->print_text($form_id, "Damen/Herren Liga:", $x1, 15+$off);  
  $my_form->input_select($form_id, "l_weiblich", $arr_weiblich[1],
                         $arr_weiblich[0],$bool_weiblich, 1, 0,
                         $x2-0.1, 14.9+$off, 12);
  $my_form->print_text($form_id, "Ordnungsnummer:", $x1, 17+$off);
  $my_form->input_text($form_id, "l_ordnungsnr", $int_l_ord,
                       10, 10, $x2, 17+$off);

  //$my_form->print_text($form_id, "Stichtag:", $x1, 21+$off);
  //$my_form->input_text($form_id, "l_date", $str_l_date, 10, 10, $x2, 21+$off);
  //$my_form->print_text($form_id, "Spieler müssen jünger als Stichtag sein:",
  //                     $x1, 23+$off);
  //$my_form->input_radio($form_id, "stich_min", "stich_min", "",
  //                      $x2+9, 23.15+$off, -1, -1, $bool_stich);
  //$my_form->print_text($form_id, "Spieler müssen älter als Stichtag sein:",
  //                     $x1, 25+$off);
  //$my_form->input_radio($form_id, "stich_min", "stich_min", "",
  //                      $x2+9, 25.15+$off, -1, -1, !$bool_stich);
  $my_form->print_text($form_id, "Begegnungen Gruppe A:", $x1, 23+$off);
  $var_num = 0;
  for($i=0; $i<6; $i++) {
    $var_num++;
    if($int_id > 0) {
      $my_form->print_text($form_id, $str_b_date[$i], $x1+2, 25+$off+$i*1.7);
    }
    else {
      $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[$i],
                           10, 10, $x1+2, 25+$off+$i*1.7);
    }
    $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[$i],
                         6, 10, $x1+10, 25+$off+$i*1.7);
    $my_form->input_select($form_id, "team1_".$var_num, $arr_teams[1],
                           $arr_teams[0],$arr_team_id[$var_num-1][0], 1, 0,
                           $x1+16, 24.7+$off+$i*1.7, 12);
    $my_form->input_select($form_id, "team2_".$var_num, $arr_teams[1],
                           $arr_teams[0],$arr_team_id[$var_num-1][1], 1, 0,
                           $x1+29, 24.7+$off+$i*1.7, 12);
  }

  $my_form->print_text($form_id, "Begegnungen Gruppe B:", $x1, 38+$off);
  for($i=0; $i<6; $i++) {
    $var_num++;
    if($int_id > 0) {
      $my_form->print_text($form_id, $str_b_date[$i], $x1+2, 40+$off+$i*1.7);
    }
    else {
      $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[$i+6],
                           10, 10, $x1+2, 40+$off+$i*1.7);
    }
    $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[$i+6],
                         6, 10, $x1+10, 40+$off+$i*1.7);
    $my_form->input_select($form_id, "team1_".$var_num, $arr_teams[1],
                           $arr_teams[0],$arr_team_id[$var_num-1][0], 1, 0,
                           $x1+16, 39.7+$off+$i*1.7, 12);
    $my_form->input_select($form_id, "team2_".$var_num, $arr_teams[1],
                           $arr_teams[0],$arr_team_id[$var_num-1][1], 1, 0,
                           $x1+29, 39.7+$off+$i*1.7, 12);
  }
  $var_num++;
  $my_form->print_text($form_id, "1. Halbfinale (Spiel a):", $x1, 53+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[12], 10, 10,
                       $x1+2, 55+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[12], 6, 10,
                       $x1+10, 55+$off);
  $my_form->print_text($form_id, "1. Gruppe A vs 2. Gruppe B", $x1+20, 55+$off);

  $var_num++;
  $my_form->print_text($form_id, "2. Halbfinale (Spiel b):", $x1, 58+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[13], 10, 10,
                       $x1+2, 60+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[13], 6, 10,
                       $x1+10, 60+$off);
  $my_form->print_text($form_id, "2. Gruppe A vs 1. Gruppe B", $x1+20, 60+$off);

  $var_num++;
  $my_form->print_text($form_id, "Spiel um Platz 5:", $x1, 63+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[14], 10, 10,
                       $x1+2, 65+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[14], 6, 10,
                       $x1+10, 65+$off);
  $my_form->print_text($form_id, "3. Gruppe A vs 3. Gruppe B", $x1+20, 65+$off);

  $var_num++;
  $my_form->print_text($form_id, "Spiel um Platz 7:", $x1, 68+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[15], 10, 10,
                       $x1+2, 70+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[15], 6, 10,
                       $x1+10, 70+$off);
  $my_form->print_text($form_id, "4. Gruppe A vs 4. Gruppe B", $x1+20, 70+$off);

  $var_num++;
  $my_form->print_text($form_id, "Spiel um Platz 3:", $x1, 73+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[16], 10, 10,
                       $x1+2, 75+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[16], 6, 10,
                       $x1+10, 75+$off);
  $my_form->print_text($form_id, "Verlierer Spiel a vs Verlierer Spiel b", $x1+20, 75+$off);

  $var_num++;
  $my_form->print_text($form_id, "Finale:", $x1, 78+$off);
  $my_form->input_text($form_id, "beg".$var_num."_date", $str_b_date[17], 10, 10,
                       $x1+2, 80+$off);
  $my_form->input_text($form_id, "beg".$var_num."_time", $str_b_time[17], 6, 10,
                       $x1+10, 80+$off);
  $my_form->print_text($form_id, "Sieger Spiel a vs Sieger Spiel b", $x1+20, 80+$off);
  

  if($int_id > -1) {
    $my_form->input_button($form_id, "save", "DM speichern", $x1+15, 90, 13, 2);
    //$my_form->input_button($form_id, "delete", "Liga löschen", $x2+10, 30, 13, 2);
  }
  else {
    $my_form->input_button($form_id, "save", "DM anlegen", $x1+15, 90, 13, 2);
  }
    //$my_form->input_file($form_id, "l_file", 25, 255, $x1, 24);
    //$my_form->input_button($form_id, "read_file", "Datei einlesen", $x1+2, 26, 13, 2);
}
/*
else if($bool_show_delete) {
  $off = -2;
  $x2 = 40.7;
  $my_form->input_hidden($form_id, "id", $int_id);
  $my_form->input_hidden($form_id, "nav", "league");
  $my_form->input_hidden($form_id, "l_name", $str_l_name);
  $my_form->print_text($form_id, "Bezeichnung der Liga:", $x1, 5+$off);
  $my_form->print_text($form_id, $str_l_name, $x2, 5+$off);
  $my_form->print_text($form_id, "Kurzname:", $x1, 7+$off);
  $my_form->print_text($form_id, $str_l_kurzname, $x2, 7+$off);
  $my_form->print_text($form_id, "Saison:", $x1, 9+$off);
  $my_form->print_text($form_id, $_SESSION['str_saison'], $x2, 9+$off);
  $my_form->print_text($form_id, "Kategorie:", $x1, 11+$off);
  $str_kate = "";
  for($i = 0; $i<count($arr_kate[0]); $i++) {
    if($arr_kate[0][$i] == $int_id_kate) {
      $str_kate = $arr_kate[1][$i];
      break;
    }
  }
  $my_form->print_text($form_id, $str_kate, $x2, 11+$off);
  $my_form->print_text($form_id, "Klasse:", $x1, 13+$off);
  $str_klasse = "";
  for($i = 0; $i<count($arr_klasse[0]); $i++) {
    if($arr_klasse[0][$i] == $int_id_klasse) {
      $str_klasse = $arr_klasse[1][$i];
      break;
    }
  }
  $my_form->print_text($form_id, $str_klasse, $x2, 13+$off);

  $my_form->print_text($form_id, "Spielsystem:", $x1, 15+$off);
  $str_spielsystem = "";
  for($i = 0; $i<count($arr_spielsysteme[0]); $i++) {
    if($arr_spielsysteme[0][$i] == $int_id_spielsystem) {
      $str_spielsystem = $arr_spielsysteme[1][$i];
      break;
    }
  }
  $my_form->print_text($form_id, $str_spielsystem, $x2, 15+$off);
  $my_form->print_text($form_id, "Damen/Herren Liga:", $x1, 17+$off);
  if($bool_weiblich) $str_weiblich = "Damen Liga";
  else $str_weiblich = "Herren Liga";
  $my_form->print_text($form_id, $str_weiblich, $x2, 17+$off);

  $my_form->print_text($form_id, "Stichtag:", $x1, 19+$off);
  $my_form->print_text($form_id, $str_l_date, $x2, 19+$off);
  $my_form->print_text($form_id, "Spieler müssen jünger als Stichtag sein:",
                       $x1, 21+$off);
  $my_form->input_radio($form_id, "stich_min", "stich_min", "",
                        $x2+5, 21.15+$off, -1, -1, $bool_stich, 0);
  $my_form->print_text($form_id, "Spieler müssen älter als Stichtag sein:",
                       $x1, 23+$off);
  $my_form->input_radio($form_id, "stich_min", "stich_min", "",
                        $x2+5, 23.15+$off, -1, -1, !$bool_stich, 0);
  
  $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id);
  $int_count_teams = count($arr_teams[0]);
  $my_form->print_text($form_id, "Anzahl der Mannschafften:", $x1, 25+$off);
  $my_form->print_text($form_id, $int_count_teams, $x2, 25+$off);
  $int_count_player = 0;
  for($i=0; $i<$int_count_teams; $i++) {
    $arr_l = DBM_Spieler::get_list_Spieler_of_Mannschaft($arr_teams[0][$i]);
    $int_count_player += count($arr_l[0]);
  }
  $my_form->print_text($form_id, "Anzahl der Lizenzen:", $x1, 27+$off);
  $my_form->print_text($form_id, $int_count_player, $x2, 27+$off);

  $arr_spieltage = DBMapper::get_list_Spieltag($int_id);
  $int_count_spieltage = count($arr_spieltage[0]);
  $my_form->print_text($form_id, "Anzahl der Spieltage:", $x1, 29+$off);
  $my_form->print_text($form_id, $int_count_spieltage, $x2, 29+$off);

  $my_form->input_button($form_id, "cancel", "Abrechen", $x1+8, 33+$off, 13, 2);
  if($int_count_player > 0 || $int_count_teams > 0 ||
     $int_count_spieltage > 0) {
      $my_message->add_error("Die Liga kann nicht gelöscht werden da noch Abhängigkeiten in der Datenbank bestehen!");
  }
  else {
    $my_message->add_warning("Vorsicht: Das Löschen einer Liga ist endgültig!");
    $my_form->input_button($form_id, "delete_ok", "Liga löschen", $x2+6,
                           33+$off, 13, 2);
  }
}
*/
//$my_form->input_file($form_id, "ad_file", 25, 255, $x1, 28);
//$my_form->input_button($form_id, "read_ad_file", "Datei einlesen", $x1+2, 32, 13, 2);
//$my_form->input_file($form_id, "ad_pl_file", 25, 255, $x1, 32);
//$my_form->input_button($form_id, "read_ad_pl_file", "Datei einlesen", $x1+2, 36, 13, 2);

//$my_form->input_file($form_id, "ad_nation_file", 25, 255, $x1, 36);
//$my_form->input_button($form_id, "read_ad_nation_file", "Datei einlesen", $x1+2, 40, 13, 2);
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
