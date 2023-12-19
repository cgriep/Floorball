<?php
/**
 * Logik für ...
 *
 * @package    VM
 * @subpackage Handles
 */
$bool_show_data = 0;

// Benutzer laden
$Benutzer = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$str_s_pass1 = "";
$str_s_pass2 = "";
$str_s_pass3 = "";
$s_error = 0;

if(isset($_POST['save'])) {
  if($_POST['s_pass2'] != "") {
    if(!Login::check_passwort($_POST['s_pass1'])) {
      $s_error = 1;
      $my_message->add_error("Das alte Passwort wurde falsch eingegeben.");
    }
    else if($_POST['s_pass2'] != $_POST['s_pass3']) {
      $s_error = 2;
      $my_message->add_error("Die beiden Eingaben für das neue Passwort sind nicht identisch.");
    }
    else if(count($_POST['s_pass2']) < 3)
      $my_message->add_error("Weniger als drei Zeichen können nicht für ein neues Passwort gesetzt werden.");
  }
  else {
    $s_error = 3;
      $my_message->add_error("Das alte Passwort wurde nicht angegeben.");
  }
  if(!$s_error) {
    DBM_Benutzer::update_passwort($_POST['s_pass2'],
				       $_SESSION['arr_login']['id_benutzer']);
    $my_message->add_message("Das Passwort wurde erfolgreich geändert.");
  }
  unset($_POST['s_pass1']);
  unset($_POST['s_pass2']);
  unset($_POST['s_pass3']);
}


?>
