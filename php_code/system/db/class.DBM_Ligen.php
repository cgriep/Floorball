<?php

require_once('class.DBM_root.php');

/**
 * DBM_Ligen
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * von Liga-Daten in bzw. aus der Datenbank
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_Ligen extends DBM_root {


  public static function get_ligen_by_sortierung($int_level, $int_ordnungsnr){
    $sql = "SELECT liga.id_liga as id_liga,
                   liga.name as name
            FROM ". VERBAND . TABLE_LIGA ." liga
            WHERE ordnungsnr   = '$int_ordnungsnr'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_ligen = array();
      for ($i=0; $i<mysql_num_rows($result); $i++){
        $arr_ligen[0][$i] = mysql_result($result, $i, "id_liga");
        $arr_ligen[1][$i] = mysql_result($result, $i, "name");
      }
      return $arr_ligen;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_liga_by_begegnung($int_id_begegnung){
    $sql = "SELECT liga.name as name
            FROM ". VERBAND . TABLE_LIGA ." liga,
                 ". VERBAND . TABLE_SPIELTAG ." spieltag,
                 ". VERBAND . TABLE_BEGEGNUNG ." begegnung
            WHERE liga.id_liga = spieltag.id_liga
                  AND spieltag.id_spieltag = begegnung.id_spieltag
                  AND begegnung.id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Liga(Liga $Liga) {
    $int_id_kategorie   = DBM_root::add_quotes($Liga->get_int_id_kategorie());
    $int_id_klasse      = DBM_root::add_quotes($Liga->get_int_id_klasse());
    $int_id_saison      = DBM_root::add_quotes($Liga->get_int_id_saison());
    $int_id_spielsystem = DBM_root::add_quotes($Liga->get_int_id_spielsystem());
    $str_name           = DBM_root::add_quotes($Liga->get_str_name());
    $date_stichtag      = DBM_root::add_quotes($Liga->get_date_stichtag(true));
    $bool_stichtag_typ  = DBM_root::add_quotes($Liga->get_bool_stichtag_typ());
    $str_kurzname       = DBM_root::add_quotes($Liga->get_str_kurzname());
    $bool_weiblich      = DBM_root::add_quotes($Liga->get_bool_weiblich());
    $int_ordnungsnr     = DBM_root::add_quotes($Liga->get_int_ordnungsnr());
    $sql = "INSERT INTO ". VERBAND . TABLE_LIGA . "
            SET id_kategorie = $int_id_kategorie ,
                id_klasse    = $int_id_klasse ,
                id_saison    = $int_id_saison ,
                id_spielsystem = $int_id_spielsystem ,
                name         = $str_name ,
                stichtag     = $date_stichtag ,
                stichtag_typ = $bool_stichtag_typ ,
                kurzname     = $str_kurzname ,
                weiblich     = $bool_weiblich ,
                ordnungsnr   = $int_ordnungsnr";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Liga(Liga $Liga) {
    $int_id_kategorie = DBM_root::add_quotes($Liga->get_int_id_kategorie());
    $int_id_klasse    = DBM_root::add_quotes($Liga->get_int_id_klasse());
    $int_id_saison    = DBM_root::add_quotes($Liga->get_int_id_saison());
    $int_id_spielsystem = DBM_root::add_quotes($Liga->get_int_id_spielsystem());
    $str_name         = DBM_root::add_quotes($Liga->get_str_name());
    $date_stichtag    = DBM_root::add_quotes($Liga->get_date_stichtag(true));
    $bool_stichtag_typ = DBM_root::add_quotes($Liga->get_bool_stichtag_typ());
    $str_kurzname     = DBM_root::add_quotes($Liga->get_str_kurzname());
    $bool_weiblich     = DBM_root::add_quotes($Liga->get_bool_weiblich());
    $int_ordnungsnr    = DBM_root::add_quotes($Liga->get_int_ordnungsnr());
    $sql = "UPDATE ". VERBAND . TABLE_LIGA . "
            SET id_kategorie = $int_id_kategorie ,
                id_klasse    = $int_id_klasse ,
                id_saison    = $int_id_saison ,
                id_spielsystem = $int_id_spielsystem ,
                name         = $str_name ,
                stichtag     = $date_stichtag ,
                stichtag_typ = $bool_stichtag_typ ,
                kurzname     = $str_kurzname ,
                weiblich     = $bool_weiblich ,
                ordnungsnr   = $int_ordnungsnr
             WHERE id_liga = '". $Liga->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Liga($int_id) {
    $sql = "SELECT id_liga,
                   id_klasse,
                   id_kategorie,
                   id_saison,
                   id_spielsystem,
                   stichtag,
                   stichtag_typ,
                   kurzname,
                   weiblich,
                   ordnungsnr,
                   name as liga_name
            FROM ". VERBAND . TABLE_LIGA ."
            WHERE id_liga=$int_id";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Liga = new Liga();
      $Liga->set_int_id(mysql_result($result, 0, "id_liga"));
      $Liga->set_int_id_klasse(mysql_result($result, 0, "id_klasse"));
      $Liga->set_int_id_kategorie(mysql_result($result, 0, "id_kategorie"));
      $Liga->set_int_id_saison(mysql_result($result, 0, "id_saison"));
      $Liga->set_int_id_spielsystem(mysql_result($result, 0, "id_spielsystem"));
      $Liga->set_str_name(mysql_result($result, 0, "liga_name"));
      $Liga->set_date_stichtag(mysql_result($result, 0, "stichtag"));
      $Liga->set_str_kurzname(mysql_result($result, 0, "kurzname"));
      $Liga->set_bool_stichtag_typ(mysql_result($result, 0, "stichtag_typ"));
      $Liga->set_bool_weiblich(mysql_result($result, 0, "weiblich"));
      $Liga->set_int_ordnungsnr(mysql_result($result, 0, "ordnungsnr"));
      //$Liga->set_str_name(mysql_result($result, 0, "saison_name"));
      $int_id_kategorie = $Liga->get_int_id_kategorie();
      $int_id_klasse = $Liga->get_int_id_klasse();
      $sql = "SELECT ka.name as kategorie_name,
                     kl.name as klasse_name
              FROM ". TABLE_KATEGORIE ." ka,
                   ". TABLE_KLASSE ." kl
              WHERE ka.id_kategorie=$int_id_kategorie
                AND kl.id_klasse=$int_id_klasse";
      $db = self::db_connect(DB_NAME, "r");
      if($result = $db->query($sql)) {
        $Liga->set_str_kategorie(mysql_result($result, 0, "kategorie_name"));
        $Liga->set_str_klasse(mysql_result($result, 0, "klasse_name"));
      }
      return $Liga;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Liga() {
    $sql = "SELECT id_liga, name, kurzname, id_kategorie
            FROM ". VERBAND . TABLE_LIGA ."
            ORDER BY ordnungsnr ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_liga = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_liga[0][$i] = $arr_row[0];
        $arr_list_liga[1][$i] = $arr_row[1];
        $arr_list_liga[2][$i] = $arr_row[2];
        $arr_list_liga[3][$i] = $arr_row[3];
        $i++;
      }
        return $arr_list_liga;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Liga2() {
    $sql = "SELECT id_liga, name, kurzname, id_kategorie
            FROM ". VERBAND . TABLE_LIGA ."
            ORDER BY id_klasse ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_liga = array();
      $i = 0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_liga[$i][0] = $arr_row[0];
        $arr_list_liga[$i][1] = $arr_row[1];
        $arr_list_liga[$i][2] = $arr_row[2];
        $arr_list_liga[$i][3] = $arr_row[3];
        ++$i;
      }
      return $arr_list_liga;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_name_spielsystem($int_id_spielsystem) {
    $sql = "SELECT name
            FROM ". TABLE_SPIELSYSTEM ."
            WHERE id_spielsystem = $int_id_spielsystem";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $str_name = mysql_result($result, 0, "name");
      return $str_name;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_spielsystem() {
    $sql = "SELECT id_spielsystem, name
            FROM ". TABLE_SPIELSYSTEM;
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_system = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_system[0][$i] = $arr_row[0];
        $arr_list_system[1][$i] = $arr_row[1];
        $i++;
      }
        return $arr_list_system;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function delete_Liga($int_id) {
    // DB Verbindung aufbauen
    $db = self::db_connect(DB_NAME, "rw");

    // Prüfen ob die Liga wirklich gelöscht werden kann
    $sql = "SELECT count(*)
            FROM ". VERBAND . TABLE_MANNSCHAFT ."
            WHERE id_liga = $int_id";
    if($result = $db->query($sql)) {
      if(mysql_result($result, 0, "count(*)")) {
        // Fehlermeldung generieren
        var_dump($result);
        echo "daaaaaaaaaaa";
        return FALSE;
      }
    }
    else {
      // hier sollten wir nie hinkommen
      return FALSE;
    }

    $sql = "SELECT count(*)
            FROM ". VERBAND . TABLE_SPIELTAG ."
            WHERE id_liga = $int_id";
    if ($result = $db->query($sql)) {
      if (mysql_result($result, 0, "count(*)")) {
        // Fehlermeldung generieren
        var_dump($result);
        echo "daaaaaaaaaaa";
        return FALSE;
      }
    }
    else {
      // hier sollten wir nie hinkommen
      return FALSE;
    }

    // Liga kann gelöscht werden
    $sql = "DELETE FROM ". VERBAND . TABLE_LIGA ."
            WHERE id_liga = $int_id";
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }

  }
}

?>
