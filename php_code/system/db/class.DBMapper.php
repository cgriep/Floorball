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
class DBMapper extends DBM_root {


  public static function get_spieltagdatum($int_id_begegnung){
    $sql = "SELECT spieltag.datum as datum
            FROM ". VERBAND . TABLE_SPIELTAG ." spieltag,
                 ". VERBAND . TABLE_BEGEGNUNG ." begegnung
            WHERE spieltag.id_spieltag = begegnung.id_spieltag
                  AND begegnung.id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "datum");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Ereignis(Ereignis $Ereignis) {
    $sql = "INSERT INTO ". VERBAND . TABLE_EREIGNIS . "
            SET id_begegnung = '". $Ereignis->get_int_id_begegnung() ."',
                zeile        = '". $Ereignis->get_int_zeile() ."',
                nr_team1     = '". $Ereignis->get_int_nr_team1() ."',
                ass_team1    = '". $Ereignis->get_int_ass_team1() ."',
                periode      = '". $Ereignis->get_int_periode() ."',
                zeit         = '". $Ereignis->get_str_zeit() ."',
                tore_team1   = '". $Ereignis->get_int_tore_team1() ."',
                tore_team2   = '". $Ereignis->get_int_tore_team2() ."',
                id_strafe    = '". $Ereignis->get_int_id_strafe() ."',
                id_strafcode = '". $Ereignis->get_int_id_strafcode() ."',
                nr_team2     = '". $Ereignis->get_int_nr_team2() ."',
                ass_team2    = '". $Ereignis->get_int_ass_team2()."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Ereignis(Ereignis $Ereignis) {
    $sql = "UPDATE ". VERBAND . TABLE_EREIGNIS . "
            SET id_begegnung = '". $Ereignis->get_int_id_begegnung() ."',
                zeile        = '". $Ereignis->get_int_zeile ."',
                nr_team1     = '". $Ereignis->get_int_nr_team1() ."',
                ass_team1    = '". $Ereignis->get_int_ass_team1() ."',
                periode      = '". $Ereignis->get_int_periode() ."',
                zeit         = '". $Ereignis->get_str_zeit() ."',
                tore_team1   = '". $Ereignis->get_int_tore_team1() ."',
                tore_team2   = '". $Ereignis->get_int_tore_team2() ."',
                id_strafe    = '". $Ereignis->get_int_id_strafe() ."',
                id_strafcode = '". $Ereignis->get_int_id_strafcode() ."',
                nr_team2     = '". $Ereignis->get_int_nr_team2() ."',
                ass_team2    = '". $Ereignis->get_int_ass_team2() ."'
            WHERE id_ereignis = '". $Ereignis->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Ereignis($int_id_begegnung) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_EREIGNIS ."
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $num_rows = mysql_num_rows($result);
      $arr_Ereignis = array();
      for ($i=0; $i<$num_rows; $i++) {
        $Ereignis = new Ereignis();
        $Ereignis->set_int_id(mysql_result($result, $i, "id_ereignis"));
        $Ereignis->set_int_id_begegnung(mysql_result($result, $i, "id_begegnung"));
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
      return $arr_Ereignis;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function write_TransferProtokoll($id_user, $id_saison, $id_spieler, $id_verein_alt, $id_verein_neu, $id_verband)
  {
   	$sql = "INSERT INTO ". TABLE_TRANSFER_PROTOKOLL . '(id_spieler, id_verein_alt, id_verein_neu, id_saison, id_user, id_verband)'.
   	' VALUES ('.$id_spieler.','.$id_verein_alt.','.$id_verein_neu.','.$id_saison.','.$id_user.','.$id_verband.')';
            
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function write_Spielbericht(Spielbericht $Spielbericht) {
    // Ereignisse in die DB schreiben
    $arr_Ereignis = $Spielbericht->get_arr_Ereignis();
    foreach( $arr_Ereignis as $Ereignis ){
      self::write_Ereignis($Ereignis);
    }

    // Betreuer in die DB Schreiben
    $Betreuer1 = $Spielbericht->get_Betreuer_team1();
    self::write_Betreuer($Betreuer1);
    $Betreuer2 = $Spielbericht->get_Betreuer_team2();
    self::write_Betreuer($Betreuer2);

    // Mitspieler in die DB Schreiben
    $arr_Mitspieler1 = $Spielbericht->get_arr_Mitspieler_team1();
    foreach( $arr_Mitspieler1 as $Mitspieler ){
      self::write_Mitspieler($Mitspieler);
    }
    $arr_Mitspieler2 = $Spielbericht->get_arr_Mitspieler_team2();
    foreach( $arr_Mitspieler2 as $Mitspieler ){
      self::write_Mitspieler($Mitspieler);
    }

    // Spielbericht in die DB schreiben
    $sql = "INSERT INTO ". VERBAND . TABLE_SPIELBERICHT . "
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
                timeout1   = '". $Spielbericht->get_str_timeout1() ."',
                timeout2   = '". $Spielbericht->get_str_timeout2() ."',
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
      return $last_insert_id;
    } else {
      return FALSE;
    }

  }


  public static function del_Mitspieler($int_id_begegnung){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_MITSPIELER ."
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Betreuer($int_id_begegnung){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_BETREUER ."
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Ereignisse($int_id_begegnung){
    $sql = "DELETE
            FROM ". VERBAND . TABLE_EREIGNIS ."
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Spielbericht($int_id_begegnung) {
    self::del_Ereignisse($int_id_begegnung);
    self::del_Betreuer($int_id_begegnung);
    self::del_Mitspieler($int_id_begegnung);

    $sql = "DELETE FROM ". VERBAND . TABLE_SPIELBERICHT . "
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "rw");
    if($db->query_insert($sql)) {
      return TRUE;
    } else {
      return FALSE;
    }

  }


  public static function update_Spielbericht(Spielbericht $Spielbericht) {
    // Ereignisse in der DB aktualisieren
    $arr_Ereignis = $Spielbericht->get_arr_Ereignis();
    foreach ($arr_Ereignis as $Ereignis) {
      self::update_Ereignis($Ereignis);
    }

    // Betreuer in der DB aktualisieren
    $Betreuer1 = $Spielbericht->get_Betreuer_team1();
    $id_betreuer1 = self::update_Betreuer($Betreuer1);
    $Betreuer2 = $Spielbericht->get_Betreuer_team2();
    $id_betreuer2 = self::update_Betreuer($Betreuer2);

    // Mitspieler in der DB aktualisieren
    $arr_Mitspieler1 = $Spielbericht->get_arr_Mitspieler_team1();
    foreach ($arr_Mitspieler1 as $Mitspieler) {
      self::update_Mitspieler($Mitspieler);
    }
    $arr_Mitspieler2 = $Spielbericht->get_arr_Mitspieler_team2();
    foreach ($arr_Mitspieler2 as $Mitspieler) {
      self::update_Mitspieler($Mitspieler);
    }


    $sql = "UPDATE ". VERBAND . TABLE_SPIELBERICHT . "
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
                timeout1     = '". $Spielbericht->get_str_timeout1() ."',
                timeout2     = '". $Spielbericht->get_str_timeout2() ."',
                matchstrafe1 = '". $Spielbericht->get_bool_matchstrafe1() ."',
                matchstrafe2 = '". $Spielbericht->get_bool_matchstrafe2() ."',
                matchstrafe3 = '". $Spielbericht->get_bool_matchstrafe3() ."',
                bes_ereignis = '". $Spielbericht->get_bool_besonderes_ereignis() ."',
                protest      = '". $Spielbericht->get_bool_protest() ."',
                unterschrift_kaptain1 = '". $Spielbericht->get_bool_unterschrift_kaptain1() ."',
                unterschrift_kaptain2 = '". $Spielbericht->get_bool_unterschrift_kaptain2() ."',
                verlaengerung     = '". $Spielbericht->get_bool_verlaengerung() ."',
                kommentar         = '". $Spielbericht->get_str_kommentar() ."'
            WHERE id_spielbericht = '". $Spielbericht->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  /**
   * DBMapper::read_Spielbericht()
   *
   * Lädt einen Spielbericht aus der DB anhand von einer Begegnungs-ID
   *
   * @param int $int_id ID der Begegnung
   * @return mixed FALSE wenn was schief geht, ansonsten einen Spielberciht
   */
  public static function read_Spielbericht($int_id_begegnung) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_SPIELBERICHT ."
            WHERE id_begegnung = '$int_id_begegnung'";
    $db = self::db_connect(DB_NAME, "r");
    $result = $db->query($sql);
    if ($result === FALSE) {
      // Fehlerbehandlung
      return FALSE;
    } else if ($result === NULL) {
      // Leeren Spielbericht zurückgeben
      return FALSE;//new Spielbericht();
    } else {
      // Spielbericht füllen mit Daten
      $Spielbericht = new Spielbericht();
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

      //$int_id_begegnung = $Spielbericht->get_int_id_begegnung();

      // Betreuer setzen
      $Betreuer_team1 = self::read_Betreuer($int_id_begegnung, 1);
      $Spielbericht->set_Betreuer_team1($Betreuer_team1);
      $Betreuer_team2 = self::read_Betreuer($int_id_begegnung, 2);
      $Spielbericht->set_Betreuer_team2($Betreuer_team2);

      // Mitspieler setzen
      $arr_Mitspieler = self::read_Mitspieler($int_id_begegnung, 1);
      $Spielbericht->set_arr_Mitspieler_team1($arr_Mitspieler);
      $arr_Mitspieler = self::read_Mitspieler($int_id_begegnung, 2);
      $Spielbericht->set_arr_Mitspieler_team2($arr_Mitspieler);

      // Ereignisse setzen
      $arr_Ereignis = self::read_Ereignis($int_id_begegnung);
      $Spielbericht->set_arr_Ereignis($arr_Ereignis);

      return $Spielbericht;
    }
  }


  public static function get_strafcode($int_id_strafcode) {
    $sql = "SELECT code
            FROM ". TABLE_STRAFCODE ."
            WHERE id_strafcode = $int_id_strafcode";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $int_strafcode = mysql_result($result, 0, "code");
      return $int_strafcode;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_strafcode($bool_aktiv = FALSE) {
    if ($bool_aktiv) {
      $sql = "SELECT id_strafcode, code, beschreibung
              FROM ". TABLE_STRAFCODE ."
              WHERE aktiv = '1'
              ORDER BY code ASC";
    } else {
      $sql = "SELECT id_strafcode, code, beschreibung
              FROM ". TABLE_STRAFCODE ."
              ORDER BY code ASC";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_strafe = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_strafe[0][$i] = $arr_row[0];
        $arr_list_strafe[1][$i] = $arr_row[1];
        $arr_list_strafe[2][$i] = $arr_row[2];
        $i++;
      }
      return $arr_list_strafe;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_strafe($int_id_strafe){
    $sql = "SELECT name
            FROM ". TABLE_STRAFE ."
            WHERE id_strafe = $int_id_strafe";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $int_strafe = mysql_result($result, 0, "name");
      return $int_strafe;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_strafe(){
    $sql = "SELECT id_strafe, name, beschreibung
            FROM ". TABLE_STRAFE ."
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_strafe = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_strafe[0][$i] = $arr_row[0];
        $arr_list_strafe[1][$i] = $arr_row[1];
        $arr_list_strafe[2][$i] = $arr_row[2];
        $i++;
      }
      return $arr_list_strafe;
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
    $sql = "INSERT INTO ". VERBAND . TABLE_MITSPIELER . "
            SET id_begegnung = '". $Mitspieler->get_int_id_begegnung() ."',
                id_spieler   = ". self::add_quotes($int_id_spieler) .",
                trikotnr     = '". $Mitspieler->get_int_trikotnr() ."',
                torwart      = '". $Mitspieler->get_bool_torwart() ."',
                kapitain     = '". $Mitspieler->get_bool_kapitain() ."',
                team         = '". $Mitspieler->get_int_team() ."',
                name         = '". $Mitspieler->get_str_name() ."',
                vorname      = '". $Mitspieler->get_str_vorname() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Mitspieler(Mitspieler $Mitspieler) {
    if ($Mitspieler->get_int_id_spieler() < 0) {
      $int_id_spieler = NULL;
    } else {
      $int_id_spieler = $Mitspieler->get_int_id_spieler();
    }
    $this->add_quotes($Mitspieler->get_int_id_spieler());
    $sql = "UPDATE ". VERBAND . TABLE_MITSPIELER . "
            SET id_begegnung = '". $Mitspieler->get_int_id_begegnung() ."',
                id_spieler   = ". self::add_quotes($int_id_spieler) .",
                trikotnr     = '". $Mitspieler->get_int_trikotnr() ."',
                torwart      = '". $Mitspieler->get_bool_torwart() ."',
                kapitain     = '". $Mitspieler->get_bool_kapitain() ."',
                team         = '". $Mitspieler->get_int_team() ."',
                name         = '". $Mitspieler->get_str_name() ."',
                vorname      = '". $Mitspieler->get_str_vorname() ."'
            WHERE id_mitspieler = '". $Mitspieler->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Mitspieler($int_id_begegnung, $int_team) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_MITSPIELER ."
            WHERE id_begegnung = '$int_id_begegnung'
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
        $int_id_spieler = mysql_result($result, $i, "id_spieler");
        if ($int_id_spieler === NULL) {
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
      return $arr_Mitspieler;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Betreuer(Betreuer $Betreuer) {
    $sql = "INSERT INTO ". VERBAND . TABLE_BETREUER . "
            SET id_begegnung = '". $Betreuer->get_int_id_begegnung() ."',
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


  public static function update_Betreuer(Betreuer $Betreuer) {
    $sql = "UPDATE ". VERBAND . TABLE_BETREUER . "
            SET id_begegnung = '". $Betreuer->get_int_id_begegnung() ."',
                betreuer1    = '". $Betreuer->get_str_betreuer1() ."',
                betreuer2    = '". $Betreuer->get_str_betreuer2() ."',
                betreuer3    = '". $Betreuer->get_str_betreuer3() ."',
                betreuer4    = '". $Betreuer->get_str_betreuer4() ."',
                betreuer5    = '". $Betreuer->get_str_betreuer5() ."',
                betreuer1_unterschrift = '". $Betreuer->get_bool_unterschrift() ."',
                team         = '". $Betreuer->get_int_team() ."'
            WHERE id_betreuer = '". $Betreuer->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Betreuer($int_id_begegnung, $int_team) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_BETREUER ."
            WHERE id_begegnung = '$int_id_begegnung'
                  AND team = '$int_team'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Betreuer = new Betreuer();
      $Betreuer->set_int_id(mysql_result($result, 0, "id_betreuer"));
      $Betreuer->set_int_id_begegnung(mysql_result($result, 0, "id_begegnung"));
      $Betreuer->set_str_betreuer1(mysql_result($result, 0, "betreuer1"));
      $Betreuer->set_str_betreuer2(mysql_result($result, 0, "betreuer2"));
      $Betreuer->set_str_betreuer3(mysql_result($result, 0, "betreuer3"));
      $Betreuer->set_str_betreuer4(mysql_result($result, 0, "betreuer4"));
      $Betreuer->set_str_betreuer5(mysql_result($result, 0, "betreuer5"));
      $Betreuer->set_bool_unterschrift(mysql_result($result, 0, "betreuer1_unterschrift"));
      $Betreuer->set_int_team($int_team);
      return $Betreuer;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_Spielort_by_begegnung($int_id_begegnung){
    //baustelle

    $sql = "SELECT spieltag.id_spielort as id_spielort
            FROM ". VERBAND . TABLE_SPIELTAG ." spieltag,
                 ". VERBAND . TABLE_BEGEGNUNG ." begegnung
            WHERE spieltag.id_spieltag = begegnung.id_spieltag
                  AND begegnung.id_begegnung = $int_id_begegnung";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $id_spielort = mysql_result($result, 0, "id_spielort");
      $sql = "SELECT name
              FROM ". TABLE_SPIELORT ."
              WHERE id_spielort = $id_spielort";
      $db = self::db_connect(DB_NAME, "r");
      if ($result = $db->query($sql)) {
        return mysql_result($result, 0, "name");
      } else {
        return FALSE;
      }
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_schiriname($int_id_schiri){
    $sql = "SELECT name
            FROM ". VERBAND . TABLE_SCHIEDSRICHTER ."
            WHERE id_schiedsrichter = $int_id_schiri";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Verein(Verein $Verein) {
    $sql = "INSERT INTO ". TABLE_VEREIN . "
            SET id_spartenleiter = '". $Verein->get_int_id_spartenleiter() ."',
                name             = '". $Verein->get_str_name() ."',
                kurzname         = '". $Verein->get_str_kurzname() ."',
                strasse          = '". $Verein->get_str_strasse() ."',
                hausnummer       = '". $Verein->get_str_hausnummer() ."',
                plz              = '". $Verein->get_str_plz() ."',
                ort              = '". $Verein->get_str_ort() ."',
                homepage_verein  = '". $Verein->get_str_homepage_verein() ."',
                homepage_sparte  = '". $Verein->get_str_homepage_sparte() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      // Verknüpfung zwischen Verband und Verein erstellen mit Verband
      // als heimverband
      $sql = "INSERT INTO ". TABLE_VEREIN_VERBAND . "
              SET id_verein      = '". $last_insert_id ."',
                id_verband       = '". DBMapper::get_id_Verband() ."',
                heimatverband    = '1'";
      $db = self::db_connect(DB_NAME, "rw");
      $db->query_insert($sql);
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_id_Verband($str_pfad = VERBAND) {
    $sql = "SELECT id_verband
            FROM ". TABLE_VERBAND ."
            WHERE pfad = '$str_pfad'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "id_verband");
    }
    return FALSE;
  }

  public static function update_Verein(Verein $Verein) {
    $sql = "UPDATE  ". TABLE_VEREIN . "
            SET id_spartenleiter = '". $Verein->get_int_id_spartenleiter() ."',
                name             = '". $Verein->get_str_name() ."',
                kurzname         = '". $Verein->get_str_kurzname() ."',
                strasse          = '". $Verein->get_str_strasse() ."',
                hausnummer       = '". $Verein->get_str_hausnummer() ."',
                plz              = '". $Verein->get_str_plz() ."',
                ort              = '". $Verein->get_str_ort() ."',
                homepage_verein  = '". $Verein->get_str_homepage_verein() ."',
                homepage_sparte  = '". $Verein->get_str_homepage_sparte() ."'
            WHERE id_verein = '". $Verein->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Verein($int_id) {
    $sql = "SELECT *
            FROM ". TABLE_VEREIN ."
            WHERE id_verein = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Verein = new Verein();
      $Verein->set_int_id(mysql_result($result, 0, "id_verein"));
      $Verein->set_int_id_spartenleiter(mysql_result($result, 0, "id_spartenleiter"));
      $Verein->set_str_name(mysql_result($result, 0, "name"));
      $Verein->set_str_kurzname(mysql_result($result, 0, "kurzname"));
      $Verein->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Verein->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Verein->set_str_plz(mysql_result($result, 0, "plz"));
      $Verein->set_str_ort(mysql_result($result, 0, "ort"));
      $Verein->set_str_homepage_verein(mysql_result($result, 0, "homepage_verein"));
      $Verein->set_str_homepage_sparte(mysql_result($result, 0, "homepage_sparte"));
      return $Verein;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function delete_Verein($int_id) {
    // Prüfen ob der Verein wirklich gelöscht werden kann
    if(DBM_Spieler::get_count_Spieler_of_Verein($int_id)) {
      return FALSE;
    }
    if(DBMapper::get_count_Schiedsrichter($int_id)) {
      return FALSE;
    }
    if(DBMapper::get_count_Mannschaft($int_id)) {
      return FALSE;
    }
    if(DBMapper::get_count_SG($int_id)) {
      return FALSE;
    }
    if(DBMapper::get_count_Spielort($int_id)) {
      return FALSE;
    }
    $arr_gast = DBMapper::get_list_Gastverbaende($int_id);
    if(!$arr_gast) return FALSE;
    for($i=0; $i<count($arr_gast[0]); $i++) {
      $count_gast = DBMapper::check_Gastverband_Mannschaft($int_id,
                                                           $arr_gast[0][$i]);
      if($count_gast != 0) {
        return FALSE;
      }
    }
    // DB Verbindung aufbauen
    $db = self::db_connect(DB_NAME, "rw");

    // Löschen der Verbindung zwischen Verband und Verein
    $sql = "DELETE FROM ". TABLE_VEREIN_VERBAND ."
            WHERE id_verein = $int_id";
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      //$my_message->add_error("Verein-Verband Link konnte nicht aus der Datenbank gelöscht werden. Bitte den Fehler an die System-Admins weitergeben!");
      return FALSE;
    }
    // Liga kann gelöscht werden
    $sql = "DELETE FROM ". TABLE_VEREIN ."
            WHERE id_verein = $int_id";
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      //$my_message->add_error("Verein konnte nicht aus der Datenbank gelöscht werden. Bitte den Fehler an die System-Admins weitergeben!");
      return FALSE;
    }

    // zuletzt bei allen Personen die dem Verein zugeordnet sind die id_verein
    // auf null setzen
    DBMapper::delete_id_verein_of_Person($int_id);
    return TRUE;
  }


  public static function get_list_Verein() {
    $sql = "SELECT verein.id_verein, verein.kurzname, verein.name
            FROM ". TABLE_VEREIN ." verein,
                 ". TABLE_VEREIN_VERBAND ." verband
            WHERE verein.id_verein = verband.id_verein
                  AND verband.id_verband = ". DBMapper::get_id_Verband() ."
            ORDER BY kurzname ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_verein[0] = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
      $arr_list_verein[0][$i] = $arr_row[0];
      $arr_list_verein[1][$i] = $arr_row[1];
      $arr_list_verein[2][$i] = $arr_row[2];
      $i++;
      }
//   $arr_list_verein = mysql_fetch_array($result, MYSQL_ASSOC);
      return $arr_list_verein;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Verein_all() {
    $sql = "SELECT id_verein, kurzname, name
            FROM ". TABLE_VEREIN ."
            WHERE 1
            ORDER BY kurzname ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_verein[0] = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
      $arr_list_verein[0][$i] = $arr_row[0];
      $arr_list_verein[1][$i] = $arr_row[1];
      $arr_list_verein[2][$i] = $arr_row[2];
      $i++;
      }
//   $arr_list_verein = mysql_fetch_array($result, MYSQL_ASSOC);
      return $arr_list_verein;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function check_Heimatverband($id_verein) {
    $sql = "SELECT heimatverband FROM ". TABLE_VEREIN_VERBAND ."
            WHERE id_verein = $id_verein
                  AND id_verband = ". DBMapper::get_id_Verband();
    $db = self::db_connect(DB_NAME, "r");
    if($result = $db->query($sql)) {
      return mysql_result($result, 0, "heimatverband");
    }
    else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Gastverbaende($id_verein) {
    $sql = "SELECT verband.id_verband, verband.name, verband.kuerzel
            FROM ". TABLE_VERBAND ." verband,
                 ". TABLE_VEREIN_VERBAND ." verein_verband
            WHERE verein_verband.id_verband = verband.id_verband
                  AND verein_verband.id_verein = ". $id_verein ."
                  AND verein_verband.heimatverband = 0";
    $db = self::db_connect(DB_NAME, "r");

    $arr_list_verband[0] = array();
    if ($result = $db->query($sql)) {
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
      $arr_list_verband[0][$i] = $arr_row[0];
      $arr_list_verband[1][$i] = $arr_row[1];
      $arr_list_verband[2][$i] = $arr_row[2];
      $i++;
      }
//   $arr_list_verein = mysql_fetch_array($result, MYSQL_ASSOC);
      return $arr_list_verband;
    } else {
      // Fehlermeldung generieren
      if($result === NULL) return $arr_list_verband;
      return FALSE;
    }
  }

  public static function write_Person(Person $Person) {
    $sql = "INSERT INTO ". VERBAND . TABLE_PERSON . "
            SET id_verein   = '". $Person->get_int_id_verein() ."',
                vorname     = '". $Person->get_str_vorname() ."',
                name        = '". $Person->get_str_name() ."',
                strasse     = '". $Person->get_str_strasse() ."',
                hausnummer  = '". $Person->get_str_hausnummer() ."',
                plz         = '". $Person->get_str_plz() ."',
                ort         = '". $Person->get_str_ort() ."',
                telefon     = '". $Person->get_str_telefon() ."',
                handy       = '". $Person->get_str_handy() ."',
                email       = '". $Person->get_str_email() ."',
                geb_datum   = '". $Person->get_date_geb_datum(TRUE) ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Person(Person $Person) {
    $sql = "UPDATE  ". VERBAND . TABLE_PERSON . "
            SET id_verein   = '". $Person->get_int_id_verein() ."',
                vorname     = '". $Person->get_str_vorname() ."',
                name        = '". $Person->get_str_name() ."',
                strasse     = '". $Person->get_str_strasse() ."',
                hausnummer  = '". $Person->get_str_hausnummer() ."',
                plz         = '". $Person->get_str_plz() ."',
                ort         = '". $Person->get_str_ort() ."',
                telefon     = '". $Person->get_str_telefon() ."',
                handy       = '". $Person->get_str_handy() ."',
                email       = '". $Person->get_str_email() ."',
                geb_datum   = '". $Person->get_date_geb_datum(TRUE) ."'
            WHERE id_person = '". $Person->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Person($int_id) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_PERSON ."
            WHERE id_person = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
      $Person = new Person();
    if ($result = $db->query($sql)) {
      $Person->set_int_id(mysql_result($result, 0, "id_person"));
      $Person->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Person->set_str_vorname(mysql_result($result, 0, "vorname"));
      $Person->set_str_name(mysql_result($result, 0, "name"));
      $Person->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Person->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Person->set_str_plz(mysql_result($result, 0, "plz"));
      $Person->set_str_ort(mysql_result($result, 0, "ort"));
      $Person->set_str_telefon(mysql_result($result, 0, "telefon"));
      $Person->set_str_handy(mysql_result($result, 0, "handy"));
      $Person->set_str_email(mysql_result($result, 0, "email"));
      $Person->set_date_geb_datum(mysql_result($result, 0, "geb_datum"));
      return $Person;
    } else {
      // Fehlermeldung generieren
      return $Person;
    }
  }


  public static function get_list_Person($id_verein = -1) {
    if ($id_verein == -1) {
      $sql = "SELECT id_person, name, vorname
              FROM ". VERBAND . TABLE_PERSON ."
              ORDER BY name ASC";
    } else {
      $sql = "SELECT id_person, name, vorname
              FROM ". VERBAND . TABLE_PERSON ."
              WHERE id_verein = $id_verein
              ORDER BY name ASC";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_person = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_person[0][$i] = $arr_row[0];
        $arr_list_person[1][$i] = $arr_row[1].", ".$arr_row[2];
        $i++;
      }
      //$arr_list_person = mysql_fetch_array($result, MYSQL_ASSOC);
      return $arr_list_person;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function delete_id_verein_of_Person($int_id) {
    $sql = "UPDATE  ". VERBAND . TABLE_PERSON . "
            SET id_verein   = '0'
            WHERE id_verein = '$int_id'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Klasse() {
    $sql = "SELECT id_klasse, name
            FROM ". TABLE_KLASSE ."
            ORDER BY id_klasse ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_klasse[0][$i] = $arr_row[0];
        $arr_list_klasse[1][$i] = $arr_row[1];
        $i++;
      }
      return $arr_list_klasse;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Kategorie() {
    $sql = "SELECT id_kategorie, name
            FROM ". TABLE_KATEGORIE;
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
      $arr_list_kategorie[0][$i] = $arr_row[0];
      $arr_list_kategorie[1][$i] = $arr_row[1];
      $i++;
      }return $arr_list_kategorie;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function read_kategorie($id_kategorie) {
    $sql = "SELECT name
            FROM ". TABLE_KATEGORIE ."
            WHERE id_kategorie = $id_kategorie";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function read_klasse($id_klasse) {
    $sql = "SELECT name
            FROM ". TABLE_KLASSE ."
            WHERE id_klasse = $id_klasse";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_rel_spieler_verein($int_id_spieler, $int_id_verein){
    $int_id_spieler = add_quotes($int_id_spieler);
    $int_id_verein  = add_quotes($int_id_verein);
    $sql = "INSERT INTO ". TABLE_VEREIN_SPIELER . "
            SET id_spieler = $int_id_spieler ,
                id_verein  = $int_id_verein";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_rel_spieler_verein($int_id_spieler, $int_id_verein){
    $int_id_spieler = add_quotes($int_id_spieler);
    $int_id_verein  = add_quotes($int_id_verein);
    $sql = "DELETE FROM ". TABLE_VEREIN_SPIELER . "
            WHERE id_spieler = $int_id_spieler
                  AND id_verein = $int_id_verein";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Lizenz($int_id_spieler, $int_id_mannschaft,
                                      $int_id_klasse, $int_id_kategorie,
                                      $weiblich, $int_id_status_lizenz){
    $int_id_spieler     = self::add_quotes($int_id_spieler);
    $int_id_mannschaft  = self::add_quotes($int_id_mannschaft);
    $int_id_klasse  = self::add_quotes($int_id_klasse);
    $int_id_kategorie  = self::add_quotes($int_id_kategorie);
    $weiblich  = self::add_quotes($weiblich);
    $int_id_status_lizenz  = self::add_quotes($int_id_status_lizenz);

    //$int_id_status_lizenz = self::add_quotes($int_id_status_lizenz);
    $sql = "INSERT INTO ". VERBAND . TABLE_LIZENZ . "
            SET id_spieler    = $int_id_spieler ,
                id_mannschaft = $int_id_mannschaft,
                id_klasse     = $int_id_klasse,
                id_kategorie  = $int_id_kategorie,
                weiblich      = $weiblich";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      // jetzt den ersten Status schreiben
      $id_lizenz = self::add_quotes($last_insert_id);
      $id_user = self::add_quotes($_SESSION['arr_login']['id_benutzer']);
      $sql = "INSERT INTO ". VERBAND . TABLE_LIZENZVERLAUF ."
              SET id_lizenz       = $id_lizenz,
                  id_lizenzstatus = $int_id_status_lizenz,
                  id_benutzer     = $id_user";
      $db->query_insert($sql);
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Lizenz($int_id_spieler, $int_id_mannschaft, $int_id_status_lizenz){
    $int_id_spieler     = self::add_quotes($int_id_spieler);
    $int_id_mannschaft  = self::add_quotes($int_id_mannschaft);
    $int_id_status_lizenz = self::add_quotes($int_id_status_lizenz);

    $arr_lizenz = DBM_Spieler::get_last_Spieler_Lizenzstatus($int_id_spieler, $int_id_mannschaft);
    if($arr_lizenz[0] != $int_id_status_lizenz) {
      $db = self::db_connect(DB_NAME, "rw");
      $id_user = self::add_quotes($_SESSION['arr_login']['id_benutzer']);
      $sql = "INSERT INTO ". VERBAND . TABLE_LIZENZVERLAUF ."
              SET id_lizenz       = ".$arr_lizenz[2].",
                  id_lizenzstatus = ".$int_id_status_lizenz.",
                  id_benutzer     = $id_user";
      $db->query_insert($sql);
      return TRUE;
    }
    else return TRUE;
  }


  public static function del_Lizenz($id_lizenz, $id_spieler){
    $id_lizenz = self::add_quotes($id_lizenz);
    $sql = "DELETE FROM ". VERBAND . TABLE_LIZENZVERLAUF . "
            WHERE id_lizenz = $id_lizenz";
    $sql2 = "DELETE FROM ". VERBAND . TABLE_LIZENZ . "
             WHERE id_lizenz = $id_lizenz";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      if ($db->query($sql2)) {
        $count = DBMapper::get_count_Doppellizenz($id_spieler);
        if ($count == 2) {
          if (DBMapper::delete_Doppellizenz($id_spieler)) {
            return TRUE;
          } else {
            return FALSE;
          }
        }
        else if ($count > 2) {
          if (DBMapper::delete_Doppellizenz($id_spieler, $id_lizenz)) {
            return TRUE;
          } else {
            return FALSE;
          }
        }
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Lizenz_of_Spieler($int_id_spieler) {
    // hier müssen wir über alle verbände suchen
    $int_id_spieler = self::add_quotes($int_id_spieler);
    $arr_verband = DBMapper::get_list_Verband();
    $arr_list_lizenz[0] = array();

    $my_db = self::db_connect(DB_NAME, "r");

    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT id_lizenz, id_mannschaft, id_klasse, id_kategorie, weiblich
            FROM ". $verband . TABLE_LIZENZ ."
            WHERE id_spieler = $int_id_spieler";
      if ($result = $my_db->query($sql)) {
        while ($arr_row = mysql_fetch_row($result)) {
          $arr_list_lizenz[0][] = $arr_row[0]; // id_lizenz
          $arr_list_lizenz[1][] = $arr_row[1]; // id_mannschaft
          $arr_list_lizenz[2][] = $arr_row[2]; // id_klasse
          $arr_list_lizenz[3][] = $arr_row[3]; // id_kategorie
          $arr_list_lizenz[4][] = $arr_row[4]; // weiblich
          $arr_list_lizenz[5][] = $arr_verband[0][$i]; // id_verband
        }
      }
    }
    return $arr_list_lizenz;
  }


  public static function del_Lizenz_for_Mannschaft($int_id_mannschaft){
    $int_id_mannschaft  = self::add_quotes($int_id_mannschaft);
    $sql = "DELETE FROM ". VERBAND . TABLE_LIZENZ . "
            WHERE id_mannschaft = $int_id_mannschaft";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Lizenz($int_id) {
    $sql = "SELECT count(*) FROM ". VERBAND . TABLE_LIZENZ ."
            WHERE id_mannschaft = $int_id";
    $db = self::db_connect(DB_NAME, "r");
    if($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_count_Lizenz_by_Klasse($id_spieler, $id_klasse,
                                                    $id_kategorie,
                                                    $weiblich = 0, $filter_state = 1) {
    $arr_verband = DBMapper::get_list_Verband();
    // muss über alle verbände suchen
    if ($weiblich) $w = 1;
    else $w = 0;
    $sum = 0;
    $db = self::db_connect(DB_NAME, "r");
    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT id_mannschaft
            FROM ". $verband . TABLE_LIZENZ ."
            WHERE id_spieler = $id_spieler
            AND   id_klasse = $id_klasse
            AND   id_kategorie = $id_kategorie
            AND   weiblich = $w";
      if ($result = $db->query($sql)) {
        while ($arr_row = mysql_fetch_row($result)) {
          if ($filter_state) {
            $arr_lizenz = DBM_Spieler::get_last_Spieler_Lizenzstatus($id_spieler, $arr_row[0]);
            if ($arr_lizenz[0] < 6) $sum += 1;
          } else {
            $sum += 1;
          }
        }
      }
    }
    return $sum;
  }

  public static function get_list_Lizenz_of_Mannschaft($id_mannschaft) {
    $sql = "SELECT id_lizenz, id_spieler
            FROM ". VERBAND . TABLE_LIZENZ ." WHERE id_mannschaft = $id_mannschaft";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_result[0] = array();
      $arr_result[1] = array();
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_result[0][] = $arr_row[0];
        $arr_result[1][] = $arr_row[1];
      }
      return $arr_result;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Lizenz_of_Liga($int_id) {
    $sql = "SELECT count(*) FROM ". VERBAND . TABLE_LIZENZ ." l,
                                 ". VERBAND . TABLE_MANNSCHAFT ." m
            WHERE m.id_liga = $int_id AND l.id_mannschaft = m.id_mannschaft";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function write_Mannschaft(Mannschaft $Mannschaft, $int_id_verband = NULL) {
    $sql = "INSERT INTO ". VERBAND . TABLE_MANNSCHAFT . "
            SET id_verein    = '". $Mannschaft->get_int_id_verein() ."',
                id_liga      = '". $Mannschaft->get_int_id_liga() ."',
                id_betreuer  = '". $Mannschaft->get_int_id_betreuer() ."',
                name         = '". $Mannschaft->get_str_name() ."',
                kurzname     = '". $Mannschaft->get_str_kurzname() ."',
                genehmigt    = '". $Mannschaft->get_bool_genehmigt() ."',
                angelegt_von = '". $_SESSION['arr_login']['id_benutzer'] ."',
                sg           = '". $Mannschaft->get_bool_sg() ."'";
    if ($int_id_verband != NULL) {
      $db = self::db_connect(DB_NAME, "rw", $int_id_verband);
    } else {
      $db = self::db_connect(DB_NAME, "rw");
    }
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Mannschaft(Mannschaft $Mannschaft, $int_id_verband = NULL) {
    $sql = "UPDATE  ". VERBAND . TABLE_MANNSCHAFT . "
            SET id_verein    = '". $Mannschaft->get_int_id_verein() ."',
                id_liga      = '". $Mannschaft->get_int_id_liga() ."',
                id_betreuer  = '". $Mannschaft->get_int_id_betreuer() ."',
                name         = '". $Mannschaft->get_str_name() ."',
                kurzname     = '". $Mannschaft->get_str_kurzname() ."',
                genehmigt    = '". $Mannschaft->get_bool_genehmigt() ."',
                angelegt_von = '". $Mannschaft->get_int_angelegt_von() ."',
                sg           = '". $Mannschaft->get_bool_sg() ."'
            WHERE id_mannschaft = '". $Mannschaft->get_int_id() ."'";
    if ($int_id_verband != NULL) {
      $db = self::db_connect(DB_NAME, "rw", $int_id_verband);
    } else {
      $db = self::db_connect(DB_NAME, "rw");
    }
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Mannschaft($int_id, $int_id_verband = NULL) {
    if ($int_id_verband != NULL
        && $int_id_verband != 0) {
      $verband = self::get_Pfad_of_Verband($int_id_verband);
      $sql = "SELECT *
            FROM ". $verband . TABLE_MANNSCHAFT ."
            WHERE id_mannschaft = '$int_id'";
    } else {
      $sql = "SELECT *
            FROM ". VERBAND . TABLE_MANNSCHAFT ."
            WHERE id_mannschaft = '$int_id'";
    }
    if ($int_id_verband != NULL) {
      $db = self::db_connect(DB_NAME, "r", $int_id_verband);
    } else {
      $db = self::db_connect(DB_NAME, "r");
    }
    if ($result = $db->query($sql)) {
      $Mannschaft = new Mannschaft();
      $Mannschaft->set_int_id(mysql_result($result, 0, "id_mannschaft"));
      $Mannschaft->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Mannschaft->set_int_id_liga(mysql_result($result, 0, "id_liga"));
      $Mannschaft->set_int_id_betreuer(mysql_result($result, 0, "id_betreuer"));
      $Mannschaft->set_str_name(mysql_result($result, 0, "name"));
      $Mannschaft->set_str_kurzname(mysql_result($result, 0, "kurzname"));
      $Mannschaft->set_bool_genehmigt(mysql_result($result, 0, "genehmigt"));
      $Mannschaft->set_int_angelegt_von(mysql_result($result, 0, "angelegt_von"));
      $Mannschaft->set_date_angelegt_am(mysql_result($result, 0, "angelegt_am"));
      $Mannschaft->set_bool_sg(mysql_result($result, 0, "sg"));
      mysql_free_result($result);
      return $Mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Mannschaft($id_verein = 0, $filter = 0) {
    $sql = "SELECT id_mannschaft, name, id_liga
            FROM ". VERBAND . TABLE_MANNSCHAFT;
    if ($id_verein) {
      $sql .= "
            WHERE id_verein = $id_verein";
      if ($filter) {
        $sql .= "
            AND genehmigt < 2";
      }
    }
    $sql .= "
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_mannschaft = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_mannschaft[0][$i] = $arr_row[0];
        $arr_list_mannschaft[1][$i] = $arr_row[1];
        $arr_list_mannschaft[2][$i] = $arr_row[2];
        $i++;
      }
      return $arr_list_mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Mannschaft_SG($id_verein) {
    $sql = "SELECT m.id_mannschaft, m.name, m.id_liga
            FROM ". VERBAND . TABLE_MANNSCHAFT." m,
                 ". VERBAND . TABLE_SG." sg
                 WHERE sg.id_verein = $id_verein
                 AND m.id_verein != $id_verein
                 AND m.id_mannschaft = sg.id_mannschaft
                 ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_mannschaft[0] = array();
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_mannschaft[0][] = $arr_row[0];
        $arr_list_mannschaft[1][] = $arr_row[1];
        $arr_list_mannschaft[2][] = $arr_row[2];
      }
      return $arr_list_mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Mannschaft($id_verein = 0) {
    $sql = "SELECT count(*)
            FROM ". VERBAND . TABLE_MANNSCHAFT;
    if($id_verein) {
      $sql .= "
            WHERE id_verein = $id_verein";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }


  public static function get_list_Mannschaft_of_Liga($id_liga, $filter = 0) {
    $sql = "SELECT id_mannschaft, name, id_verein
            FROM ". VERBAND . TABLE_MANNSCHAFT."
            WHERE id_liga = $id_liga";
    if($filter) $sql .= "
            AND genehmigt < 2";
    $sql .= "
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_mannschaft = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_mannschaft[0][$i] = $arr_row[0];
        $arr_list_mannschaft[1][$i] = $arr_row[1];
        $arr_list_mannschaft[2][$i] = 0;
        $arr_list_mannschaft[3][$i] = $arr_row[2];
      $i++;
      }
      return $arr_list_mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function delete_Mannschaft($int_id) {
    // Prüfen ob Mannschaft wirklich gelöscht werden kann
    if (DBMapper::get_count_Lizenz($int_id)) {
      return FALSE;
    }
    if (DBMapper::get_count_Begegnung($int_id)) {
      return FALSE;
    }
    // DB Verbindung aufbauen
    $db = self::db_connect(DB_NAME, "rw");
    // Löschen der Mannschaft
    $sql = "DELETE FROM ". VERBAND . TABLE_MANNSCHAFT ." WHERE id_mannschaft = $int_id";
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      return FALSE;
    }
    // und noch die Spielgemeinschaften löschen
    $sql = "DELETE FROM ". TABLE_SG ." WHERE id_mannschaft = $int_id";
    if (!$db->query($sql)) {
      // Fehlermeldung generieren
      return FALSE;
    }
    return TRUE;
  }

  public static function delete_Verein_SG($id_mannschaft, $id_verein) {
    $db = self::db_connect(DB_NAME, "rw");
    $sql = "DELETE FROM ". VERBAND . TABLE_SG ." WHERE id_mannschaft = $id_mannschaft
                                             AND id_verein = $id_verein";
    if(!$db->query($sql)) {
      // Fehlermeldung generieren
      return FALSE;
    }
    return TRUE;
  }

  public static function get_list_Verein_of_SG($id_mannschaft) {
    $sql = "SELECT id_verein
            FROM ". VERBAND . TABLE_SG."
            WHERE id_mannschaft = $id_mannschaft";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_mannschaft = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_mannschaft[$i] = $arr_row[0];
        $i++;
      }
      return $arr_list_mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  static function get_list_SG() {
    $sql = "SELECT id_mannschaft, name
            FROM ". VERBAND . TABLE_MANNSCHAFT."
            WHERE sg = 1";
    $sql .= "
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_mannschaft = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_mannschaft[0][$i] = $arr_row[0];
        $arr_list_mannschaft[1][$i] = $arr_row[1];
        $i++;
      }
      return $arr_list_mannschaft;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  static function get_count_SG($int_id_verein = 0) {
    if ($int_id_verein) {
      $sql = "SELECT count(*)
              FROM ". VERBAND . TABLE_SG."
              WHERE id_verein = $int_id_verein";
    }
    else {
      $sql = "SELECT count(*)
              FROM ". VERBAND . TABLE_SG."
              WHERE 1";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  static function get_count_SG_of_Mannschaft($int_id = 0) {
    $sql = "SELECT count(*)
            FROM ". VERBAND . TABLE_SG."
            WHERE id_mannschaft = $int_id";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function write_SG($id_verein, $id_mannschaft) {
    $sql = "INSERT INTO ". VERBAND . TABLE_SG . "
            SET id_verein    = $id_verein,
                id_mannschaft = $id_mannschaft";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function write_Spielort(Spielort $Spielort) {
    $sql = "INSERT INTO ". TABLE_SPIELORT . "
            SET id_verein     = '". $Spielort->get_int_id_verein() ."',
                name          = '". $Spielort->get_str_name() ."',
                strasse       = '". $Spielort->get_str_strasse() ."',
                hausnummer    = '". $Spielort->get_str_hausnummer() ."',
                plz           = '". $Spielort->get_str_plz() ."',
                ort           = '". $Spielort->get_str_ort() ."',
                anfahrt_pkw   = '". $Spielort->get_str_anfahrt_pkw() ."',
                anfahrt_oepnv = '". $Spielort->get_str_anfahrt_oepnv() ."',
                zuschauer     = '". $Spielort->get_str_zuschauer() ."',
                kommentar     = '". $Spielort->get_str_kommentar() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Spielort(Spielort $Spielort) {
    $sql = "UPDATE  ". TABLE_SPIELORT . "
            SET id_verein     = '". $Spielort->get_int_id_verein() ."',
                name          = '". $Spielort->get_str_name() ."',
                strasse       = '". $Spielort->get_str_strasse() ."',
                hausnummer    = '". $Spielort->get_str_hausnummer() ."',
                plz           = '". $Spielort->get_str_plz() ."',
                ort           = '". $Spielort->get_str_ort() ."',
                anfahrt_pkw   = '". $Spielort->get_str_anfahrt_pkw() ."',
                anfahrt_oepnv = '". $Spielort->get_str_anfahrt_oepnv() ."',
                zuschauer     = '". $Spielort->get_str_zuschauer() ."',
                kommentar     = '". $Spielort->get_str_kommentar() ."'
            WHERE id_spielort = '". $Spielort->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Spielort($int_id) {
    $sql = "SELECT *
            FROM ". TABLE_SPIELORT ."
            WHERE id_spielort = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Spielort = new Spielort();
      $Spielort->set_int_id(mysql_result($result, 0, "id_spielort"));
      $Spielort->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Spielort->set_str_name(mysql_result($result, 0, "name"));
      $Spielort->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Spielort->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Spielort->set_str_plz(mysql_result($result, 0, "plz"));
      $Spielort->set_str_ort(mysql_result($result, 0, "ort"));
      $Spielort->set_str_anfahrt_pkw(mysql_result($result, 0, "anfahrt_pkw"));
      $Spielort->set_str_anfahrt_oepnv(mysql_result($result, 0,
                                                    "anfahrt_oepnv"));
      $Spielort->set_str_zuschauer(mysql_result($result, 0, "zuschauer"));
      $Spielort->set_str_kommentar(mysql_result($result, 0, "kommentar"));
      return $Spielort;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spielort() {
    $sql = "SELECT id_spielort, name
            FROM ". TABLE_SPIELORT ."
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spielort = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_spielort[0][$i] = $arr_row[0];
        $arr_list_spielort[1][$i] = $arr_row[1];
        $i++;
      }
      return $arr_list_spielort;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spielort_by_Verein($id_verein) {
    $sql = "SELECT id_spielort, name
            FROM ". TABLE_SPIELORT ."
            WHERE id_verein = '$id_verein'
            ORDER BY name ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spielort = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spielort[0][$i] = $arr_row[0];
        $arr_list_spielort[1][$i] = $arr_row[1];
        $i++;
      }
      return $arr_list_spielort;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Spielort($id_verein) {
    if($id_verein) {
      $sql = "SELECT count(*)
              FROM ". TABLE_SPIELORT ."
              WHERE id_verein = $id_verein";
    }
    else {
      $sql = "SELECT count(*)
              FROM ". TABLE_SPIELORT ."
              WHERE 1";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function write_Spieltag(Spieltag $Spieltag) {
    $sql = "INSERT INTO ". VERBAND . TABLE_SPIELTAG . "
            SET id_spielort       = '". $Spieltag->get_int_id_spielort() ."',
                id_liga           = '". $Spieltag->get_int_id_liga() ."',
                datum             = '". $Spieltag->get_date_datum(TRUE) ."',
                spieltag_nr       = '". $Spieltag->get_str_spieltag_nr() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Spieltag(Spieltag $Spieltag) {
    $sql = "UPDATE  ". VERBAND . TABLE_SPIELTAG . "
            SET id_spielort = '". $Spieltag->get_int_id_spielort() ."',
                id_liga     = '". $Spieltag->get_int_id_liga() ."',
                datum       = '". $Spieltag->get_date_datum(TRUE) ."',
                spieltag_nr = '". $Spieltag->get_str_spieltag_nr() ."'
            WHERE id_spieltag = '". $Spieltag->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      echo "fehler!!!!";
      return FALSE;
    }
  }


  public static function read_Spieltag($int_id) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_SPIELTAG ."
            WHERE id_spieltag = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Spieltag = new Spieltag();
      $Spieltag->set_int_id(mysql_result($result, 0, "id_spieltag"));
      $Spieltag->set_int_id_spielort(mysql_result($result, 0, "id_spielort"));
      $Spieltag->set_int_id_liga(mysql_result($result, 0, "id_liga"));
      $Spieltag->set_date_datum(mysql_result($result, 0, "datum"));
      $Spieltag->set_str_spieltag_nr(mysql_result($result, 0, "spieltag_nr"));
      return $Spieltag;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieltag($int_id_liga = 0) {
    if($int_id_liga) {
      $sql = "SELECT id_spieltag, datum, sp.id_liga, liga.kurzname, id_spielort, sp.spieltag_nr
              FROM ". VERBAND . TABLE_SPIELTAG ." sp, ".
              VERBAND . TABLE_LIGA     ." liga
              WHERE sp.id_liga = liga.id_liga
              AND sp.id_liga = $int_id_liga
              ORDER BY sp.spieltag_nr ASC";
    }
    else {
      $sql = "SELECT id_spieltag, datum, sp.id_liga, liga.kurzname, id_spielort, sp.spieltag_nr
              FROM ". VERBAND . TABLE_SPIELTAG ." sp, ".
              VERBAND . TABLE_LIGA     ." liga
              WHERE sp.id_liga = liga.id_liga
              ORDER BY sp.spieltag_nr ASC";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spieltag = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieltag[0][$i] = $arr_row[0];
        $datum = sprintf("%02d.%02d.%04d",
                              substr($arr_row[1], 8, 2),
                              substr($arr_row[1], 5, 2),
                              substr($arr_row[1], 0, 4));
        $arr_list_spieltag[1][$i] = $datum;
        $arr_list_spieltag[2][$i] = $arr_row[2];
        $arr_list_spieltag[3][$i] = $arr_row[3];
        $arr_list_spieltag[4][$i] = $arr_row[4];
        $arr_list_spieltag[5][$i] = $arr_row[5];
        $i++;
      }
      return $arr_list_spieltag;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Spieltag_by_liga($int_id_liga) {
    $sql = "SELECT id_spieltag, datum, spieltag_nr
            FROM ". VERBAND . TABLE_SPIELTAG ."
            WHERE id_liga = $int_id_liga
            ORDER BY spieltag_nr ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spieltag = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieltag[0][$i] = $arr_row[0];
        $datum = sprintf("%02d.%02d.%04d",
                              substr($arr_row[1], 8, 2),
                              substr($arr_row[1], 5, 2),
                              substr($arr_row[1], 0, 4));
        $arr_list_spieltag[1][$i] = $datum;
        $arr_list_spieltag[2][$i] = $arr_row[2];
        $i++;
      }
      return $arr_list_spieltag;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Spieltag_by_Verein($id_verein) {
    global $arr_ids_verband;
    // erst alle Spielorte des Vereins suchen
    $arr_list_spieltag[0] = array();
    $arr_spielorte = get_list_Spielort_by_Verein($id_verein);
    if ($arr_spielorte) {
      for ($i=0; $i<count($arr_spielorte[0]); $i++) {
        $sql = "SELECT id_spieltag, datum, sp.id_liga, liga.kurzname, id_spielort, sp.spieltag_nr
              FROM ". VERBAND . TABLE_SPIELTAG ." sp, ".
                      VERBAND . TABLE_LIGA     ." liga
              WHERE sp.id_liga = liga.id_liga
                AND sp.id_spielort = ".$arr_spielorte[0][$i]."
              ORDER BY sp.spieltag_nr ASC";
        foreach($arr_ids_verband as $key => $value) {
          $db = self::db_connect(DB_NAME, "r", $key);
          if ($result = $db->query($sql)) {
            while ($arr_row = mysql_fetch_row($result)) {
              $arr_list_spieltag[0][] = $arr_row[0];
              $datum = sprintf("%02d.%02d.%04d",
                               substr($arr_row[1], 8, 2),
                               substr($arr_row[1], 5, 2),
                               substr($arr_row[1], 0, 4));
              $arr_list_spieltag[1][] = $datum;
              $arr_list_spieltag[2][] = $arr_row[2];
              $arr_list_spieltag[3][] = $arr_row[3];
              $arr_list_spieltag[4][] = $arr_row[4];
              $arr_list_spieltag[5][] = $arr_row[5];
              $arr_list_spieltag[6][] = $key;
            }
          }
        }
      }
    }
    else {
      return $arr_list_spieltag;
    }
  }


  public static function write_Begegnung(Begegnung $Begegnung) {
    $sql = "INSERT INTO ". VERBAND . TABLE_BEGEGNUNG . "
            SET id_spieltag      = '". $Begegnung->get_int_id_spieltag() ."',
                id_mannschaft1   = '". $Begegnung->get_int_id_mannschaft1() ."',
                id_verband_team1 = '". $Begegnung->get_int_id_verband_team1() ."',
                id_mannschaft2   = '". $Begegnung->get_int_id_mannschaft2() ."',
                id_verband_team2 = '". $Begegnung->get_int_id_verband_team2() ."',
                uhrzeit          = '". $Begegnung->get_str_uhrzeit() ."',
                schiedsrichter   = '". $Begegnung->get_str_schiedsrichter() ."',
                spielnummer      = '". $Begegnung->get_int_spielnummer() ."',
                genehmigt_von    = '". $Begegnung->get_int_genehmigt_von() ."',
                  forfeit        = '". $Begegnung->get_int_forfeit() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Begegnung(Begegnung $Begegnung) {
    $sql = "UPDATE  ". VERBAND . TABLE_BEGEGNUNG . "
            SET id_spieltag    = '". $Begegnung->get_int_id_spieltag() ."',
                id_mannschaft1   = '". $Begegnung->get_int_id_mannschaft1() ."',
                id_verband_team1 = '". $Begegnung->get_int_id_verband_team1() ."',
                id_mannschaft2   = '". $Begegnung->get_int_id_mannschaft2() ."',
                id_verband_team2 = '". $Begegnung->get_int_id_verband_team2() ."',
                uhrzeit        = '". $Begegnung->get_str_uhrzeit() ."',
                schiedsrichter = '". $Begegnung->get_str_schiedsrichter() ."',
                spielnummer    = '". $Begegnung->get_int_spielnummer() ."',
                forfeit        = '". $Begegnung->get_int_forfeit() ."',
                genehmigt_von  = '". $Begegnung->get_int_genehmigt_von() ."'
            WHERE id_begegnung = '". $Begegnung->get_int_id() ."'";

    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Begegnung($int_id) {
    $sql = "SELECT *
            FROM ". VERBAND . TABLE_BEGEGNUNG ."
            WHERE id_begegnung = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Begegnung = new Begegnung();
      $Begegnung->set_int_id(mysql_result($result, 0, "id_begegnung"));
      $Begegnung->set_int_id_spieltag(mysql_result($result, 0, "id_spieltag"));
      $int_id_mannschaft1   = mysql_result($result, 0, "id_mannschaft1");
      $int_id_verband_team1 = mysql_result($result, 0, "id_verband_team1");
      $Begegnung->set_int_id_mannschaft1($int_id_mannschaft1, $int_id_verband_team1);
      $int_id_mannschaft2   = mysql_result($result, 0, "id_mannschaft2");
      $int_id_verband_team2 = mysql_result($result, 0, "id_verband_team2");
      $Begegnung->set_int_id_mannschaft2($int_id_mannschaft2, $int_id_verband_team2);
      $Begegnung->set_str_uhrzeit(mysql_result($result, 0, "uhrzeit"));
      $Begegnung->set_str_schiedsrichter(mysql_result($result, 0, "schiedsrichter"));
      $Begegnung->set_int_spielnummer(mysql_result($result, 0, "spielnummer"));
      $Begegnung->set_int_forfeit(mysql_result($result, 0, "forfeit"));
      $Begegnung->set_int_genehmigt_von(mysql_result($result, 0, "genehmigt_von"));
      return $Begegnung;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Begegnung($int_id_spieltag) {
    // baustelle forfeit entfernt
    $sql = "SELECT id_begegnung, id_mannschaft1, id_mannschaft2, uhrzeit, schiedsrichter, spielnummer, forfeit, id_verband_team1, id_verband_team2, genehmigt_von
            FROM ". VERBAND . TABLE_BEGEGNUNG."
            WHERE id_spieltag = $int_id_spieltag
            ORDER BY uhrzeit ASC";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_spieltag = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_spieltag[0][$i] = $arr_row[0];
        $arr_list_spieltag[1][$i] = $arr_row[1];
        $arr_list_spieltag[2][$i] = $arr_row[2];
        $arr_list_spieltag[3][$i] = $arr_row[3];
        $arr_list_spieltag[4][$i] = $arr_row[4];
        $arr_list_spieltag[5][$i] = $arr_row[5];
        $arr_list_spieltag[6][$i] = $arr_row[6];
        $arr_list_spieltag[7][$i] = $arr_row[7];
        $arr_list_spieltag[8][$i] = $arr_row[8];
        //$arr_list_spieltag[7][$i] = $arr_row[7];
        $i++;
      }
      return $arr_list_spieltag;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function del_Begegnung($int_id_begegnung){
    $int_id_begegnung  = self::add_quotes($int_id_begegnung);
    $sql = "DELETE FROM ". VERBAND . TABLE_BEGEGNUNG . "
            WHERE id_begegnung = $int_id_begegnung";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Begegnung($int_id) {
    $sql = "SELECT count(*) FROM ". VERBAND . TABLE_BEGEGNUNG ."
            WHERE id_mannschaft1 = $int_id
                  OR id_mannschaft2 = $int_id";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_schiri_lizenztyp($int_id_lizenztyp){
    $sql = "SELECT name
            FROM ". TABLE_SCHIRI_LIZENZTYP . "
            WHERE id_lizenztyp = $int_id_lizenztyp";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_schiri_lizenztyp(){
    $sql = "SELECT id_lizenztyp, name
            FROM ". TABLE_SCHIRI_LIZENZTYP;
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_schiri_lizenztyp = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_schiri_lizenztyp[$i] = $arr_row[0];
        $i++;
      }
      return $arr_list_schiri_lizenztyp;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Schiedsrichter(Schiedsrichter $Schiri) {
    // baustelle
    $sql = "INSERT INTO ". TABLE_SCHIEDSRICHTER . "
            SET id_verein    = '". $Schiri->get_int_id_verein() ."',
                id_schiri_lizenztyp = '". $Schiri->get_int_id_lizenztyp() ."',
                lizenznummer = '". $Schiri->get_int_lizenznummer() ."',
                vorname      = '". $Schiri->get_str_vorname() ."',
                name         = '". $Schiri->get_str_name() ."',
                strasse      = '". $Schiri->get_str_strasse() ."',
                hausnummer   = '". $Schiri->get_str_hausnummer() ."',
                plz          = '". $Schiri->get_str_plz() ."',
                ort          = '". $Schiri->get_str_ort() ."',
                telefon      = '". $Schiri->get_str_telefon() ."',
                handy        = '". $Schiri->get_str_handy() ."',
                email        = '". $Schiri->get_str_email() ."',
                geb_datum    = '". $Schiri->get_date_geb_datum(TRUE) ."',
                kommentar    = '". $Schiri->get_str_kommentar() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function update_Schiedsrichter(Schiedsrichter $Schiri) {
    // baustelle
    $sql = "UPDATE  ". TABLE_SCHIEDSRICHTER . "
            SET id_verein    = '". $Schiri->get_int_id_verein() ."',
                id_schiri_lizenztyp = '". $Schiri->get_int_id_lizenztyp() ."',
                lizenznummer = '". $Schiri->get_int_lizenznummer() ."',
                vorname      = '". $Schiri->get_str_vorname() ."',
                name         = '". $Schiri->get_str_name() ."',
                strasse      = '". $Schiri->get_str_strasse() ."',
                hausnummer   = '". $Schiri->get_str_hausnummer() ."',
                plz          = '". $Schiri->get_str_plz() ."',
                ort          = '". $Schiri->get_str_ort() ."',
                telefon      = '". $Schiri->get_str_telefon() ."',
                handy        = '". $Schiri->get_str_handy() ."',
                email        = '". $Schiri->get_str_email() ."',
                geb_datum    = '". $Schiri->get_date_geb_datum(TRUE) ."',
                kommentar    = '". $Schiri->get_str_kommentar() ."'
            WHERE id_schiedsrichter = '". $Schiri->get_int_id() ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function read_Schiedsrichter($int_id) {
    // baustelle
    $sql = "SELECT *
            FROM ". TABLE_SCHIEDSRICHTER ."
            WHERE id_schiedsrichter = '$int_id'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Schiri = new Schiedsrichter();
      $Schiri->set_int_id(mysql_result($result, 0, "id_schiedsrichter"));
      $Schiri->set_int_id_lizenztyp(mysql_result($result, 0, "id_schiri_lizenztyp"));
      $Schiri->set_int_id_verein(mysql_result($result, 0, "id_verein"));
      $Schiri->set_int_lizenznummer(mysql_result($result, 0, "lizenznummer"));
      $Schiri->set_str_vorname(mysql_result($result, 0, "vorname"));
      $Schiri->set_str_name(mysql_result($result, 0, "name"));
      $Schiri->set_str_strasse(mysql_result($result, 0, "strasse"));
      $Schiri->set_str_hausnummer(mysql_result($result, 0, "hausnummer"));
      $Schiri->set_str_plz(mysql_result($result, 0, "plz"));
      $Schiri->set_str_ort(mysql_result($result, 0, "ort"));
      $Schiri->set_str_telefon(mysql_result($result, 0, "telefon"));
      $Schiri->set_str_handy(mysql_result($result, 0, "handy"));
      $Schiri->set_str_email(mysql_result($result, 0, "email"));
      $Schiri->set_date_geb_datum(mysql_result($result, 0, "geb_datum"));
      $Schiri->set_str_kommentar(mysql_result($result, 0, "kommentar"));
      return $Schiri;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_list_Schiedsrichter($int_id_verein = 0) {
    if ($int_id_verein) {
      $sql = "SELECT id_schiedsrichter, name, vorname
              FROM ". TABLE_SCHIEDSRICHTER ."
              WHERE id_verein = $int_id_verein
              ORDER BY name ASC";
    }
    else {
      $sql = "SELECT id_schiedsrichter, name, vorname
              FROM ". TABLE_SCHIEDSRICHTER ."
              WHERE 1
              ORDER BY name ASC";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_sschiedsrichter = array();
      $i=0;
      while($arr_row = mysql_fetch_row($result)){
        $arr_list_schiedsrichter[0][$i] = $arr_row[0];
        $arr_list_schiedsrichter[1][$i] = $arr_row[1];
        $arr_list_schiedsrichter[2][$i] = $arr_row[2];
        $i++;
      }
      return $arr_list_schiedsrichter;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_count_Schiedsrichter($int_id_verein) {
    if($int_id_verein) {
      $sql = "SELECT count(*)
              FROM ". TABLE_SCHIEDSRICHTER."
              WHERE id_verein = $int_id_verein";
    }
    else {
      $sql = "SELECT count(*)
              FROM ". TABLE_SCHIEDSRICHTER."
              WHERE 1";
    }
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    } else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_teamname_by_id($int_id) {
    $sql = "SELECT name
            FROM ". VERBAND . TABLE_MANNSCHAFT ."
            WHERE id_mannschaft = $int_id
            LIMIT 1";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "name");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_teamliga_by_id($int_id) {
    $sql = "SELECT id_liga
            FROM ". VERBAND . TABLE_MANNSCHAFT ."
            WHERE id_mannschaft = $int_id
            LIMIT 1";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "id_liga");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_status_of_lizenz($id_lizenz) {
    $sql = "SELECT id_lizenzstatus
            FROM ". VERBAND . TABLE_LIZENZVERLAUF ."
            WHERE id_lizenz = $id_lizenz
            ORDER BY timestamp DESC
            LIMIT 1";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "id_lizenzstatus");
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_last_Lizenzstatus($id_lizenz) {
    $sql = "SELECT id_lizenzstatus, DATE_FORMAT(timestamp, '%d.%m.%Y')
            FROM ". VERBAND . TABLE_LIZENZVERLAUF ."
            WHERE id_lizenz = $id_lizenz
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

  public static function get_Lizenzstatus($id_lizenzstatus) {
    $sql = "SELECT text FROM ". TABLE_LIZENZSTATUS ."
            WHERE id_lizenzstatus = $id_lizenzstatus";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "text");
    }
    else {
      // Fehlermeldung
      return FALSE;
    }
  }

  public static function get_list_status_lizenz(){
    $sql = "SELECT *
            FROM ". TABLE_LIZENZSTATUS;
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_status_lizenz = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_status_lizenz[0][$i] = $arr_row[0];
        $arr_list_status_lizenz[1][$i] = $arr_row[1];
        $i++;
      }
      return $arr_list_status_lizenz;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Verband() {
    $sql = "SELECT id_verband, name, kuerzel, pfad, subdomain
            FROM ". TABLE_VERBAND;
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_verband = array();
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_verband[0][$i] = $arr_row[0];
        $arr_list_verband[1][$i] = $arr_row[1];
        $arr_list_verband[2][$i] = $arr_row[2];
        $arr_list_verband[3][$i] = $arr_row[3];
        $i++;
      }
      return $arr_list_verband;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function get_Verband_by_pfad($str_pfad){
    $sql = "SELECT id_verband, name, kuerzel, pfad, subdomain
            FROM ". TABLE_VERBAND ."
            WHERE pfad = '$str_pfad'";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $Verband = new Verband();
      $Verband->set_int_id(mysql_result($result, 0, "id_verband"));
      $Verband->set_str_name(mysql_result($result, 0, "name"));
      $Verband->set_str_kuerzel(mysql_result($result, 0, "kuerzel"));
      $Verband->set_str_subdomain(mysql_result($result, 0, "subdomain"));
      $Verband->set_str_pfad(mysql_result($result, 0, "pfad"));
      return $Verband;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


  public static function write_Verein_Verband($id_verein, $id_verband,
                                              $bool_heimat = FALSE) {
    $sql = "INSERT INTO ". TABLE_VEREIN_VERBAND . "
            SET id_verein      = '". $id_verein ."',
                id_verband       = '". $id_verband ."',
                heimatverband    = '". $bool_heimat ."'";
    $db = self::db_connect(DB_NAME, "rw");
    $db->query_insert($sql);
    return TRUE;
  }

  public static function delete_Verein_Verband($id_verein, $id_verband) {
    $sql = "DELETE FROM ". TABLE_VEREIN_VERBAND ."
            WHERE id_verein = $id_verein
                  AND id_verband = $id_verband";
    $db = self::db_connect(DB_NAME, "rw");
    if($db->query($sql)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public static function check_Gastverband_Mannschaft($id_verein, $id_verband) {
    $sql = "SELECT * FROM ". TABLE_VERBAND ." WHERE id_verband = $id_verband";
    $db = self::db_connect(DB_NAME, "r");

    if ($result = $db->query($sql)) {
      $verband = mysql_result($result, 0, "pfad");
      $sql = "SELECT count(*) FROM ". $verband . TABLE_MANNSCHAFT ."
              WHERE id_verein = $id_verein";
      if ($result = $db->query($sql)) {
        $count = mysql_result($result, 0, "count(*)");
        return $count;
      }
      else {
        return -1;
      }
    }
    else {
      return -1;
    }
  }

  public static function get_count_Lizenz_of_Spieler($id_spieler) {
    $arr_verband = DBMapper::get_list_Verband();
    $count = 0;
    $my_db = self::db_connect(DB_NAME, "r");
    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT count(*) FROM ". $verband . TABLE_LIZENZ ."
              WHERE id_spieler = $id_spieler";
      if ($result = $my_db->query($sql)) {
        $count += mysql_result($result, 0, "count(*)");
      }
      else {
        return -1;
      }

    }
    return $count;
  }

  public static function get_count_Lizenz_of_Spieler2($id_spieler, $id_verein) {
    $arr_verband = DBMapper::get_list_Verband();
    $count = 0;
    $my_db = self::db_connect(DB_NAME, "r");
    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT count(*) FROM ". $verband . TABLE_LIZENZ ." l,
              ". $verband . TABLE_MANNSCHAFT ." m
              WHERE l.id_spieler = $id_spieler
              AND   l.id_mannschaft = m.id_mannschaft
              AND   m.id_verein = $id_verein";
      if ($result = $my_db->query($sql)) {
        $count += mysql_result($result, 0, "count(*)");
      }
      else {
        return -1;
      }

    }
    return $count;
  }

  public static function get_count_Doppellizenz($id_spieler) {
    $sql = "SELECT count(*)
            FROM ". TABLE_DOPPELLIZENZ ."
            WHERE id_spieler = $id_spieler";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_count_Doppel($id_mannschaft, $id_verband) {
    $sql = "SELECT count(*)
            FROM ". TABLE_DOPPELLIZENZ ."
            WHERE id_mannschaft = $id_mannschaft
            AND id_verband = $id_verband";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function get_count_Doppel_B($id_mannschaft, $id_verband) {
    $sql = "SELECT count(*)
            FROM ". TABLE_DOPPELLIZENZ ."
            WHERE id_mannschaft = $id_mannschaft
            AND id_verband = $id_verband
            AND (id_klasse = 10 OR id_klasse = 20)";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "count(*)");
    }
    else {
      // Fehlermeldung generieren
      return -1;
    }
  }

  public static function write_Doppel($id_spieler, $id_verband,
                                      $id_lizenz, $id_klasse,
                                      $id_mannschaft, $ord) {
    $sql = "INSERT INTO ". TABLE_DOPPELLIZENZ . "
            SET id_spieler    = $id_spieler,
                id_verband    = $id_verband,
                id_lizenz     = $id_lizenz,
                id_klasse     = $id_klasse,
                id_mannschaft = $id_mannschaft,
                ordnungsnr    = $ord";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function delete_Doppellizenz($id_spieler, $id_lizenz=0) {
    if ($id_lizenz) {
      $sql = "DELETE FROM ". TABLE_DOPPELLIZENZ ."
              WHERE id_spieler = $id_spieler
                  AND id_lizenz = $id_lizenz";
    }
    else {
      $sql = "DELETE FROM ". TABLE_DOPPELLIZENZ ."
              WHERE id_spieler = $id_spieler";
    }
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }


  public static function get_count_Spieler_of_Mitspieler($id_spieler) {
    $arr_verband = DBMapper::get_list_Verband();
    $count = 0;
    $my_db = self::db_connect(DB_NAME, "r");
    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT count(*) FROM ". VERBAND . TABLE_MITSPIELER ."
              WHERE id_spieler = $id_spieler";
      if ($result = $my_db->query($sql)) {
        $count += mysql_result($result, 0, "count(*)");
      }
      else {
        return -1;
      }

    }
    return $count;
  }

  public static function get_list_Schiri_Lizenztype() {
    $sql = "SELECT *
            FROM ". TABLE_SCHIRI_LIZENZTYP ."
            ORDER BY id_schiri_lizenztyp ASC";
    $db = self::db_connect(DB_NAME, "r");
    $arr_list_lizenztyp[0] = array();
    $arr_list_lizenztyp[1] = array();
    $arr_list_lizenztyp[2] = array();
    if ($result = $db->query($sql)) {
      $i=0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_lizenztyp[0][$i] = $arr_row[0];
        $arr_list_lizenztyp[1][$i] = $arr_row[1];
        $arr_list_lizenztyp[2][$i] = $arr_row[2];
        $i++;
      }
    } else {
      // Fehlermeldung generieren
    }
    return $arr_list_lizenztyp;
  }


  public static function get_kuerzel_Liga_by_Mannschaft($id_mannschaft) {
    $sql = "SELECT l.kurzname
            FROM ". VERBAND . TABLE_MANNSCHAFT ." m,
                 ". VERBAND . TABLE_LIGA ." l
            WHERE m.id_mannschaft = $id_mannschaft
            AND   m.id_liga = l.id_liga";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "l.kurzname");
    }
    else return "";
  }

  public static function get_list_Pokalliga($including_dm = false) {
    $arr_verband = DBMapper::get_list_Verband();
    $count = 0;
    $arr_result[0] = array();
    $arr_result[1] = array();
    $arr_result[2] = array();
    $my_db = self::db_connect(DB_NAME, "r");
    for ($i=0; $i<count($arr_verband[0]); $i++) {
      $verband = $arr_verband[3][$i];
      $sql = "SELECT id_liga, kurzname FROM ". $verband . TABLE_LIGA ."
              WHERE id_kategorie = 3
              OR    id_kategorie = 4";
      if ($including_dm) {
        $sql .= " OR id_kategorie = 100 OR id_kategorie = 101";
      }
      if ($result = $my_db->query($sql)) {
        while ($arr_row = mysql_fetch_row($result)) {
          $arr_result[0][] = $arr_row[0];
          $arr_result[1][] = $arr_verband[0][$i];
          $arr_result[2][] = $arr_row[1];
          $arr_result[3][] = $arr_verband[2][$i];
        }
      }
    }
    return $arr_result;
  }

  public static function get_list_Pokalliga_of_Team($id_mannschaft,
                                                    $id_verband) {
    $sql = "SELECT id_liga, id_verband_liga FROM ". TABLE_POKAL_TEAM ."
            WHERE id_mannschaft = $id_mannschaft
            AND   id_verband_mannschaft = $id_verband";
    $db = self::db_connect(DB_NAME, "r");
    $arr_result[0] = array();
    $arr_result[1] = array();
    if ($result = $db->query($sql)) {
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_result[0][] = $arr_row[0];
        $arr_result[1][] = $arr_row[1];
      }
    }
    return $arr_result;
  }

  public static function add_Mannschaft_to_Pokal($id_liga,
                                                 $id_verband_liga,
                                                 $id_mannschaft,
                                                 $id_verband_mannschaft) {
    $sql = "INSERT INTO ". TABLE_POKAL_TEAM . "
            SET id_liga               = '". $id_liga ."',
                id_verband_liga       = '". $id_verband_liga ."',
                id_mannschaft         = '". $id_mannschaft ."',
                id_verband_mannschaft = '". $id_verband_mannschaft ."'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function del_Mannschaft_from_Pokal($id_liga,
                                                   $id_verband_liga,
                                                   $id_mannschaft,
                                                   $id_verband_mannschaft){
    $sql = "DELETE
            FROM ". TABLE_POKAL_TEAM ."
            WHERE id_liga = '$id_liga'
            AND   id_verband_liga = '$id_verband_liga'
            AND   id_mannschaft = '$id_mannschaft'
            AND   id_verband_mannschaft = '$id_verband_mannschaft'";
    $db = self::db_connect(DB_NAME, "rw");
    if ($db->query($sql)) {
      return TRUE;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }

  public static function get_list_Mannschaft_of_Pokal($id_liga,
                                                      $id_verband_liga) {
    $sql = "SELECT id_mannschaft, id_verband_mannschaft
            FROM ". TABLE_POKAL_TEAM ."
            WHERE id_liga = $id_liga
            AND id_verband_liga = $id_verband_liga";
    $db = self::db_connect(DB_NAME, "r");
    $arr_tmp[0] = array();
    $arr_tmp[1] = array();
    if ($result = $db->query($sql)) {
      while($arr_row = mysql_fetch_row($result)) {
        $arr_tmp[0][] = $arr_row[0];
        $arr_tmp[1][] = $arr_row[1];
      }
    }
    $arr_verband = DBMapper::get_list_Verband();
    $arr_result[0] = array();
    $arr_result[1] = array();
    for ($i=0; $i<count($arr_tmp[0]); $i++) {
      for ($k=0; $k<count($arr_verband[0]); $k++) {
        if ($arr_verband[0][$k] == $arr_tmp[1][$i]) {
          $verband = $arr_verband[3][$k];
          $sql = "SELECT name, id_verein FROM ". $verband . TABLE_MANNSCHAFT ."
              WHERE id_mannschaft = '".$arr_tmp[0][$i]."'";
          if ($result = $db->query($sql)) {
            $arr_result[0][] = $arr_tmp[0][$i];
            $arr_result[1][] = mysql_result($result, 0, "name");
            $arr_result[2][] = $arr_verband[0][$k];
            $arr_result[3][] = mysql_result($result, 0, "id_verein");
          }
          break;
        }
      }
    }
    return $arr_result;
  }

  public static function get_Pfad_of_Verband($id_verband) {
    $sql = "SELECT pfad
            FROM ". TABLE_VERBAND ."
            WHERE id_verband = $id_verband";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "pfad");
    }
    else {
      return FALSE;
    }
  }

  public static function get_Doppel_nr($id_spieler,
                                       $id_mannschaft,
                                       $id_verband) {
    if ($id_verband == 0) $id_verband = DBMapper::get_id_Verband();
    $sql = "SELECT ordnungsnr FROM ". TABLE_DOPPELLIZENZ."
            WHERE id_spieler = $id_spieler
              AND id_mannschaft = $id_mannschaft
              AND id_verband = $id_verband";
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      return mysql_result($result, 0, "ordnungsnr");
    }
    else return 0;
  }

  public static function get_Statistik_Ordnung($id_verband,
                                               $id_liga, $id_mannschaft) {
    if ($id_verband == 0) $id_verband = DBMapper::get_id_Verband();
    $sql = "SELECT ordnungsnr FROM ". TABLE_MEISTER_SPALTUNG ."
            WHERE id_verband = $id_verband
              AND id_liga = $id_liga
              AND id_mannschaft = $id_mannschaft";
    $db = self::db_connect(DB_NAME, "r");
    //echo $sql."<br/>";
    if ($result = $db->query($sql)) {
      $bla = mysql_result($result, 0, "ordnungsnr");
      //echo $bla;
      return $bla;
    }
    else return 0;
  }

  public static function get_Playoff($id_verband,
                                     $id_liga, $id_begegnung) {
    if ($id_verband == 0) $id_verband = DBMapper::get_id_Verband();
    $sql = "SELECT playoff FROM ". TABLE_PLAYOFF ."
            WHERE id_verband = $id_verband
              AND id_liga = $id_liga
              AND id_begegnung = $id_begegnung";
    $db = self::db_connect(DB_NAME, "r");
    //echo $sql."<br/>";
    if ($result = $db->query($sql)) {
      $bla = mysql_result($result, 0, "playoff");
      //echo $bla;
      return $bla;
    }
    else return 0;
  }

  public static function set_Playoff($id_verband,
                                     $id_liga, $id_begegnung, $playoff) {
    if ($id_verband == 0) $id_verband = DBMapper::get_id_Verband();
    $sql = "INSERT INTO ". TABLE_PLAYOFF ."
            SET id_verband  = '".$id_verband."',
              id_liga       = '".$id_liga."',
              id_begegnung  = '".$id_begegnung."',
              playoff       = '".$playoff."'";
    $db = self::db_connect(DB_NAME, "rw");
    echo $sql;
    if ($last_insert_id = $db->query_insert($sql)) {
      return $last_insert_id;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }
}

?>
