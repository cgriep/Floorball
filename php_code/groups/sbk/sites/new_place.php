<?php
$x1 = 2;
$x2 = 18;
$x3 = 30;
$x4 = 38;

$arr_clubs = DBMapper::get_list_Verein();
$arr_clubs[0][] = 0;
$arr_clubs[1][] = "leer";

$form_id = $my_form->create_form("places", "Spieltage", -1, -1, 60, 36);
$my_form->print_text($form_id, "Spielort anlegen", 3, 1);

$my_form->input_hidden($form_id, "nav", "new_place");
$my_form->input_hidden($form_id, "id", $int_id);
$my_form->input_hidden($form_id, "date", $str_date);
$my_form->input_hidden($form_id, "id_league", $int_id_league);
$my_form->input_hidden($form_id, "id_place", $int_id_place);
$my_form->input_hidden($form_id, "spieltag_nr", $str_spieltag_nr);

$my_form->print_text($form_id, "Name:", $x1, 4);
$my_form->input_text($form_id, "pl_name", "", 25, 255, $x2, 4);
$my_form->print_text($form_id, "Verein:", $x1, 6);
$my_form->input_select($form_id, "id_pl_club", $arr_clubs[1], $arr_clubs[0],
                       0, 1, 0, $x2-0.1, 5.8, 12);
$my_form->print_text($form_id, "Stra&szlig;e:", $x1, 8);
$my_form->input_text($form_id, "pl_street", "", 25, 255, $x2, 8);
$my_form->print_text($form_id, "Hausnummer:", $x1, 10);
$my_form->input_text($form_id, "pl_nbr", "", 5, 55, $x2, 10);
$my_form->print_text($form_id, "PLZ:", $x1, 12);
$my_form->input_text($form_id, "pl_plz", "", 5, 55, $x2, 12);
$my_form->print_text($form_id, "Ort:", $x1, 14);
$my_form->input_text($form_id, "pl_place", "", 25, 255, $x2, 14);

$my_form->print_text($form_id, "Zuschauerkapazität:", $x1, 16);
$my_form->input_text($form_id, "pl_zuschauer", "", 25, 255, $x2, 16);

$my_form->print_text($form_id, "Anfahrtsbeschreibung PKW:", $x1, 18);
$my_form->input_textarea($form_id, "pl_anfahrt_pkw", "", 30, 5, $x2, 18);

$my_form->print_text($form_id, "Anfahrtsbeschreibung ÖPNV:", $x1, 25.3);
$my_form->input_textarea($form_id, "pl_anfahrt_oepnv", "", 30, 5, $x2, 25.3);

$my_form->input_button($form_id, "back", "Abbrechen", $x2+1, 35.4, 10, 2);
$my_form->input_button($form_id, "save", "Spielort anlegen", $x2+12.1, 35.4,
                       10, 2);

echo "\n<br/><center>\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>