<?php

require_once('class.DBM_root.php');
require_once('class.Benutzer.php');

/**
 * DBM_Benutzer
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * von Benutzer-Daten in bzw. aus der Datenbank
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_Benutzer extends DBM_root {


  public static function login($str_user_name) {
    $sql = "SELECT id_benutzer, user_name, passwort
            FROM ". TABLE_BENUTZER ."
            WHERE aktiv = 1
            AND user_name = '$str_user_name'";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      return $result;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function login2($str_user_name) {
    $sql = "SELECT id_benutzer, user_name, passwort2
            FROM ". TABLE_BENUTZER ."
            WHERE aktiv = 1
            AND user_name = '$str_user_name'
            AND passwort2_time > DATE_SUB(now(), INTERVAL 24 HOUR)";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      return $result;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Benutzer(Benutzer $Benutzer) {
    $array = array("user_name"    => "str_user_name",
                   "vorname"      => "str_vorname",
                   "nachname"     => "str_nachname",
                   "geb_datum"    => "date_geb_datum",
                   "beschreibung" => "str_beschreibung",
                   "email"        => "str_email",
                   "id_verein"    => "int_id_verein",
                   "strasse"      => "str_strasse",
                   "hausnummer"   => "str_hausnummer",
                   "plz"          => "str_plz",
                   "ort"          => "str_ort",
                   "passwort"     => "str_passwort",
                   "passwort2"    => "str_passwort2",
                   "passwort2_time" => "time_passwort2",
                   "aktiv"        => "bool_aktiv");
    $sql_set = self::build_set($array, $Benutzer);
    $sql = "INSERT INTO ". TABLE_BENUTZER ." ".$sql_set;
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      $Benutzer->set_int_id($last_insert_id);

      // Gruppe(n) setzen
      self::write_benutzer_gruppe($Benutzer);

      // Team(s) setzen
      self::write_benutzer_teams($Benutzer);

      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  private static function write_benutzer_gruppe($Benutzer) {

    if (is_array($Benutzer->get_arr_gruppe())) {

      // Alte Gruppen-Zugehörigkeit löschen
      $int_id_benutzer = $Benutzer->get_int_id();
      $sql = "DELETE FROM " . TABLE_BENUTZER_GRUPPE ."
              WHERE id_benutzer = $int_id_benutzer";
      $db = self::db_connect(DB_USERS_NAME, "rw");
      $db->query($sql);

      // Neue Gruppen-Zugehörigkeit eintragen
      foreach($Benutzer->get_arr_gruppe() as $int_id_gruppe){
        $sql = "INSERT INTO " . TABLE_BENUTZER_GRUPPE ."
                SET id_benutzer = $int_id_benutzer ,
                    id_gruppe   = $int_id_gruppe";
        $db->query($sql);
      }
    }
    return FALSE;
  }


  private static function write_benutzer_teams($Benutzer) {

    if (is_array($Benutzer->get_arr_teams())) {
      $int_id_benutzer = $Benutzer->get_int_id();
      // Alte Mannschafts-Zugehörigkeit löschen
      $sql = "DELETE FROM " . TABLE_BENUTZER_TEAMS ."
              WHERE id_benutzer = $int_id_benutzer";
      $db = self::db_connect(DB_USERS_NAME, "rw");
      $db->query($sql);

      // Neue Mannschafts-Zugehörigkeit eintragen
      foreach($Benutzer->get_arr_teams() as $int_id_team) {
        $sql = "INSERT INTO " . TABLE_BENUTZER_TEAMS ."
                SET id_benutzer = $int_id_benutzer ,
                    id_teams    = $int_id_team";
        $db->query($sql);
      }
    }
    return FALSE;
  }





  public static function update_Benutzer(Benutzer $Benutzer) {
    $array = array("vorname"      => "str_vorname",
                   "nachname"     => "str_nachname",
                   "geb_datum"    => "date_geb_datum",
                   "beschreibung" => "str_beschreibung",
                   "email"        => "str_email",
                   "id_verein"    => "int_id_verein",
                   "strasse"      => "str_strasse",
                   "hausnummer"   => "str_hausnummer",
                   "plz"          => "str_plz",
                   "ort"          => "str_ort");
    $sql_set = self::build_set($array, $Benutzer);
    $sql = "UPDATE  ". TABLE_BENUTZER . " ".
            $sql_set ."
            WHERE id_benutzer = '". $Benutzer->get_int_id() ."'"
        . " AND user_name = '". $Benutzer->get_str_user_name() ."'";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      // Gruppe(n) setzen
      self::write_benutzer_gruppe($Benutzer);

      // Team(s) setzen
      self::write_benutzer_teams($Benutzer);

      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Benutzer_mit_pw(Benutzer $Benutzer) {
    $array = array("vorname"      => "str_vorname",
                   "nachname"     => "str_nachname",
                   "geb_datum"    => "date_geb_datum",
                   "beschreibung" => "str_beschreibung",
                   "email"        => "str_email",
                   "id_verein"    => "int_id_verein",
                   "strasse"      => "str_strasse",
                   "hausnummer"   => "str_hausnummer",
                   "plz"          => "str_plz",
                   "ort"          => "str_ort",
                   "passwort"     => "str_passwort");
    $sql_set = self::build_set($array, $Benutzer);
    $sql = "UPDATE  ". TABLE_BENUTZER . " ".
            $sql_set ."
            WHERE id_benutzer = '". $Benutzer->get_int_id() ."'";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      // Gruppe(n) setzen
      self::write_benutzer_gruppe($Benutzer);

      // Team(s) setzen
      self::write_benutzer_teams($Benutzer);

      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Benutzer($int_id) {
    $sql = "SELECT id_benutzer,
                   user_name,
                   vorname,
                   nachname,
                   geb_datum,
                   beschreibung,
                   email,
                   id_verein,
                   strasse,
                   hausnummer,
                   plz,
                   ort,
                   datenschutz,
                   aktiv
            FROM ". TABLE_BENUTZER ."
            WHERE id_benutzer = '$int_id'";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      $Benutzer = new Benutzer();
      $Benutzer->set_int_id(mysql_result($result, 0, "id_benutzer"));
      $Benutzer->set_str_user_name(mysql_result($result, 0, "user_name"));
      $Benutzer->set_str_vorname(mysql_result($result, 0, "vorname"));
      $Benutzer->set_str_nachname(mysql_result($result, 0, "nachname"));
      $Benutzer->set_date_geb_datum(mysql_result($result, 0, "geb_datum"));
      $Benutzer->set_str_beschreibung(mysql_result($result, 0, "beschreibung"));
      $Benutzer->set_str_email(mysql_result($result, 0, "email"));
      $Benutzer->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Benutzer->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Benutzer->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Benutzer->set_str_plz(mysql_result($result, 0, "plz"));
      $Benutzer->set_str_ort(mysql_result($result, 0, "ort"));
      $Benutzer->set_bool_datenschutz(mysql_result($result, 0, "datenschutz"));
      $Benutzer->set_bool_aktiv(mysql_result($result, 0, "aktiv"));
      mysql_free_result($result);
      return $Benutzer;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Benutzer_by_user_name($str_user_name) {
    $sql = "SELECT id_benutzer,
                   user_name,
                   vorname,
                   nachname,
                   geb_datum,
                   beschreibung,
                   email,
                   id_verein,
                   strasse,
                   hausnummer,
                   plz,
                   ort,
                   datenschutz,
                   aktiv
            FROM ". TABLE_BENUTZER ."
            WHERE user_name = '$str_user_name'";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      $Benutzer = new Benutzer();
      $Benutzer->set_int_id(mysql_result($result, 0, "id_benutzer"));
      $Benutzer->set_str_user_name(mysql_result($result, 0, "user_name"));
      $Benutzer->set_str_vorname(mysql_result($result, 0, "vorname"));
      $Benutzer->set_str_nachname(mysql_result($result, 0, "nachname"));
      $Benutzer->set_date_geb_datum(mysql_result($result, 0, "geb_datum"));
      $Benutzer->set_str_beschreibung(mysql_result($result, 0, "beschreibung"));
      $Benutzer->set_str_email(mysql_result($result, 0, "email"));
      $Benutzer->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Benutzer->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Benutzer->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Benutzer->set_str_plz(mysql_result($result, 0, "plz"));
      $Benutzer->set_str_ort(mysql_result($result, 0, "ort"));
      $Benutzer->set_bool_datenschutz(mysql_result($result, 0, "datenschutz"));
      $Benutzer->set_bool_aktiv(mysql_result($result, 0, "aktiv"));
      mysql_free_result($result);
      return $Benutzer;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Benutzer($str_gruppe, $int_id_verein=FALSE) {
    if ($int_id_verein == FALSE) {
      $sql = "SELECT b.id_benutzer,
                     user_name,
                     b.beschreibung,
                     email,
                     aktiv,
                     id_verein
              FROM ". TABLE_BENUTZER ." b,
                   ". TABLE_BENUTZER_GRUPPE ." b_g,
                   ". TABLE_GRUPPE ." g
              WHERE g.name = '$str_gruppe'
              AND b.id_benutzer = b_g.id_benutzer
              AND b_g.id_gruppe = g.id_gruppe
              AND b.aktiv = '1'
              ORDER BY user_name ASC";
    } else {
      $sql = "SELECT b.id_benutzer,
                     user_name,
                     b.beschreibung,
                     email,
                     aktiv,
                     id_verein
              FROM ". TABLE_BENUTZER ." b,
                   ". TABLE_BENUTZER_GRUPPE ." b_g,
                   ". TABLE_GRUPPE ." g
              WHERE g.name = '$str_gruppe'
              AND b.id_verein = $int_id_verein
              AND b.id_benutzer = b_g.id_benutzer
              AND b_g.id_gruppe = g.id_gruppe
              AND b.aktiv = '1'
              ORDER BY user_name ASC";
    }
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_Benutzer = array();
      $i = 0;
      while ($arr_row = mysql_fetch_assoc($result)) {
        $Benutzer = new Benutzer();
        $int_id_benutzer = $arr_row['id_benutzer'];
        $Benutzer->set_int_id($int_id_benutzer);
        $Benutzer->set_str_user_name($arr_row['user_name']);
        $Benutzer->set_str_beschreibung($arr_row['beschreibung']);
        $Benutzer->set_str_email($arr_row['email']);
        $Benutzer->set_bool_aktiv($arr_row['aktiv']);
        $Benutzer->set_int_id_verein($arr_row['id_verein']);

        // Gruppen holen
        //$Benutzer->set_arr_gruppe(self::get_gruppen($int_id_benutzer));

        // Teams holen
        //$Benutzer->set_arr_teams(self::get_list_mannschaften($int_id_benutzer));

        $arr_Benutzer[$i] = $Benutzer;
        $i++;
      }
      mysql_free_result($result);
      //$arr_list_person = mysql_fetch_array($result, MYSQL_ASSOC);
      return $arr_Benutzer;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_gruppen($int_id) {
    $sql = "SELECT g.name
            FROM ". TABLE_BENUTZER ." b,
            ". TABLE_BENUTZER_GRUPPE ." b_g,
            ". TABLE_GRUPPE ." g
            WHERE b.id_benutzer = $int_id
            AND b.id_benutzer = b_g.id_benutzer
            AND b_g.id_gruppe = g.id_gruppe";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_gruppen = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_gruppen[$i] = $arr_row[0];
        $i++;
      }
      return $arr_list_gruppen;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_verein($int_id) {
    $sql = "SELECT id_verein
            FROM ". TABLE_BENUTZER ."
            WHERE id_benutzer = $int_id";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "id_verein");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_mannschaften($int_id) {
    $sql = "SELECT id_teams
            FROM ". TABLE_BENUTZER ." b,
            ". TABLE_BENUTZER_TEAMS ." b_t
            WHERE b.id_benutzer = $int_id
            AND b.id_benutzer = b_t.id_benutzer";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_teams = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_teams[0][$i] = $arr_row[0];
        $arr_list_teams[1][$i] = DBMapper::get_teamname_by_id($arr_row[0]);
        $arr_list_teams[2][$i] = DBMapper::get_teamliga_by_id($arr_row[0]);
        $i++;
      }
      return $arr_list_teams;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_passwort_by_user_id($int_id) {
    $sql = "SELECT passwort
            FROM ". TABLE_BENUTZER ."
            WHERE id_benutzer = $int_id
            LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "passwort");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_passwort($passwort, $id_benutzer) {
    $passwort = md5($passwort);
    $sql = "UPDATE ". TABLE_BENUTZER ."
            SET passwort = '$passwort'
            WHERE id_benutzer = $id_benutzer
            LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_newPW($user_name, $str_new_pw) {
    $passwort = md5($str_new_pw);
    $sql = "UPDATE ". TABLE_BENUTZER ."
          SET passwort2 = '$passwort',
              passwort2_time = NOW()
          WHERE user_name = '$user_name'
          LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  /**
   * DBM_Benutzer::unsetPW2()
   *
   * @param int $id_benutzer
   * @return
   */
  public static function unsetPW2($id_benutzer) {
    $sql = "UPDATE ". TABLE_BENUTZER ."
           SET passwort2 = NULL,
               passwort2_time = NULL
          WHERE id_benutzer = $id_benutzer
          LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  /**
   * DBM_Benutzer::setNewPW()
   *
   * @param int $id_benutzer
   * @param string $passwort
   * @return
   */
  public static function setNewPW($id_benutzer, $passwort) {
    $passwort_md5 = md5($passwort);
    $sql = "UPDATE ". TABLE_BENUTZER ."
           SET passwort = '$passwort_md5'
           WHERE id_benutzer = $id_benutzer
           LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  /**
   * DBM_Benutzer::setDatenschutz()
   *
   * @param int $id_benutzer
   * @return
   */
  public static function setDatenschutz($id_benutzer) {
    $sql = "UPDATE ". TABLE_BENUTZER ."
           SET datenschutz = 1
           WHERE id_benutzer = $id_benutzer
           LIMIT 1";
    $db = self::db_connect(DB_USERS_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

}

?>
