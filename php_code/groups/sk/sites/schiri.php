<?php
$x1 = 21;
$x2 = 30;
$x3 = 40;

$arr_persons[0][0] = 0;
$arr_persons[0][1] = 0;
$arr_persons[1][0] = "Neuen Schiedsrichter anlegen";
$arr_persons[1][1] = "";
$arr_tmp = DBMapper::get_list_Schiedsrichter();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_persons[0][$i+2] = $arr_tmp[0][$i];
    $arr_persons[1][$i+2] = $arr_tmp[1][$i].", ".$arr_tmp[2][$i];
}

$arr_clubs[0][0] = 0;
$arr_clubs[1][0] = "keine Vereinzugehörigkeit";
$arr_tmp = DBMapper::get_list_Verein();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_clubs[0][$i+1] = $arr_tmp[0][$i];
    $arr_clubs[1][$i+1] = $arr_tmp[1][$i];
}

$arr_lizenztyp[0][0] = 0;
$arr_lizenztyp[1][0] = "Keine Lizenz";
$arr_tmp = DBMapper::get_list_Schiri_Lizenztype();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_lizenztyp[0][$i+1] = $arr_tmp[0][$i];
    $arr_lizenztyp[1][$i+1] = $arr_tmp[1][$i];
}

$form_id = $my_form->create_form("Schiri", "Schiri", -1, -1, 60, 41);
$my_form->input_list($form_id, "list1", $arr_persons[1], $arr_persons[0], 2, 4,
                     16, 20, 15, 2);
$my_form->input_hidden($form_id, "nav", "persons");

if($bool_show_data) {
  $my_form->input_hidden($form_id, "id", $int_id);
  $my_form->print_text($form_id, "Vorname:", $x1, 4);
  $my_form->input_text($form_id, "name", $str_name, 25, 45, $x2, 4);
  $my_form->print_text($form_id, "Nachname:", $x1, 6);
  $my_form->input_text($form_id, "name2", $str_name2, 25, 45, $x2, 6);
  $my_form->print_text($form_id, "Geburtsdatum:", $x1, 8);
  $my_form->input_text($form_id, "date", $str_date, 25, 10, $x2, 8);
  $my_form->print_text($form_id, "Lizenznummer:", $x1, 10);
  $my_form->input_text($form_id, "lizenznummer", $int_lizenznummer,
                       25, 100, $x2, 10);
  $my_form->print_text($form_id, "Lizenztyp:", $x1, 12);
  $my_form->input_select($form_id, "lizenztyp", $arr_lizenztyp[1],
                         $arr_lizenztyp[0], $int_id_lizenztyp, 1, 0,
                         $x2-0.15, 11.8, 16);

  $my_form->print_text($form_id, "Sta&szlig;e:", $x1, 14);
  $my_form->input_text($form_id, "street", $str_street, 25, 100, $x2, 14);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 16);
  $my_form->input_text($form_id, "nbr", $str_nbr, 25, 10, $x2, 16);
  $my_form->print_text($form_id, "PLZ:", $x1, 18);
  $my_form->input_text($form_id, "plz", $str_plz, 25, 16, $x2, 18);
  $my_form->print_text($form_id, "Ort:", $x1, 20);
  $my_form->input_text($form_id, "place", $str_place, 25, 100, $x2, 20);
  $my_form->print_text($form_id, "Telefon:", $x1, 22);
  $my_form->input_text($form_id, "tel", $str_tel, 25, 100, $x2, 22);
  $my_form->print_text($form_id, "Handynummer:", $x1, 24);
  $my_form->input_text($form_id, "handy", $str_handy, 25, 100, $x2, 24);
  $my_form->print_text($form_id, "Email Adresse:", $x1, 26);
  $my_form->input_text($form_id, "email", $str_email, 25, 100, $x2, 26);
  $my_form->print_text($form_id, "Verein:", $x1, 28);
  $my_form->input_select($form_id, "id_club", $arr_clubs[1], $arr_clubs[0], 
                         $int_id_club, 1, 0, $x2-0.15, 27.8, 16);
  $my_form->print_text($form_id, "Kommentar:", $x1, 30);
  $my_form->input_textarea($form_id, "kommentar", $str_kommentar, 30, 5, $x2, 30.2);

  if($int_id == -1)
    $my_form->input_button($form_id, "save", "Schiedsrichter anlegen", $x1+1.2,
                           40.6, 13, 2);
  else
    $my_form->input_button($form_id, "save", "Schiedsrichter speichern", $x1+1.2,
                           40.6, 13, 2);
}

echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
