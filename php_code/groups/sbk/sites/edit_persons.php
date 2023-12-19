<?php
$x1 = 19;
$x2 = 27;
$x3 = 38;

$arr_persons[0][0] = 0;
$arr_persons[0][1] = 0;
$arr_persons[1][0] = "Neue Person anlegen";
$arr_persons[1][1] = "";
$arr_tmp = DBMapper::get_list_Person();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_persons[0][$i+2] = $arr_tmp[0][$i];
    $arr_persons[1][$i+2] = $arr_tmp[1][$i];
}

$arr_clubs[0][0] = 0;
$arr_clubs[1][0] = "keine Vereinzugehörigkeit";
$arr_tmp = DBMapper::get_list_Verein();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_clubs[0][$i+1] = $arr_tmp[0][$i];
    $arr_clubs[1][$i+1] = $arr_tmp[1][$i];
}

$form_id = $my_form->create_form("persons", "Personen", -1, -1, 60, 37);
if($bool_show_data && $club_str != "")
  $my_form->print_text($form_id, "Personen des Vereins: ".$club_str, 3, 1);
$my_form->input_list($form_id, "list1", $arr_persons[1], $arr_persons[0], 2, 4,
                     14, 20, 13, 2);
$my_form->input_hidden($form_id, "nav", "persons");

if($bool_show_data) {
  $my_form->input_hidden($form_id, "p_id", $int_p_id);
  $my_form->print_text($form_id, "Vorname:", $x1, 4);
  $my_form->input_text($form_id, "p_name", $str_p_name, 25, 45, $x2, 4);
  $my_form->print_text($form_id, "Nachname:", $x1, 6);
  $my_form->input_text($form_id, "p_name2", $str_p_name2, 25, 45, $x2, 6);
  $my_form->print_text($form_id, "Geburtsdatum:", $x1, 8);
  $my_form->input_text($form_id, "p_date", $str_p_date, 25, 10, $x2, 8);
  $my_form->print_text($form_id, "Sta&szlig;e:", $x1, 10);
  $my_form->input_text($form_id, "p_street", $str_p_street, 25, 100, $x2, 10);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 12);
  $my_form->input_text($form_id, "p_nbr", $str_p_nbr, 25, 10, $x2, 12);
  $my_form->print_text($form_id, "PLZ:", $x1, 14);
  $my_form->input_text($form_id, "p_plz", $str_p_plz, 25, 16, $x2, 14);
  $my_form->print_text($form_id, "Ort:", $x1, 16);
  $my_form->input_text($form_id, "p_place", $str_p_place, 25, 100, $x2, 16);
  $my_form->print_text($form_id, "Telefon:", $x1, 18);
  $my_form->input_text($form_id, "p_tel", $str_p_tel, 25, 100, $x2, 18);
  $my_form->print_text($form_id, "Handynummer:", $x1, 20);
  $my_form->input_text($form_id, "p_handy", $str_p_handy, 25, 100, $x2, 20);
  $my_form->print_text($form_id, "Email Adresse:", $x1, 22);
  $my_form->input_text($form_id, "p_email", $str_p_email, 25, 100, $x2, 22);
  $my_form->print_text($form_id, "Verein:", $x1, 24);
  $my_form->print_text($form_id, $club_str, $x2, 24);
  $my_form->input_hidden($form_id, "p_id_club", $int_id_club);
  //$my_form->input_select($form_id, "p_id_club", $arr_clubs[1], $arr_clubs[0], 
  //		 $int_id_club, 1, 0, $x2-0.1, 23.9, 16);

  $my_form->input_button($form_id, "save", "Person speichern", $x1+1.2,
			 28, 13, 2);
}

echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
