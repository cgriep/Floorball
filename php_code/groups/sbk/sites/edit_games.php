<?php
  /*
$x1 = 0.5;
$x2 = 7;
$x3 = 12;
$x4 = 29;
$x5 = 46;
$x6 = 60;
$x7 = 70;
  */
$x1 = 0.5;
$x2 = 0.5;
$x3 = 5.0;
$x4 = 20.5;
$x5 = 35.5;
$x6 = 50.0;
$x7 = 58.0;
$x8 = 64.5;

$Liga = DBM_Ligen::read_Liga($int_id_league);
$pokal = 0;
if($Liga) $id_kategorie = $Liga->get_int_id_kategorie();
else $id_kategorie = 0;
if($id_kategorie == 3 || $id_kategorie == 4 || $id_kategorie >= 100) {
  $arr_teams = DBMapper::get_list_Mannschaft_of_Pokal($int_id_league,
                                                      DBMapper::get_id_Verband());
  $pokal = 1;
  for($i=0; $i<count($arr_teams[0]); $i++) {
    $arr_teams[0][$i] .= "#".$arr_teams[2][$i];
  }
}
else
  $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id_league);

  $arr_teams[0][] = "0";
$arr_teams[1][] = "leer";
$arr_schiri = DBMapper::get_list_Schiedsrichter();
$arr_schiri[0][] = 0;
$arr_schiri[1][] = "leer";
for($i=0; $i<count($arr_schiri[0])-1; $i++) {
  $arr_schiri[1][$i] .= ", ".$arr_schiri[2][$i];
}

$arr_forfeit[0] = array(0, 1, 2, 3);
$arr_forfeit[1] = array("-", "team1", "team2", "beide");

$arr_games = DBMapper::get_list_Begegnung($int_id);

if($pokal) {
  for($i=0; $i<count($arr_games[0]); $i++) {
    $arr_games[1][$i] .= "#".$arr_games[7][$i];
    $arr_games[2][$i] .= "#".$arr_games[8][$i];
  }
}

if(count($arr_games) > 1) $yform = count($arr_games[0])*2;
else $yform = 0;

$form_id = $my_form->create_form("games", "Spieltage", -1, -1, 70, 20+$yform);
$my_form->print_text($form_id, "Begegnungen festlegen", 1, 1);

$my_form->input_hidden($form_id, "nav", "edit_games");
$my_form->input_hidden($form_id, "id", $int_id);
$my_form->input_hidden($form_id, "date", $str_date);
$my_form->input_hidden($form_id, "id_league", $int_id_league);
$my_form->input_hidden($form_id, "id_place", $int_id_place);
$my_form->input_hidden($form_id, "spieltag_nr", $str_spieltag_nr);

//$my_form->print_text($form_id, "Datum", $x1, 4);
$my_form->print_text($form_id, "Uhrzeit", $x2, 4);
$my_form->print_text($form_id, "Team1", $x3, 4);
$my_form->print_text($form_id, "Team2", $x4, 4);
$my_form->print_text($form_id, "Schiedsrichter", $x5, 4);
$my_form->print_text($form_id, "Sp.-nr.", $x6, 4);
$my_form->print_text($form_id, "forfeit", 65.5, 4);

$y = 6;
if(count($arr_games) > 1) {
  for($i=0; $i<count($arr_games[0]); $i++) {
    $Spielbericht = DBMapper::read_Spielbericht($arr_games[0][$i]);
   
    for($k=0; $k<count($arr_teams[0]); $k++) {
      if($arr_games[1][$i] == $arr_teams[0][$k])
        $arr_games[11][$i] = $arr_teams[1][$k];
      if($arr_games[2][$i] == $arr_teams[0][$k])
        $arr_games[12][$i] = $arr_teams[1][$k];
    }
    
    //$my_form->print_text($form_id, $str_date, $x1, $y);
    //$my_form->print_text($form_id, $arr_games[3][$i], $x2, $y);
    $my_form->input_text($form_id, "t_".$arr_games[0][$i],
                         $arr_games[3][$i], 4, 8, $x2, $y);
    if($Spielbericht) {
      $my_form->print_text($form_id, $arr_games[11][$i], $x3, $y);
      $my_form->input_hidden($form_id, "id_team1_".$arr_games[0][$i],
                             $arr_games[1][$i]);
    }
    else {
      $my_form->input_select($form_id, "id_team1_".$arr_games[0][$i],
                             $arr_teams[1], $arr_teams[0], $arr_games[1][$i],
                             1, 0, $x3, $y*0.965, 14);
    }
    $my_form->print_text($form_id, ":", $x4-0.8, $y);
    if($Spielbericht) {
      $my_form->print_text($form_id, $arr_games[12][$i], $x4, $y);
      $my_form->input_hidden($form_id, "id_team2_".$arr_games[0][$i],
                             $arr_games[2][$i]);
    }
    else {
      //$my_form->print_text($form_id, $arr_games[2][$i], $x4, $y);
      $my_form->input_select($form_id, "id_team2_".$arr_games[0][$i],
                             $arr_teams[1], $arr_teams[0], $arr_games[2][$i],
                             1, 0, $x4, $y*0.965, 14);
    }
    // hier die Schiedsrichter wieder auseinander pflücken
    $arr_tmp = explode("#", $arr_games[4][$i]);
    //$my_form->print_text($form_id, $arr_tmp[0], $x5, $y);
    $my_form->input_text($form_id, "ref_".$arr_games[0][$i],
                         $arr_tmp[0], 20, 255, $x5, $y);
    //$my_form->print_text($form_id, $arr_games[5][$i], $x6+2, $y);
    $my_form->input_text($form_id, "g_num_".$arr_games[0][$i],
                         $arr_games[5][$i], 3, 5, $x6, $y);

    $my_form->input_button($form_id, "save_".$arr_games[0][$i], "speichern", $x7, $y*1.05, 5.5, 1.5);
    $my_form->input_button($form_id, "del_".$arr_games[0][$i], "del", $x8, $y*1.05, 4, 1.5);
    $my_form->input_select($form_id, "arr_forfeit".$i, $arr_forfeit[1], $arr_forfeit[0], $arr_games[6][$i], 1, 0, 64.5, $y-0.3, 5);
    $y += 2;
  }
}
$y += 1.2;
$my_form->input_button($form_id, "set", "set", 70.5, $y, 4, 1.5);
$y += 5;
//$my_form->print_text($form_id, $str_date, $x1, $y);
$my_form->input_text($form_id, "g_time", "", 4, 8, $x2, $y);
$my_form->input_select($form_id, "id_team1", $arr_teams[1], $arr_teams[0], 0, 1, 0, $x3, $y*0.983, 14);
$my_form->print_text($form_id, ":", $x4-0.8, $y);
$my_form->input_select($form_id, "id_team2", $arr_teams[1], $arr_teams[0], 0, 1, 0, $x4, $y*0.983, 14);
$my_form->input_text($form_id, "referees", "", 20, 255, $x5, $y);
//$my_form->input_select($form_id, "id_schiri1", $arr_schiri[1], $arr_schiri[0],
//                       0, 1, 0, $x5-1, $y-0.2, 14);
//$my_form->input_select($form_id, "id_schiri2", $arr_schiri[1], $arr_schiri[0],
//                       0, 1, 0, $x5-1, $y+1.8, 14);
$my_form->input_text($form_id, "g_number", "", 3, 5, $x6, $y);
$my_form->input_text($form_id, "g_playoff", "", 1, 1, $x6+4, $y);

$my_form->input_button($form_id, "save", "ok", $x7+2.5, $y*1.065, 4, 1.5);
$my_form->input_button($form_id, "back", "Zur&uuml;ck zum Spieltag", $x1, $y+5, 15, 2);

echo "\n<br/><center style=\"font-size: 0.8em;\">\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>