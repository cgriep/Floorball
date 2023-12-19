<?php
$x1 = 19;
$x2 = 35;
$x3 = 38;

$arr_player[0][0] = 0;
$arr_player[0][1] = 0;
$arr_player[1][0] = "Neuen Spieler anlegen";
$arr_player[1][1] = "";
$arr_tmp = DBM_Spieler::get_list_Spieler_of_Verein($club_id);
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


$form_id = $my_form->create_form("teams", "Spieler", -1, -1, 60, 37);
$my_form->print_text($form_id, "Spieler des Vereins: ".$club_str." (".$club_id.")", 3, 1);
$my_form->input_list($form_id, "list1", $arr_player[1], $arr_player[0], 2, 4,
                     14, 20, 13, 2);
$my_form->input_hidden($form_id, "nav", "player");

if($int_pl_id > 0)
  $int_count_lizenz = DBMapper::get_count_Lizenz_of_Spieler($int_pl_id);
else $int_count_lizenz = -1;

if($int_count_lizenz == 0) $load = false;

if($bool_show_data) {
  // Makierungen fÃ¼r Pflichtfelder
  $my_form->print_text($form_id, "*", $x2+17.5, 4);
  $my_form->print_text($form_id, "*", $x2+17.5, 6);
  $my_form->print_text($form_id, "*", $x2+17.5, 8);
  $my_form->print_text($form_id, "*", $x2+13, 10);
  $my_form->print_text($form_id, "*", $x2+13, 20);
  $my_form->print_text($form_id, "* Pflichtfelder", $x1, 23);

  $my_form->input_hidden($form_id, "pl_id", $int_pl_id);
  if ( $int_pl_id > -1 )
  {
  	$my_form->print_text($form_id, "Spieler-ID:", $x1, 2);
  	$my_form->print_text($form_id, $int_pl_id, $x2, 2);
  }
  $my_form->print_text($form_id, "Vorname:", $x1, 4);
  // Änderungen an vorhandenen Spielern sind nachträglich nicht mehr erlaubt.
  // derartige Änderungen müssen über die SBK erledigt werden.
  if($load || $int_pl_id > -1)
    $my_form->print_text($form_id, $str_pl_name, $x2, 4);
  else
    $my_form->input_text($form_id, "pl_name", $str_pl_name, 25, 45, $x2, 4);
  $my_form->print_text($form_id, "Nachname:", $x1, 6);
  if($load || $int_pl_id > -1)
    $my_form->print_text($form_id, $str_pl_name2, $x2, 6);
  else
    $my_form->input_text($form_id, "pl_name2", $str_pl_name2, 25, 45, $x2, 6);
  $my_form->print_text($form_id, "Geburtsdatum (dd.mm.yyyy):", $x1, 8);
  if($load || $int_pl_id > -1)
    $my_form->print_text($form_id, $str_date, $x2, 8);
  else
    $my_form->input_text($form_id, "pl_date", $str_date, 25, 10, $x2, 8);
  $my_form->print_text($form_id, "Geschlecht:", $x1, 10);
  if($load) {
    if($bool_man) $str_man = "m&auml;nnlich";
    else $str_man = "weiblich";
    $my_form->print_text($form_id, $str_man, $x2, 10);
  }
  else
    $my_form->input_select($form_id, "pl_man", $arr_man[1], $arr_man[0], $bool_man, 1, 0, $x2, 10, 12);
  $my_form->print_text($form_id, "Stra&szlig;e:", $x1, 12);
  $my_form->input_text($form_id, "pl_street", $str_pl_street, 25, 100, $x2, 12);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 14);
  $my_form->input_text($form_id, "pl_nbr", $str_pl_nbr, 25, 10, $x2, 14);
  $my_form->print_text($form_id, "PLZ:", $x1, 16);
  $my_form->input_text($form_id, "pl_plz", $str_pl_plz, 25, 16, $x2, 16);
  $my_form->print_text($form_id, "Ort:", $x1, 18);
  $my_form->input_text($form_id, "pl_place", $str_pl_place, 25, 100, $x2, 18);
  $my_form->print_text($form_id, "Nationalit&auml;t:", $x1, 20);
  if($load) {
    $str_nation = "";
    for($i=0; $i<count($arr_nation[0]); $i++) {
      if($arr_nation[0][$i] == $int_pl_nation) {
        $str_nation = $arr_nation[1][$i];
        break;
      }
    }
    $my_form->print_text($form_id, $str_nation, $x2, 20);
  }
  else
    $my_form->input_select($form_id, "pl_nation", $arr_nation[1],
                            $arr_nation[0], $int_pl_nation, 1, 0, $x2, 20, 12);
    $my_form->input_button($form_id, "save", "Spieler speichern", $x1+1.2,
                           28, 13, 2);
}

echo "\n<br/>\n<center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>