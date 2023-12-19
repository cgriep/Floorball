<?php

/**
* Email
*
* @package    SYSTEM
* @subpackage EMAIL
*
* @author Michael Rohn
* @copyright Copyright (c) 2005
* @version $Id$ 0.2
* @access public
*
*
*/
class Email {

//  private $dbLink = FALSE;   // Offene Datenbankverbindung


  /**
   * Email::send_willkommen()
   *
   * Sendet eine Email mit Willkommenstext an die SBK-EMail. Später dann an den Nutzer
   *
   * @param int $id_benutzer
   * @param str $passwort
   * @param str $email_adresse
   */
  public function send_willkommen($id_benutzer, $passwort, $email_adresse) {
    $Benutzer = DBM_Benutzer::read_Benutzer($id_benutzer);
    $Verband  = DBMapper::get_Verband_by_pfad(VERBAND);
    $username = $Benutzer->get_str_user_name();

    $email_betreff = "Willkommen beim SaisonManager Unihockey";
    $email_message = "Fuer Sie wurde ein Account beim SaisonManager Unihockey angelegt.\n\n"
                     . "Username: $username \n"
                     . "Passwort: $passwort \n\n"
                     . "Sie koennen sich mit den Angaben nun unter: \n\n"
                     . "http://". $Verband->get_str_subdomain() . ".sm-u.de \n\n"
                     . "anmelden.\n\n"
                     . "Nach dem ersten Anmelden muessen Sie die Datenschutzbedingungen "
                     . "lesen und ein neues Passwort setzen.\n";
    if (!preg_match( '/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+/' ,$email_adresse)) {
      echo "<br>FEHLER: Mail konnte nicht versendet werden, da die Adresse ungültig ist!";
    } else {
      mail($email_adresse,
           $email_betreff,
           $email_message,
           "From: system@sm-u.de\r\n" . "Reply-To: system@smu-u.de\r\n");
//      or die("FEHLER: Mail konnte wegen eines unbekannten Fehlers nicht versendet werden");
    }
  }

  /**
   * Email::send_passwort()
   *
   * Sendet eine Email mit dem neuen Passwort an die SBK-EMail. Später dann an den Nutzer
   *
   * @param int $id_benutzer
   * @param str $passwort
   */
  public function send_newPW($Benutzer, $passwort) {
    $username = $Benutzer->get_str_user_name();
    $Verband  = DBMapper::get_Verband_by_pfad(VERBAND);
    $email_adresse = $Benutzer->get_str_email();
    $email_betreff = "Ihr Passwort wurde geaendert";
    $email_message = "Fuer Ihren Zugang zum SaisonManager Unihockey wurde ein neues "
                     . "Passwort gesetzt. Die Logindaten lauten:\n\n"
                     . "Username: $username \n"
                     . "Passwort: $passwort \n\n"
                     . "Sie koennen sich innerhalb der naechsten 24 Stunden mit "
                     . "diesen Daten anmelden. Nach dieser Zeit und bis Sie sich mit den "
                     . "neuem Passwort anmelden, koennen sie sich auch weiterhin mit "
                     . "dem alten Passwort anmelden.\n\n"
                     . "http://". $Verband->get_str_subdomain() .".sm-u.de \n\n"
                     . "anmelden.";
    if(!preg_match( '/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+/' , $email_adresse)){
      echo "<br>FEHLER: Mail konnte nicht versendet werden, da die Adresse ungültig ist!";
    } else {
      mail($email_adresse,
           $email_betreff,
           $email_message,
           "From: system@sm-u.de\r\n" . "Reply-To: system@sm-u.de\r\n");
      //or die("FEHLER: Mail konnte wegen eines unbekannten Fehlers nicht versendet werden");
    }
  }


} // class

?>