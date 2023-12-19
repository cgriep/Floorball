<?php
$x1 = 2;
$x2 = 10;
$x3 = 30;
$x4 = 38;

$form_id = $my_form->create_form("persons", "VereinsManager", -1, -1, 60, 20);
$my_form->print_text($form_id, "Person anlegen (Verein: ".$_SESSION['temp']['t_str_club'].")", 3, 1);

$my_form->input_hidden($form_id, "nav", "persons");
$my_form->print_text($form_id, "Name:", $x1, 4);
$my_form->input_text($form_id, "p_name", "", 25, 255, $x2, 4);
$my_form->print_text($form_id, "Nachname:", $x3, 4);
$my_form->input_text($form_id, "p_n_name", "", 25, 255, $x4, 4);
$my_form->print_text($form_id, "Stra&szlig;e:", $x1, 6);
$my_form->input_text($form_id, "p_street", "", 25, 255, $x2, 6);
$my_form->print_text($form_id, "Telefon:", $x3, 6);
$my_form->input_text($form_id, "p_tel", "", 25, 255, $x4, 6);
$my_form->print_text($form_id, "Hausnummer:", $x1, 8);
$my_form->input_text($form_id, "p_nbr", "", 5, 55, $x2, 8);
$my_form->print_text($form_id, "Handy:", $x3, 8);
$my_form->input_text($form_id, "p_handy", "", 25, 255, $x4, 8);
$my_form->print_text($form_id, "PLZ:", $x1, 10);
$my_form->input_text($form_id, "p_plz", "", 5, 55, $x2, 10);
$my_form->print_text($form_id, "E-Mail:", $x3, 10);
$my_form->input_text($form_id, "p_mail", "", 25, 255, $x4, 10);
$my_form->print_text($form_id, "Ort:", $x1, 12);
$my_form->input_text($form_id, "p_place", "", 25, 255, $x2, 12);
$my_form->print_text($form_id, "Geburtsdatum:", $x3, 12);
$my_form->input_text($form_id, "p_date", "", 25, 255, $x4, 12);
$my_form->input_button($form_id, "save", "Person speichern", $x1, 16, 10, 2);
$my_form->input_button($form_id, "back", "Daten verwerfen", $x2+3, 16, 10, 2);

echo "\n<br/><center>\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>