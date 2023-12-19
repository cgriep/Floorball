<?php
/**
 * Erstellung für ...
 *
 * @package    VM
 * @subpackage Sites
 */
$x1 = 3;
$x2 = 14.7;

$form_id = $my_form->create_form("accounts", "Accounts", -1, -1, 60, 35+1.63*count($arr_teams[0]), 'enctype="multipart/form-data"');
$my_form->print_text($form_id, "Accounts", 3, 1);
if(!$bool_show_data) {
  $my_form->input_list($form_id, "list1", $arr_accounts[1], $arr_accounts[0], 2, 3, 20, 20, 19, 2);
}
else {
  // Makierungen für Pflichtfelder
  $my_form->print_text($form_id, "*", $x2+13, 5);
  $my_form->print_text($form_id, "*", $x2+13, 7);
  $my_form->print_text($form_id, "*", $x2+13, 9);
  $my_form->print_text($form_id, "*", $x2+13, 11);
  $my_form->print_text($form_id, "*", $x2+13, 13);
  $my_form->print_text($form_id, "*", $x2+13, 15);
  $my_form->print_text($form_id, "*", $x2+13, 17);
  $my_form->print_text($form_id, "*", $x2+13, 19);
  $my_form->print_text($form_id, "*", $x2+13, 21);
  $my_form->print_text($form_id, "*", $x2+13, 23);
  $my_form->print_text($form_id, "* Pflichtfelder", 36, 23);

//  $arr_types[0] = array("2", "4", "5");
//  $arr_types[1] = array("SBK Account", "Vereinsmanager", "Teammanager");

  $my_form->input_hidden($form_id, "id", $int_id);
  $my_form->input_hidden($form_id, "id_club", $int_id_club);
  $my_form->input_hidden($form_id, "nav", "accounts");

  $my_form->print_text($form_id, "Account Type:", $x1, 3);
  $my_form->print_text($form_id, "Teammanager", $x2, 3);

  $my_form->print_text($form_id, "User-Name:", $x1, 5);
  $my_form->input_text($form_id, "a_nick", $str_a_nick, 25, 255, $x2, 5);
  $my_form->print_text($form_id, "Vorname:", $x1, 7);
  $my_form->input_text($form_id, "a_surname", $str_a_surname, 25, 255, $x2, 7);
  $my_form->print_text($form_id, "Nachname:", $x1, 9);
  $my_form->input_text($form_id, "a_lastname", $str_a_lastname, 25, 255,
                     $x2, 9);
  $my_form->print_text($form_id, "Geburtsdatum:", $x1, 11);
  $my_form->input_text($form_id, "a_date", $str_a_date, 25, 255, $x2, 11);
  $my_form->print_text($form_id, "Straße:", $x1, 13);
  $my_form->input_text($form_id, "a_street", $str_a_street, 25, 255, $x2, 13);
  $my_form->print_text($form_id, "Hausnummer:", $x1, 15);
  $my_form->input_text($form_id, "a_nbr", $str_a_nbr, 25, 255, $x2, 15);
  $my_form->print_text($form_id, "PLZ:", $x1, 17);
  $my_form->input_text($form_id, "a_plz", $str_a_plz, 25, 255, $x2, 17);
  $my_form->print_text($form_id, "Ort:", $x1, 19);
  $my_form->input_text($form_id, "a_place", $str_a_place, 25, 255, $x2, 19);

  $my_form->print_text($form_id, "E-Mail:", $x1, 21);
  $my_form->input_text($form_id, "a_email", $str_a_email, 25, 255, $x2, 21);
  $my_form->print_text($form_id, "Beschreibung:", $x1, 23);
  $my_form->input_text($form_id, "a_text", $str_a_text, 25, 255, $x2, 23);



  $my_form->print_text($form_id, "Club:", 36, 3);
  if($int_id_club) {
    $str_club = "";
    for($i=0; $i<count($arr_clubs[1]); $i++) {
      if($arr_clubs[0][$i] == $int_id_club) {
        $str_club = $arr_clubs[1][$i];
        break;
      }
    }
    $my_form->print_text($form_id, $str_club, 40, 3);
  }

  if($int_id_club) {
    for($i=0; $i<count($arr_teams[0]); $i++) {
      $arr_table[0][] = $arr_teams[1][$i]." (".DBMapper::get_kuerzel_Liga_by_Mannschaft($arr_teams[0][$i]).")";
      $arr_ids[] = $arr_teams[0][$i];
      $arr_v[] = "";
      $found = false;
      for($k=0; $k<count($arr_teams_user[0]); $k++) {
        if($arr_teams[0][$i] == $arr_teams_user[0][$k]) {
          $found = true;
          break;
        }
      }
      if($found) {
        $arr_check[] = 0;
      }
      else {
        $arr_check[] = 1;
      }
    }
    $arr_titles = array("Mannschaft", "ja", "nein");
    if(count($arr_teams[0]) > 0) {
      $my_form->print_text($form_id, "Rechte für Mannschaften:", 3, 27);
      $my_form->input_check_table($form_id, "", $arr_table, $arr_ids,
                                  $arr_titles,
                                  $arr_check, $arr_v, 2, 3, 29);
    }
  }

  $my_form->input_button($form_id, "save", "Account speichern", 3, 34+1.63*count($arr_teams[0]), 13, 2);
  $my_form->input_button($form_id, "cancel", "abbrechen", 19, 34+1.63*count($arr_teams[0]), 13, 2);
}
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>