<?php

require_once('class.DBM_root.php');

/**
 * DBM_tmp_spielbericht
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * von Spielgericht-Daten in bzw. aus der Datenbank (TMP Tabellen)
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_tmp_spielbericht extends DBM_root {


  public static function write_Ereignis(Ereignis $Ereignis) {
    $sql = "INSERT INTO ". VERBAND . TABLE_TMP_EREIGNIS . "
            SET id_begegnung   = '". $Ereignis->get_int_id_begegnung() ."',
                bearbeitungsnr = '". $Ereignis->get_int_bearbeitungsnr() ."',
                zeile          = '". $Ereignis->get_int_zeile() ."',
                nr_team1       = '". $Ereignis->get_int_nr_team1() ."',
                ass_team1      = '". $Ereignis->get_int_ass_team1() ."',
                periode        = '". $Ereignis->get_int_periode() ."',
                zeit           = '". $Ereignis->get_str_zeit() ."',
                tore_team1     = '". $Ereignis->get_int_tore_team1() ."',
                tore_team2     = '". $Ereignis->get_int_tore_team2() ."',
                id_strafe      = '". $Ereignis->get_int_id_strafe() ."',
                id_strafcode   = '". $Ereignis->get_int_id_strafcode() ."',
                nr_team2       = '". $Ereignis->get_int_nr_team2() ."',
                ass_team2      = '". $Ereignis->get_int_ass_team2() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  private static function read_Ereignis($int_bearbeitungsnr) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_TMP_EREIGNIS ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'
            ORDER BY id_ereignis ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $num_rows = mysql_num_rows($result);
      $arr_Ereignis = array();
      for ($i=0; $i<$num_rows; $i++) {
        $Ereignis = new Ereignis();
        $Ereignis->set_int_id(mysql_result($result, $i, "id_ereignis"));
        $Ereignis->set_int_id_begegnung(mysql_result($result, $i, "id_begegnung"));
        $Ereignis->set_int_bearbeitungsnr(mysql_result($result, $i, "bearbeitungsnr"));
        $Ereignis->set_int_zeile(mysql_result($result, $i, "zeile"));
        $Ereignis->set_int_nr_team1(mysql_result($result, $i, "nr_team1"));
        $Ereignis->set_int_ass_team1(mysql_result($result, $i, "ass_team1"));
        $Ereignis->set_int_periode(mysql_result($result, $i, "periode"));
        $Ereignis->set_str_zeit(mysql_result($result, $i, "zeit"));
        $Ereignis->set_int_tore_team1(mysql_result($result, $i, "tore_team1"));
        $Ereignis->set_int_tore_team2(mysql_result($result, $i, "tore_team2"));
        $Ereignis->set_int_id_strafe(mysql_result($result, $i, "id_strafe"));
        $Ereignis->set_int_id_strafcode(mysql_result($result, $i, "id_strafcode"));
        $Ereignis->set_int_nr_team2(mysql_result($result, $i, "nr_team2"));
        $Ereignis->set_int_ass_team2(mysql_result($result, $i, "ass_team2"));
        $arr_Ereignis[$i] = $Ereignis;
      }
      mysql_free_result($result);
      return $arr_Ereignis;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Mitspieler(Mitspieler $Mitspieler) {
    if ($Mitspieler->get_int_id_spieler() < 0) {
      $int_id_spieler = NULL;
    } else {
      $int_id_spieler = $Mitspieler->get_int_id_spieler();
    }
    $sql = "INSERT INTO ". VERBAND . TABLE_TMP_MITSPIELER . "
            SET id_begegnung = '". $Mitspieler->get_int_id_begegnung() ."',
                bearbeitungsnr = '". $Mitspieler->get_int_bearbeitungsnr() ."',
                id_spieler   = ". self::add_quotes($int_id_spieler) .",
                trikotnr     = '". $Mitspieler->get_int_trikotnr() ."',
                torwart      = '". $Mitspieler->get_bool_torwart() ."',
                kapitain     = '". $Mitspieler->get_bool_kapitain() ."',
                team         = '". $Mitspieler->get_int_team() ."',
                name         = '". mysql_real_escape_string($Mitspieler->get_str_name()) ."',
                vorname      = '". mysql_real_escape_string($Mitspieler->get_str_vorname()) ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Betreuer(Betreuer $Betreuer) {
    $sql = "INSERT INTO ". VERBAND . TABLE_TMP_BETREUER . "
            SET id_begegnung = '". $Betreuer->get_int_id_begegnung() ."',
                bearbeitungsnr = '". $Betreuer->get_int_bearbeitungsnr() ."',
                betreuer1    = '". $Betreuer->get_str_betreuer1() ."',
                betreuer2    = '". $Betreuer->get_str_betreuer2() ."',
                betreuer3    = '". $Betreuer->get_str_betreuer3() ."',
                betreuer4    = '". $Betreuer->get_str_betreuer4() ."',
                betreuer5    = '". $Betreuer->get_str_betreuer5() ."',
                betreuer1_unterschrift = '". $Betreuer->get_bool_unterschrift() ."',
                team         = '". $Betreuer->get_int_team() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Spielbericht($int_bearbeitungsnr) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_TMP_SPIELBERICHT ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'";
    $Spielbericht = new Spielbericht();
    $Spielbericht->set_int_bearbeitungsnr($int_bearbeitungsnr);
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Spielbericht->set_int_id(mysql_result($result, 0, "id_spielbericht"));
      $Spielbericht->set_int_id_begegnung(mysql_result($result, 0,
                                                       "id_begegnung"));
      $Spielbericht->set_int_id_schiri1(mysql_result($result, 0,
                                                     "id_schiedsrichter1"));
      $Spielbericht->set_int_id_schiri2(mysql_result($result, 0,
                                                     "id_schiedsrichter2"));
      $Spielbericht->set_str_schiedsgericht1(mysql_result($result, 0,
                                                          "schiedsgericht1"));
      $Spielbericht->set_str_schiedsgericht2(mysql_result($result, 0,
                                                          "schiedsgericht2"));
      $Spielbericht->set_str_schiri1_name(mysql_result($result, 0,
                                                       "schiedsrichter1"));
      $Spielbericht->set_str_schiri2_name(mysql_result($result, 0,
                                                       "schiedsrichter2"));
      $Spielbericht->set_bool_unterschrift_schiri1(mysql_result($result, 0,
                                                                "unterschrift_schiri1"));
      $Spielbericht->set_bool_unterschrift_schiri2(mysql_result($result, 0,
                                                                "unterschrift_schiri2"));
      $Spielbericht->set_bool_unterschrift_schiedsgericht1(mysql_result($result, 0, "unterschrift_schiedsgericht1"));
      $Spielbericht->set_bool_unterschrift_schiedsgericht2(mysql_result($result, 0, "unterschrift_schiedsgericht2"));
      $Spielbericht->set_str_timeout1(mysql_result($result, 0, "timeout1"));
      $Spielbericht->set_str_timeout2(mysql_result($result, 0, "timeout2"));
      $Spielbericht->set_bool_matchstrafe1(mysql_result($result, 0,
                                                        "matchstrafe1"));
      $Spielbericht->set_bool_matchstrafe2(mysql_result($result, 0,
                                                        "matchstrafe2"));
      $Spielbericht->set_bool_matchstrafe3(mysql_result($result, 0,
                                                        "matchstrafe3"));
      $Spielbericht->set_bool_besonderes_ereignis(mysql_result($result, 0,
                                                               "bes_ereignisse"));
      $Spielbericht->set_bool_protest(mysql_result($result, 0, "protest"));
      $Spielbericht->set_bool_unterschrift_kaptain1(mysql_result($result, 0, "unterschrift_kapitain1"));
      $Spielbericht->set_bool_unterschrift_kaptain2(mysql_result($result, 0, "unterschrift_kapitain2"));
      $Spielbericht->set_bool_verlaengerung(mysql_result($result, 0, "verlaengerung"));
      $Spielbericht->set_int_id_eingetragen_von(mysql_result($result, 0, "eingetragen_von"));
      $Spielbericht->set_time_eingetragen_am(mysql_result($result, 0, "eingetragen_am"));
      $Spielbericht->set_str_kommentar(mysql_result($result, 0, "kommentar"));

      mysql_free_result($result);
    }
    // Betreuer setzen
    $Betreuer_team1 = self::read_Betreuer($int_bearbeitungsnr, 1);
    $Spielbericht->set_Betreuer_team1($Betreuer_team1);
    $Betreuer_team2 = self::read_Betreuer($int_bearbeitungsnr, 2);
    $Spielbericht->set_Betreuer_team2($Betreuer_team2);
    // Mitspieler setzen
    $arr_Mitspieler = self::read_Mitspieler($int_bearbeitungsnr, 1);
    $Spielbericht->set_arr_Mitspieler_team1($arr_Mitspieler);
    $arr_Mitspieler = self::read_Mitspieler($int_bearbeitungsnr, 2);
    $Spielbericht->set_arr_Mitspieler_team2($arr_Mitspieler);
    // Ereignisse setzen
    $arr_Ereignis = self::read_Ereignis($int_bearbeitungsnr);
    $Spielbericht->set_arr_Ereignis($arr_Ereignis);

    return $Spielbericht;
  }


  private static function read_Betreuer($int_bearbeitungsnr, $int_team) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_TMP_BETREUER ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'
                  AND team = '$int_team'
            ORDER BY id_betreuer DESC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Betreuer = new Betreuer();
      $Betreuer->set_int_id(mysql_result($result, 0, "id_betreuer"));
      $Betreuer->set_int_id_begegnung(mysql_result($result, 0, "id_begegnung"));
      $Betreuer->set_int_bearbeitungsnr(mysql_result($result, 0, "bearbeitungsnr"));
      $Betreuer->set_str_betreuer1(mysql_result($result, 0, "betreuer1"));
      $Betreuer->set_str_betreuer2(mysql_result($result, 0, "betreuer2"));
      $Betreuer->set_str_betreuer3(mysql_result($result, 0, "betreuer3"));
      $Betreuer->set_str_betreuer4(mysql_result($result, 0, "betreuer4"));
      $Betreuer->set_str_betreuer5(mysql_result($result, 0, "betreuer5"));
      $Betreuer->set_bool_unterschrift(mysql_result($result, 0, "betreuer1_unterschrift"));
      $Betreuer->set_int_team(mysql_result($result, 0, "team"));
      mysql_free_result($result);
      return $Betreuer;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  private static function read_Mitspieler($int_bearbeitungsnr, $int_team) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_TMP_MITSPIELER ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'
                  AND team = '$int_team'
            ORDER BY trikotnr ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $num_rows = mysql_num_rows($result);
      $arr_Mitspieler = array();
      for ($i=0; $i<$num_rows; $i++) {
        $Mitspieler = new Mitspieler();
        $Mitspieler->set_int_id(mysql_result($result, $i, "id_mitspieler"));
        $Mitspieler->set_int_id_begegnung(mysql_result($result, $i, "id_begegnung"));
        $Mitspieler->set_int_bearbeitungsnr(mysql_result($result, $i, "bearbeitungsnr"));
        $int_id_spieler = mysql_result($result, $i, "id_spieler");
        if ($int_id_spieler == NULL) {
          $int_id_spieler = -1;
        }
        $Mitspieler->set_int_id_spieler($int_id_spieler);
        $Mitspieler->set_int_trikotnr(mysql_result($result, $i, "trikotnr"));
        $Mitspieler->set_bool_torwart(mysql_result($result, $i, "torwart"));
        $Mitspieler->set_bool_kapitain(mysql_result($result, $i, "kapitain"));
        $Mitspieler->set_int_team(mysql_result($result, $i, "team"));
        $Mitspieler->set_str_name(mysql_result($result, $i, "name"));
        $Mitspieler->set_str_vorname(mysql_result($result, $i, "vorname"));
        $arr_Mitspieler[$i] = $Mitspieler;
      }
      mysql_free_result($result);
      return $arr_Mitspieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Mitspieler($int_bearbeitungsnr, $int_team){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_TMP_MITSPIELER ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'
                  AND team = '$int_team'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Betreuer($int_bearbeitungsnr, $int_team){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_TMP_BETREUER ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'
                  AND team = '$int_team'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Ereignis($int_bearbeitungsnr){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_TMP_EREIGNIS ."
            WHERE bearbeitungsnr = '$int_bearbeitungsnr'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Spielbericht(Spielbericht $Spielbericht) {
    // Zuerst den Spielbericht schreiben
    // Bearbeitsnummer in die anderen Objekte eintragen
    // Objekte in die DB schreiben
    // Bearbeitungsnummer zurückgeben

    // Spielbericht in die DB schreiben
    $sql = "INSERT INTO ". VERBAND . TABLE_TMP_SPIELBERICHT . "
            SET id_begegnung = '". $Spielbericht->get_int_id_begegnung() ."',
                id_schiedsrichter1 = '". $Spielbericht->get_int_id_schiri1() ."',
                id_schiedsrichter2 = '". $Spielbericht->get_int_id_schiri2() ."',
                schiedsgericht1 = '". $Spielbericht->get_str_schiedsgericht1() ."',
                schiedsgericht2 = '". $Spielbericht->get_str_schiedsgericht2() ."',
                schiedsrichter1 = '". $Spielbericht->get_str_schiri1_name() ."',
                schiedsrichter2 = '". $Spielbericht->get_str_schiri2_name() ."',
                unterschrift_schiri1 = '". $Spielbericht->get_bool_unterschrift_schiri1() ."',
                unterschrift_schiri2 = '". $Spielbericht->get_bool_unterschrift_schiri2() ."',
                unterschrift_schiedsgericht1 = '". $Spielbericht->get_bool_unterschrift_schiedsgericht1() ."',
                unterschrift_schiedsgericht2 = '". $Spielbericht->get_bool_unterschrift_schiedsgericht2() ."',
                timeout1   = '". mysql_real_escape_string($Spielbericht->get_str_timeout1()) ."',
                timeout2   = '". mysql_real_escape_string($Spielbericht->get_str_timeout2()) ."',
                matchstrafe1 = '". $Spielbericht->get_bool_matchstrafe1() ."',
                matchstrafe2 = '". $Spielbericht->get_bool_matchstrafe2() ."',
                matchstrafe3 = '". $Spielbericht->get_bool_matchstrafe3() ."',
                bes_ereignisse = '". $Spielbericht->get_bool_besonderes_ereignis() ."',
                protest      = '". $Spielbericht->get_bool_protest() ."',
                unterschrift_kapitain1 = '". $Spielbericht->get_bool_unterschrift_kaptain1() ."',
                unterschrift_kapitain2 = '". $Spielbericht->get_bool_unterschrift_kaptain2() ."',
                verlaengerung = '". $Spielbericht->get_bool_verlaengerung() ."',
                eingetragen_von = '". $_SESSION['arr_login']['id_benutzer'] ."',
                eingetragen_am  = '". time() ."',
                kommentar       = '". $Spielbericht->get_str_kommentar() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      $int_bearbeitungsnr = $last_insert_id;
    } else {
      return FALSE;
    }


    // Ereignisse in die DB schreiben
    $arr_Ereignis = $Spielbericht->get_arr_Ereignis();
    foreach( $arr_Ereignis as $Ereignis ){
      $Ereignis->set_int_bearbeitungsnr($int_bearbeitungsnr);
      self::write_Ereignis($Ereignis);
    }

    // Betreuer in die DB Schreiben
    $Betreuer1 = $Spielbericht->get_Betreuer_team1();
    $Betreuer1->set_int_bearbeitungsnr($int_bearbeitungsnr);
    self::write_Betreuer($Betreuer1);
    $Betreuer2 = $Spielbericht->get_Betreuer_team2();
    $Betreuer2->set_int_bearbeitungsnr($int_bearbeitungsnr);
    self::write_Betreuer($Betreuer2);

    // Mitspieler in die DB Schreiben
    $arr_Mitspieler1 = $Spielbericht->get_arr_Mitspieler_team1();
    foreach( $arr_Mitspieler1 as $Mitspieler ){
      $Mitspieler->set_int_bearbeitungsnr($int_bearbeitungsnr);
      self::write_Mitspieler($Mitspieler);
    }
    $arr_Mitspieler2 = $Spielbericht->get_arr_Mitspieler_team2();
    foreach( $arr_Mitspieler2 as $Mitspieler ){
      $Mitspieler->set_int_bearbeitungsnr($int_bearbeitungsnr);
      self::write_Mitspieler($Mitspieler);
    }

    return $int_bearbeitungsnr;

  }


}

?>