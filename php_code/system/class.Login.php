<?php

require_once('class.DBM_Benutzer.php');

/**
 * Login
 *
 * Diese Klasse kuemmert sich um den Login.
 *
 * @package    SYSTEM
 * @subpackage LOGIN
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.5
 * @access public
 *
 */
final class Login {

  // Anzahl Versuche mit einem user_namen bis zum sperren des Accounts
  const INT_VERSUCHE_USERNAME = 5;

  // Anzahl Versuche mit einem user_namen bis zum sperren des Accounts
  const INT_VERSUCHE_IP = 10;

  // Zeit für den der Login gesperrt wird in Sekunden
  const INT_SPERRZEIT = 300; // 5 Minuten

  // Zeit für den der Login gültig ist in Sekunden
  const INT_LOGINZEIT = 3600; // 60 Minuten //1800; // 30 Minuten

  // Mit diesem String wird der Timestamp verbunden bevor die MD5 zur Kontrolle gebildet wird
  /**
   * Enthält eine Zeichenkette, die zum Verschlüssel genutzt wird.
   * @access private
   * @var string
   */
  private static $TIME_PW = "twbve";



  public static function isloggedin() {
  //return true;
    // Schauen ob das Login Array gesetzt ist. Wenn ja ne lokale Kopie erstellen
    if (isset($_SESSION['arr_login'])) {
      $arr_login = $_SESSION['arr_login'];
    } else {
      return FALSE;
    }

    // Auf abgelaufene Zeit Prüfen
    if (isset($arr_login['timestamp'])
         && isset($arr_login['md5_time'])
         && md5($arr_login['timestamp'] . self::$TIME_PW) == $arr_login['md5_time']
         && (time() - self::INT_LOGINZEIT) <= $arr_login['timestamp'] ) {
      $timestamp = time();
      $_SESSION['arr_login']['timestamp'] = $timestamp;
      $_SESSION['arr_login']['md5_time']  = md5($timestamp . self::$TIME_PW);
    } else {
      // echo "Zeit ist abgelaufen";
      return FALSE;
    }

    // Prüfen ob alles okay ist
    if (isset($arr_login['login'])
        && $arr_login['login'] ) {
//         && isset($arr_login['ip'])
//         && $arr_login['ip'] == $_SERVER['REMOTE_ADDR'] ) {
      return TRUE;
    } else {
      // echo "IP passt nicht";
      return FALSE;
    }
  }


  public static function show_status() {

    if (Login::isloggedin()) {
      $user_name = $_SESSION['arr_login']['user_name'];
      $str_saison = $_SESSION['str_saison'];

      $arrHTML[] = "<div id=\"status\">";
      $arrHTML[] = "  Benutzer: $user_name<br />";
      $arrHTML[] = "  Saison: $str_saison";
      $arrHTML[] = "  <form id=\"login\" action=\"index.php?action=do_logout\" method=\"post\" name=\"Form_Login\">";
      $arrHTML[] = "    <input class=\"generic-button\" type=\"submit\" name=\"submit\" value=\"abmelden\" />";
      $arrHTML[] = "  </form>";
      $arrHTML[] = "</div> <!-- status -->";
    } else {
      $ssl = SSLURL . "index.php";

      $arrHTML[] = "<div id=\"status\">";
      $arrHTML[] = "  <form id=\"login\" action=\"$ssl?action=do_login\" method=\"post\" name=\"Form_Login\">";
      $arrHTML[] = "    Benutzer: <input type=\"text\" name=\"user_name\" size=\"15\" /><br />";
      $arrHTML[] = "    Passwort: <input type=\"password\" name=\"passwort\" size=\"15\" /><br />";
      $arrHTML[] = "    <input class=\"generic-button\" type=\"submit\" name=\"submit\" value=\"anmelden\" />";
      $arrHTML[] = "  </form>";
      $arrHTML[] = "</div> <!-- status -->";
    }
    return $arrHTML;
  }


// DB_Connection überarbeiten
  public static function do_login() {

    // Prüfen ob "Benutzer" und "Passwort" gesetzt sind
    if (isset($_POST['user_name']) && isset($_POST['passwort']) && !self::isloggedin()) {
      $str_user_name = $_POST['user_name'];
      $str_passwort = md5($_POST['passwort']);
      unset($_POST['user_name']);
      unset($_POST['passwort']);
      if (!$sql_result = DBM_Benutzer::login($str_user_name)) {
        // Benutzer nicht gefunden
        self::logWrongLogin($str_user_name);
        return FALSE;
      }
      // Zeile für Zeile das Paar aus "user_name" und "passwort" mit den Daten aus der DB vergleichen
      while ($arr_row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
        if ($arr_row['user_name'] == $str_user_name && $arr_row['passwort'] == $str_passwort) {
          self::setLoginData($arr_row['id_benutzer'], $arr_row['user_name']);
          self::logRightLogin($str_user_name);
          mysql_free_result($sql_result);
          unset($str_user_name);
          unset($str_passwort);
          DBM_Benutzer::unsetPW2($arr_row['id_benutzer']);
          return TRUE;
        }
      }
      // Zweit passwort prüfen
      if (!$sql_result = DBM_Benutzer::login2($str_user_name)) {
        // Benutzer nicht gefunden
        self::logWrongLogin($str_user_name);
        return FALSE;
      }
      // Zeile für Zeile das Paar aus "user_name" und "passwort" mit den Daten aus der DB vergleichen
      while ($arr_row = mysql_fetch_array($sql_result, MYSQL_ASSOC)) {
        if ($arr_row['user_name'] == $str_user_name && $arr_row['passwort2'] == $str_passwort) {
          $_SESSION['id_benutzer'] = $arr_row['id_benutzer'];
          // aufzurufenden seite in session speichern
          $_SESSION['seite'] = "showChangePW";
          self::logRightLogin($str_user_name);
          mysql_free_result($sql_result);
          unset($str_user_name);
          unset($str_passwort);
          return TRUE;
        }
      }
      unset($str_user_name);
      unset($str_passwort);
      return FALSE;
    } else {
      unset($_POST['user_name']);
      unset($_POST['passwort']);
      return FALSE;
    }
  }


  public static function do_logout() {
    // Alle Variablen der Session löschen
    $_SESSION = array();

    // Das Cookie löschen wenn vorhanden
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
    }

    // Die Session selbst löschen
    return session_destroy();
  }


  public static function isInGroup($str_gruppenName, $id_benutzer = NULL) {
    if ($id_benutzer == NULL) {
      $id_benutzer = $_SESSION['arr_login']['id_benutzer'];
    }
    if (self::isloggedin()) {
      if ($arr_gruppen = DBM_Benutzer::get_gruppen($id_benutzer)) {
        foreach($arr_gruppen as $str_gruppe) {
          if ($str_gruppe == $str_gruppenName) {
            return TRUE;
          }
        }
        return FALSE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }


  public static function get_verein() {
    if (self::isloggedin()) {
      if ($int_id_verein = DBM_Benutzer::get_verein($_SESSION['arr_login']['id_benutzer'])) {
        return $int_id_verein;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }


  public static function get_list_mannschaften() {
    if (self::isloggedin()) {
      if ($arr_teams = DBM_Benutzer::get_list_mannschaften($_SESSION['arr_login']['id_benutzer'])) {
        return $arr_teams;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }


  public static function check_passwort($str_passwort) {
    $old_pw = DBM_Benutzer::get_passwort_by_user_id($_SESSION['arr_login']['id_benutzer']);
    if (md5($str_passwort) == $old_pw) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  /**
   * Login::logWrongLogin()
   *
   * @param string $str_user_name
   * @return
   */
  public static function logWrongLogin($str_user_name) {
    //throw new Exception('Not implemented.');
  }


  /**
   * Login::logRightLogin()
   *
   * @param string $str_user_name
   * @return
   */
  public static function logRightLogin($str_user_name) {
    //throw new Exception('Not implemented.');
  }


  public static function showLogin() {
    $ssl = SSLURL . "index.php";
echo<<<END
<br />
Bitte geben Sie Ihren Benutzernamen und das Passwort an um sich anzumleden.<br />
<br />
<form id="login" action="$ssl?action=do_login" method="post" name="Form_Login">
  Benutzer: <input type="text" name="user_name" size="20" /><br />
  Passwort: <input type="password" name="passwort" size="20" /><br />
  <input class="generic-button" type="submit" name="submit" value="anmelden" />
</form>
<br />
<br />
Sollten Sie Ihr Passwort vergessen haben, so können Sie sich hier ein neues Passwort
 an Ihre hinterlegte eMail-Adresse schicken lassen.<br />
<br />
<form id="login" action="$ssl?action=send_pw" method="post" name="Form_Login">
  Benutzer: <input type="text" name="user_name" size="20" /><br />
  <input class="generic-button" style="position: relative; left: 44px;" type="submit" name="submit" value="neues Passwort schicken" />
</form>

END;
  }


  public static function showChangePW() {
    $Benutzer = new Benutzer();
    $Benutzer = DBM_Benutzer::read_Benutzer($_SESSION['id_benutzer']);
    if ($Benutzer->get_bool_datenschutz()) {
      $ssl = SSLURL . "index.php";
echo<<<END
<br />
Bitte geben Sie ein neues Passwort ein.<br />
<br />
<form id="login" action="$ssl?action=set_pw" method="post" name="Form_Login">
  Neues Passwort: <input type="passwort" name="passwort" size="20" /><br />
  <input class="generic-button" type="submit" name="submit" value="neues Passwort speichern" />
</form>
<br />
END;
    } else {
      $_SESSION['PW2'] = TRUE;
      $_SESSION['seite'] = "showDatenschutz";
      self::showDatenschutz();
    }
  }


  public static function sendNewPW() {
    if (isset($_POST['user_name'])) {
      $user_name = $_POST['user_name'];
    } else {
      return FALSE;
    }
    // prüfen ob es den usernamen gibt
    $Benutzer = DBM_Benutzer::read_Benutzer_by_user_name($user_name);
    if ($Benutzer !== FALSE) {
      // PW generieren
      $arr_letters = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                       "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
                       "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
                       "u", "v", "w", "x", "y", "z", "A", "B", "C", "D",
                       "E", "F", "G", "H", "I", "J", "K", "L", "M", "N",
                       "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
                       "Y", "Z");
      $str_new_pw = "";
      for($i=0; $i<8; $i++) {
        $str_new_pw .= $arr_letters[rand(0,61)];
      }
      // PW in die DB schreiben
      DBM_Benutzer::write_newPW($user_name, $str_new_pw);

      // eMail senden
      Email::send_newPW($Benutzer, $str_new_pw);
      return TRUE;
    } else {
      return FALSE;
    }
  }


  /**
   * Login::setLoginData()
   *
   * @param int $id_benutzer
   * @param string $user_name
   * @return
   */
  public static function setLoginData($id_benutzer, $user_name) {
    $Benutzer = new Benutzer();
    $Benutzer = DBM_Benutzer::read_Benutzer($id_benutzer);
    if ($Benutzer->get_bool_datenschutz()) {
      $timestamp = time();
      $_SESSION['arr_login'] = array('login'        => true,
                                     'id_benutzer'  => $id_benutzer,
                                     'user_name'    => $user_name,
                                     'ip'           => $_SERVER['REMOTE_ADDR'],
                                     'timestamp'    => $timestamp,
                                     'md5_time'     => md5($timestamp . self::$TIME_PW));
      if (isset($_SESSION['id_benutzer'])) {
        unset($_SESSION['id_benutzer']);
      }
    } else {
      $_SESSION['seite'] = "showDatenschutz";
      $_SESSION['id_benutzer'] = $id_benutzer;
    }
  }


  /**
   * Login::setNewPW()
   *
   * @return
   */
  public static function setNewPW($passwort) {
    $id_benutzer = $_SESSION['id_benutzer'];
    $Benutzer = new Benutzer();
    $Benutzer = DBM_Benutzer::read_Benutzer($id_benutzer);
    DBM_Benutzer::setNewPW($id_benutzer, $passwort);
    DBM_Benutzer::unsetPW2($id_benutzer);
    $Benutzer = DBM_Benutzer::read_Benutzer($id_benutzer);
    self::setLoginData($id_benutzer, $Benutzer->get_str_user_name());
    unset($id_benutzer);
  }


  /**
   * Login::showPWsended()
   *
   * @return
   */
  public static function showPWsended() {
echo<<<END
<br />
<br />
Ihr neues Passwort wurde Ihnen zugesendet.<br />
<br />
END;
  }


  /**
   * Login::setDatenschutz()
   *
   * @return
   */
  public static function setDatenschutz() {
    if (isset($_POST['datenschutz']) && $_POST['datenschutz']) {
      DBM_Benutzer::setDatenschutz($_SESSION['id_benutzer']);
      if (isset($_SESSION['PW2']) && $_SESSION['PW2']) {
        $_SESSION['seite'] = "showChangePW";
        unset($_SESSION['PW2']);
      } else {
        $Benutzer = new Benutzer();
        $Benutzer = DBM_Benutzer::read_Benutzer($_SESSION['id_benutzer']);
        self::setLoginData($_SESSION['id_benutzer'], $Benutzer->get_str_user_name());
      }
    } else {
      $_SESSION['seite'] = "showDatenschutz2";
    }
  }


  /**
   * Login::showDatenschutz()
   *
   * @return
   */
  public static function showDatenschutz() {
    include('datenschutz.php');
  }


} //class Login
?>