<?php

require_once('class.DBM_root.php');

/**
 * DBM_Spieler
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * von Spieler-Daten in bzw. aus der Datenbank
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_Spieler extends DBM_root {


  public static function write_Spieler(Spieler $Spieler) {
    $sql = "INSERT INTO ". TABLE_SPIELER . "
            SET vorname     = '". mysql_real_escape_string($Spieler->get_str_vorname()) ."',
                name        = '". mysql_real_escape_string($Spieler->get_str_name()) ."',
                geschlecht  = '". $Spieler->get_bool_geschlecht() ."',
                geb_datum   = '". $Spieler->get_date_geb_datum(TRUE) ."',
                id_nation   = '". $Spieler->get_int_id_nation() ."',
                strasse     = '". $Spieler->get_str_strasse() ."',
                hausnummer  = '". $Spieler->get_str_hausnummer() ."',
                plz         = '". $Spieler->get_str_plz() ."',
                ort         = '". $Spieler->get_str_ort() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Spieler(Spieler $Spieler) {
    $sql = "UPDATE  ". TABLE_SPIELER . "
            SET vorname     = '". $Spieler->get_str_vorname() ."',
                name        = '". $Spieler->get_str_name() ."',
                geschlecht  = '". $Spieler->get_bool_geschlecht() ."',
                geb_datum   = '". $Spieler->get_date_geb_datum(TRUE) ."',
                id_nation   = '". $Spieler->get_int_id_nation() ."',
                strasse     = '". $Spieler->get_str_strasse() ."',
                hausnummer  = '". $Spieler->get_str_hausnummer() ."',
                plz         = '". $Spieler->get_str_plz() ."',
                ort         = '". $Spieler->get_str_ort() ."'
            WHERE id_spieler = '". $Spieler->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Spieler($int_id) {
    $sql = "SELECT *
            FROM ". TABLE_SPIELER ."
            WHERE id_spieler = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Spieler = new Spieler();
      $Spieler->set_int_id(mysql_result($result, 0, "id_spieler"));
      $Spieler->set_str_vorname(mysql_result($result, 0, "vorname"));
      $Spieler->set_str_name(mysql_result($result, 0, "name"));
      $Spieler->set_bool_geschlecht(mysql_result($result, 0, "geschlecht"));
      $Spieler->set_date_geb_datum(mysql_result($result, 0, "geb_datum"));
      $Spieler->set_int_id_nation(mysql_result($result, 0, "id_nation"));
      $Spieler->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Spieler->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Spieler->set_str_plz(mysql_result($result, 0, "plz"));
      $Spieler->set_str_ort(mysql_result($result, 0, "ort"));
      $Spieler->set_erstellt(mysql_result($result, 0, "erstellt"));
      return $Spieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function delete_Spieler($int_id) {
    // DB-Verbindung aufbauen
    $db = self::db_connect(DB_NAME, "rw");

    // Prüfen ob der Spieler wirklich gelöscht werden kann
    if (DBMapper::get_count_Lizenz_of_Spieler($int_id))
      return FALSE;

    // eigentlich noch alle Spielberichte checken
    // dasheißt ein count auf den mitspierer tabellen aller verbände
    if (DBMapper::get_count_Spieler_of_Mitspieler($int_id))
      return FALSE;


    // Spieler löschen
    // Zuerst zugehörigkeit zum Verein löschen
    $sql = "DELETE
            FROM ". TABLE_VEREIN_SPIELER ."
            WHERE id_spieler = $int_id";
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      return FALSE;
    }

    // Dann den Spieler löschen
    $sql = "DELETE FROM ". TABLE_SPIELER ."
            WHERE id_spieler = $int_id";
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function delete_Spieler_Verein($id_spieler, $id_verein) {
    if (DBMapper::get_count_Lizenz_of_Spieler2($id_spieler, $id_verein) )
      return FALSE;

    $sql = "DELETE
            FROM ". TABLE_VEREIN_SPIELER ."
            WHERE id_spieler = $id_spieler
            AND id_verein = $id_verein";
    $db = self::db_connect(DB_NAME, "rw");
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      return FALSE;
    }
    return TRUE;
  }

  public static function get_list_Spieler() {
    $sql = "SELECT id_spieler, name, vorname
            FROM ". TABLE_SPIELER ."
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "rw");
    if ($result = $db->query($sql)) {
      $arr_list_spieler = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieler[0][$i] = $arr_row[0];
        $arr_list_spieler[1][$i] = $arr_row[1].", ".$arr_row[2];
        $i++;
      }
      return $arr_list_spieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieler_of_Verein($int_id_verein) {
    $sql = "SELECT pl.id_spieler,
                   pl.name,
                   pl.vorname
            FROM ". TABLE_SPIELER ." pl,
                 ". TABLE_VEREIN_SPIELER ." vp
            WHERE pl.id_spieler=vp.id_spieler
                  AND vp.id_verein=$int_id_verein
            ORDER BY pl.name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spieler[0] = array();
      $arr_list_spieler[1] = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieler[0][$i] = $arr_row[0];
        $arr_list_spieler[1][$i] = $arr_row[1].", ".$arr_row[2];
        $i++;
      }
      return $arr_list_spieler;
    } else {
      // Fehlermeldung generieren
      if ($result === NULL) {
        $arr[0] = array();
        return $arr;
      }
      return FALSE;
    }
  }

  public static function get_count_Spieler_of_Verein($int_id_verein) {
    $sql = "SELECT count(*)
            FROM ". TABLE_VEREIN_SPIELER ."
            WHERE id_verein=$int_id_verein";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieler_of_SG($int_id_team) {
    // baustelle
    $sql = "SELECT pl.id_spieler,
                   pl.name,
                   pl.vorname
            FROM ". TABLE_SPIELER ." pl,
                 ". TABLE_VEREIN_SPIELER ." vp,
                 ". TABLE_SG ." sg
            WHERE sg.id_mannschaft = $int_id_team
                  AND sg.id_verein = vp.id_verein
                  AND pl.id_spieler = vp.id_spieler
            ORDER BY pl.name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spieler[0] = array();
      $arr_list_spieler[1] = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_spieler[0][$i] = $arr_row[0];
        $arr_list_spieler[1][$i] = $arr_row[1].", ".$arr_row[2];
        $i++;
      }
      return $arr_list_spieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieler_of_Mannschaft($int_id_team,
                                                        $int_id_verein = 0,
                                                        $id_verband = 0) {
    // baustelle
    // zuerst die Spieler ids aus der Lizenz Tabelle holen
    $arr_ids = array();
    $db = self::db_connect(DB_NAME, "rw");

    if ($id_verband != 0) {
      $verband = DBMapper::get_Pfad_of_Verband($id_verband);
    } else {
      $verband = VERBAND;
    }
    $sql = "SELECT id_spieler FROM ". $verband . TABLE_LIZENZ ."
            WHERE id_mannschaft = $int_id_team";

    if ($result = $db->query($sql)) {
      $i = 0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_ids[$i] = $arr_row[0];
        $i++;
      }
    }
    else {
      // Fehlermeldung generieren
      if ($result === NULL) {
        $arr[0] = array();
        return $arr;
      }
      return FALSE;
    }

    // und nun die einzelnen Spieler laden
    $arr_list_spieler[0] = array();
    $arr_list_spieler[1] = array();
    $arr_list_spieler[2] = array();
    $arr_list_spieler[3] = array();
    $arr_list_spieler[4] = array();
    $arr_list_spieler[5] = array();
    $arr_list_spieler[6] = array();

    if ($i <= 0) return $arr_list_spieler;
    $db = self::db_connect(DB_NAME, "r");
    if ($int_id_verein) {
      $sql = "SELECT pl.id_spieler,
                     pl.name,
                     pl.vorname,
                     pl.geb_datum,
                     pl.erstellt
              FROM ". TABLE_SPIELER ." pl,
                   ". TABLE_VEREIN_SPIELER ." v
              WHERE (pl.id_spieler = ".$arr_ids[0];
      for ($i=1; $i<count($arr_ids); $i++) {
        $sql .= "
            OR pl.id_spieler = ".$arr_ids[$i];
      }
      $sql .= ")
                AND pl.id_spieler = v.id_spieler
                AND v.id_verein = $int_id_verein
                ORDER BY pl.name ASC";
    }
    else {
      $sql = "SELECT id_spieler,
                     name,
                     vorname,
                     geb_datum,
                     erstellt
              FROM ". TABLE_SPIELER ."
              WHERE id_spieler = ".$arr_ids[0];
      for ($i=1; $i<count($arr_ids); $i++) {
        $sql .= "
              OR id_spieler = ".$arr_ids[$i];
      }
      $sql .= "
              ORDER BY name ASC";
    }

    if ($result = $db->query($sql)) {
      $i=0;
      while($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieler[0][$i] = $arr_row[0];
        $arr_list_spieler[1][$i] = $arr_row[1];
        $arr_list_spieler[2][$i] = $arr_row[2];
        $str_tmp = $arr_row[3];
        $geb_datum = sprintf("%02d.%02d.%04d",
                              substr($str_tmp, 8, 2),
                              substr($str_tmp, 5, 2),
                              substr($str_tmp, 0, 4));
        $arr_list_spieler[3][$i] = $geb_datum;

        $str_tmp = $arr_row[4];
        $beantragt_datum = sprintf("%02d.%02d.%04d",
                                   substr($str_tmp, 8, 2),
                                   substr($str_tmp, 5, 2),
                                   substr($str_tmp, 0, 4));
        $arr_list_spieler[4][$i] = $beantragt_datum;
        $arr_list_spieler[5][$i] = DBM_Spieler::get_last_Spieler_Lizenzstatus($arr_row[0], $int_id_team, $id_verband);
        $arr_list_spieler[6][$i] = DBM_Spieler::get_order_Spieler_Lizenzstatus($arr_row[0], $int_id_team, $id_verband);
        $i++;
      }
      return $arr_list_spieler;
    }
    else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieler_by_Begegnung($int_id_begegnung) {
    // baustelle
    $sql = "SELECT id_spieler
            FROM ". VERBAND . TABLE_LIZENZ ." lizenz,
                 ". VERBAND . TABLE_BEGEGNUNG ." begegnung
            WHERE begegnung.id_begegnung = $int_id_begegnung
                  AND (lizenz.id_mannschaft = begegnung.id_mannschaft1
                       OR lizenz.id_mannschaft = begegnung.id_mannschaft2)";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $i = 0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_ids[$i] = $arr_row[0];
        $i++;
      }
    }
    else {
      // Fehlermeldung generieren
      if($result === NULL) {
        $arr[0] = array();
        return $arr;
      }
      return FALSE;
    }

    $sql = "SELECT id_spieler, name, vorname
            FROM ". TABLE_SPIELER ."
              WHERE (id_spieler = ".$arr_ids[0];
    for ($i=1; $i<count($arr_ids); $i++) {
      $sql .= "
              OR id_spieler = ".$arr_ids[$i];
    }
    $sql .= ")
        ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      while ($arr_row = mysql_fetch_array($result)) {
        $arr_spieler[$arr_row[0]] = array($arr_row[1], $arr_row[2]);
      } // while
      return $arr_spieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function set_Spieler_of_Verein($int_id_spieler,
                                               $int_id_verein,
                                               $bool_erstverein) {
    $sql = "INSERT INTO ". TABLE_VEREIN_SPIELER . "
            SET id_spieler     = '". $int_id_spieler ."',
                id_verein      = '". $int_id_verein."',
                erstverein     = '".$bool_erstverein."'";

    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_nation($int_id) {
    if ($int_id == NULL) {
      return FALSE;
    }
    $sql = "SELECT name, eu, kuerzel
            FROM ". TABLE_NATION ."
            WHERE id_nation = $int_id
            LIMIT 1";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_res[0] = mysql_result($result, 0, "name");
      $arr_res[1] = mysql_result($result, 0, "eu");
      $arr_res[2] = mysql_result($result, 0, "kuerzel");
      return $arr_res;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_nation() {
    $sql = "SELECT id_nation, name, kuerzel
            FROM ". TABLE_NATION ."
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_nation = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_nation[0][$i] = $arr_row[0];
        $arr_list_nation[1][$i] = $arr_row[1];
        $arr_list_nation[2][$i] = $arr_row[2];
        $i++;
      }
        return $arr_list_nation;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_Verein_by_Spieler($id_spieler) {
    $sql = "SELECT id_verein, erstverein FROM ". TABLE_VEREIN_SPIELER ."
            WHERE id_spieler = $id_spieler";
    $db = self::db_connect(DB_NAME, "r");
    if($result = $db->query($sql)) {
      $arr_list_verein[0] = array(); // ge�ndert : [] entfernt 
      $arr_list_verein[1] = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)) {
        $arr_list_verein[0][] = $arr_row[0];
        $arr_list_verein[1][] = $arr_row[1];
        $i++;
      }
      return $arr_list_verein;
    }
    else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function check_Spieler($Spieler) {
    $sql = "SELECT count(*) FROM ". TABLE_SPIELER ."
            WHERE name = '".mysql_real_escape_string($Spieler->get_str_name())."'
            AND vorname = '".mysql_real_escape_string($Spieler->get_str_vorname())."'
            AND geb_datum = '".$Spieler->get_date_geb_datum(TRUE)."'
            AND id_spieler != '".$Spieler->get_int_id()."'";
    $db = self::db_connect(DB_NAME, "r");
    if($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_last_Spieler_Lizenzstatus($id_spieler, $id_mannschaft, $id_verband = 0) {
    if ($id_verband) {
      $verband = DBMapper::get_Pfad_of_Verband($id_verband);
    } else {
      $verband = VERBAND;
    }
    $db = self::db_connect(DB_NAME, "r");

    $sql = "SELECT lv.id_lizenzstatus, DATE_FORMAT(lv.timestamp, '%d.%m.%Y'), l.id_lizenz
            FROM ". $verband . TABLE_LIZENZVERLAUF ." lv,
                 ". $verband . TABLE_LIZENZ ." l
            WHERE l.id_spieler = $id_spieler
            AND   l.id_lizenz = lv.id_lizenz
            AND   l.id_mannschaft = $id_mannschaft
            ORDER BY timestamp DESC
            LIMIT 1";

    if ($result = $db->query($sql)) {
      return mysql_fetch_row($result);
      //var_dump($arr_result);
      //return $arr_result;
   } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_order_Spieler_Lizenzstatus($id_spieler, $id_mannschaft, $id_verband = 0) {
    if ($id_verband) {
      $verband = DBMapper::get_Pfad_of_Verband($id_verband);
    } else {
      $verband = VERBAND;
    }
    $sql = "SELECT DATE_FORMAT(lv.timestamp, '%d.%m.%Y')
            FROM ". $verband . TABLE_LIZENZVERLAUF ." lv,
                 ". $verband . TABLE_LIZENZ ." l
            WHERE l.id_spieler = $id_spieler
            AND   l.id_lizenz = lv.id_lizenz
            AND   l.id_mannschaft = $id_mannschaft
            AND  lv.id_lizenzstatus = 2
            ORDER BY timestamp DESC
            LIMIT 1";

    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_fetch_row($result);
      //var_dump($arr_result);
      //return $arr_result;
   } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

}
?>
