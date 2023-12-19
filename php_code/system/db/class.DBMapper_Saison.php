<?php

require_once('class.DBM_root.php');

/**
 * DBMapper
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * der Datenbank (Diese Klasse bedarf der überarbeitung)
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 *
 * @todo Klasse aufteilen in geeignete Unter-Klassen
 *
 */
class DBMapper_Saison extends DBM_root {

  public static function get_aktuelle_Saison() {
    $sql = "SELECT saison.id_saison,
                   saison.name,
                   saison.gesperrt
            FROM ". TABLE_SYSTEM ." system ,
                 ". TABLE_SAISON ." saison
            WHERE saison.id_saison = system.id_aktuelle_saison
                  AND system.id_system = '1'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Saison = new Saison();
      $Saison->set_int_id(mysql_result($result, 0, "id_saison"));
      $Saison->set_str_name(mysql_result($result, 0, "name"));
      $Saison->set_bool_gesperrt(mysql_result($result, 0, "gesperrt"));
      return $Saison;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_Saison_by_id($int_id_saison) {
    $sql = "SELECT id_saison,
                   name,
                   gesperrt
            FROM ". TABLE_SAISON ."
            WHERE id_saison = $int_id_saison
            LIMIT 1";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Saison = new Saison();
      $Saison->set_int_id(mysql_result($result, 0, "id_saison"));
      $Saison->set_str_name(mysql_result($result, 0, "name"));
      $Saison->set_bool_gesperrt(mysql_result($result, 0, "gesperrt"));
      return $Saison;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

}

?>