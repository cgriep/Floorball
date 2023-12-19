<?php
$x1 = 3;
$x2 = 15;

$form_id = $my_form->create_form("settings", "Einstellungen", -1, -1, 60, 30, 'enctype="multipart/form-data"');
$my_form->print_text($form_id, "Einstellungen", 3, 1);
$my_form->print_text($form_id, "Altes Passwort:", $x1, 5);
$my_form->input_password($form_id, "s_pass1", $str_s_pass1, 25, 255, $x2, 5);
$my_form->print_text($form_id, "Neues Passwort:", $x1, 7);
$my_form->input_password($form_id, "s_pass2", $str_s_pass2, 25, 255, $x2, 7);
$my_form->print_text($form_id, "Passwort wiederholen:", $x1, 9);
$my_form->input_password($form_id, "s_pass3", $str_s_pass3, 25, 255, $x2, 9);
$my_form->input_button($form_id, "save", "Daten speichern", $x2+1, 11, 11, 2);
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>