<?php
$x1 = 3;
$x2 = 12;
$x3 = 23;

$arr_man[0][0] = -1;
$arr_man[0][1] = 1;
$arr_man[0][2] = 0;
$arr_man[1][0] = "none";
$arr_man[1][1] = "m&auml;nnlich";
$arr_man[1][2] = "weiblich";
$arr_nation = DBM_Spieler::get_list_nation();

$form_id = $my_form->create_form("teams", "VereinsManager", -1, -1, 60, 28);
$my_form->input_hidden($form_id, "nav", "player");

$my_form->input_hidden($form_id, "pl_id", $int_pl_id);
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
$my_form->input_text($form_id, "pl_street", $str_pl_street, 25, 100,
		     $x2, 12);
$my_form->print_text($form_id, "Hausnummer:", $x1, 14);
$my_form->input_text($form_id, "pl_nbr", $str_pl_nbr, 25, 10, $x2, 14);
$my_form->print_text($form_id, "PLZ:", $x1, 16);
$my_form->input_text($form_id, "pl_plz", $str_pl_plz, 25, 16, $x2, 16);
$my_form->print_text($form_id, "Ort:", $x1, 18);
$my_form->input_text($form_id, "pl_place", $str_pl_place, 25, 100, $x2, 18);
$my_form->print_text($form_id, "Nationalität:", $x1, 20);
$my_form->input_select($form_id, "pl_nation", $arr_nation[1],
		       $arr_nation[0], $int_pl_nation, 1, 0, $x2, 20, 12);
$my_form->input_button($form_id, "save", "Daten speichern", $x1+1, 26,
		       13, 2);

$my_form->input_button($form_id, "back", "Zur&uuml;ck zum Verein", $x1+15,
		       26, 13, 2);
echo "\n<br/><center>\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>