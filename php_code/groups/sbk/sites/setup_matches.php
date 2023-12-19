<?php
$x1 = 24;
$x2 = 34.0;

$arr_league = DBM_Ligen::get_list_Liga();
$arr_league[0][] = 0;
$arr_league[1][] = "leer";
$arr_places = DBMapper::get_list_Spielort();
$arr_places[0][] = 0;
$arr_places[1][] = "leer";

if(!$int_id_league) {
  $form_id = $my_form->create_form("setup_matches", "Spieltage", -1, -1, 60, 30);
  $my_form->input_hidden($form_id, "nav", "setup_matches");
  $my_form->print_text($form_id, "Liga:", 3, 1);
  $my_form->input_select($form_id, "id_league", $arr_league[1], $arr_league[0], $int_id_league, 1, 0, 8, 1-0.2, 15);
 $my_form->input_button($form_id, "show", "anzeigen", 25, 1-0.2, 8, 1.7);
 }
 else {
  $arr_matches[0][] = 0;
  $arr_matches[1][] = "Neuen Spieltag anlegen";
  $arr_matches[0][] = 0;
  $arr_matches[1][] = "";
  $arr_tmp = DBMapper::get_list_Spieltag($int_id_league);
  for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_matches[0][] = $arr_tmp[0][$i];
    $arr_matches[1][] = $arr_tmp[1][$i] . " - " . $arr_tmp[3][$i];
  }

  $form_id = $my_form->create_form("setup_matches", "Spieltage", -1, -1, 60, 30);
  $my_form->print_text($form_id, "Spieltage", 3, 1);
  $my_form->input_list($form_id, "list1", $arr_matches[1], $arr_matches[0], 2, 3, 20, 20, 19, 2);
  $my_form->input_hidden($form_id, "nav", "setup_matches");
  $my_form->input_hidden($form_id, "id_league", $int_id_league);
  if($bool_show_data) {
    $my_form->input_hidden($form_id, "id", $int_id);
    $my_form->print_text($form_id, "Spieltagsdatum:", $x1, 5);
    $my_form->input_text($form_id, "date", $str_date, 12, 255, $x2, 5);
    $my_form->print_text($form_id, "Spieltagsnummer:", $x1, 7);
    $my_form->input_text($form_id, "spieltag_nr", $str_spieltag_nr,
                         12, 255, $x2, 7);
    $my_form->print_text($form_id, "Liga:", $x1, 9.4);
    for($i=0;$i<count($arr_league[0]); $i++)
      if($arr_league[0][$i] == $int_id_league) {
        $my_form->print_text($form_id, $arr_league[1][$i], $x2-0.1, 9.4);
        break;
      }

    //    $my_form->input_select($form_id, "id_league", $arr_league[1], $arr_league[0],$int_id_league, 1, 0, $x2-0.1, 9.4, 20);
    $my_form->print_text($form_id, "Spielort:", $x1, 11.8);
    $my_form->input_select($form_id, "id_place", $arr_places[1], $arr_places[0],$int_id_place, 1, 0, $x2-0.1, 11.8, 12);
    $my_form->input_button($form_id, "new_place", "Neuer Spielort", $x2+16, 12.4, 13, 2);
    $my_form->input_button($form_id, "edit_games", "Begegnungen editieren", $x1+16, 22, 13, 2);
    $my_form->input_button($form_id, "save", "Daten speichern", $x1+2, 22, 13, 2);
  }
 }
echo "\n<br/><center>\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>
