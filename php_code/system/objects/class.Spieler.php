<?php
/**
 * Spieler
 *
 * Datenobjekt dass die Daten zu einem Spieler enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Spieler {

  private $int_id          = NULL;
  private $str_vorname     = NULL;
  private $str_name        = NULL;
  private $bool_geschlecht = NULL; // 0 männlich 1 weiblich
  private $date_geb_datum  = NULL; // YYYY-MM-DD
  private $int_id_nation   = NULL;
  private $str_nation      = NULL; // wird durch set_int_id_nation gesetzt
  private $str_nation_kuerzel = NULL; // wird durch set_int_id_nation gesetzt
  private $bool_europa     = NULL; // wird durch set_int_id_nation gesetzt
  private $str_strasse     = NULL;
  private $str_hausnummer  = NULL;
  private $str_plz         = NULL;
  private $str_ort         = NULL;
  private $erstellt        = NULL; // timestamp

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $str_vorname = NULL,
                       $str_name = NULL,
                       $bool_geschlecht = NULL,
                       $date_geb_datum = NULL,
                       $int_id_nation = NULL,
                       $str_strasse = NULL,
                       $str_hausnummer = NULL,
                       $str_plz = NULL,
                       $str_ort = NULL,
                       $erstellt = NULL){
    $this->set_int_id($int_id);
    $this->set_str_vorname($str_vorname);
    $this->set_str_name($str_name);
    $this->set_bool_geschlecht($bool_geschlecht);
    $this->set_date_geb_datum($date_geb_datum);
    $this->set_int_id_nation($int_id_nation);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_erstellt($erstellt);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_str_vorname(){
    return $this->str_vorname;
  }

  public function set_str_vorname($str_vorname){
    $this->str_vorname = $str_vorname;
  }

  public function get_str_name(){
    return $this->str_name;
  }

  public function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  public function get_bool_geschlecht(){
    return $this->bool_geschlecht;
  }

  public function set_bool_geschlecht($bool_geschlecht){
    $this->bool_geschlecht = $bool_geschlecht;
  }

  public function get_date_geb_datum($mysql = FALSE){
    if ($mysql) {
      return $this->date_geb_datum;
    } else {
     $datum = $this->date_geb_datum;
     $datum = sprintf("%02d.%02d.%04d",
                              substr($datum, 8, 2),
                              substr($datum, 5, 2),
                              substr($datum, 0, 4));
      return $datum;
    }
  }

  public function set_date_geb_datum($date_geb_datum){
    $datum = $date_geb_datum;

    // wenn Format TT.MM.JJJJ ist
    if (preg_match('/(0[1-9]|[12][0-9]|3[01])[- \/\.](0[1-9]|1[012])[- \/\.](19|20)[0-9]{2}/', $datum)) {
      $this->date_geb_datum = sprintf("%04d-%02d-%02d",
                                substr($datum, 6, 4),
                                substr($datum, 3, 2),
                                substr($datum, 0, 2));
    } else if (preg_match('/\b(19|20)?[0-9]{2}[- \/\.](0?[1-9]|1[012])[- \/\.](0?[1-9]|[12][0-9]|3[01])\b/', $datum)) {
      // wenn Format YYYY-MM-DD ist
      $this->date_geb_datum = sprintf("%04d-%02d-%02d",
                                substr($datum, 0, 4),
                                substr($datum, 5, 2),
                                substr($datum, 8, 2));
    } else {
      $this->date_geb_datum = NULL;
    }
  }

  public function get_str_strasse(){
    return $this->str_strasse;
  }

  public function set_str_strasse($str_strasse){
    $this->str_strasse = $str_strasse;
  }

  public function get_str_hausnummer(){
    return $this->str_hausnummer;
  }

  public function set_str_hausnummer($str_hausnummer){
    $this->str_hausnummer = $str_hausnummer;
  }

  public function get_str_plz(){
    return $this->str_plz;
  }

  public function set_str_plz($str_plz){
    $this->str_plz = $str_plz;
  }

  public function get_str_ort(){
    return $this->str_ort;
  }

  public function set_str_ort($str_ort){
    $this->str_ort = $str_ort;
  }

  public function set_int_id_nation($int_id_nation) {
    $this->int_id_nation = $int_id_nation;
    // passenden String aud der DB holen
    if ($array = DBM_Spieler::get_nation($int_id_nation)) {
      $this->str_nation  = $array[0];
      $this->bool_europa = $array[1];
      $this->str_nation_kuerzel = $array[2];
    } else {
      $this->int_id_nation = NULL;
      $this->str_nation    = NULL;
      $this->bool_europa   = NULL;
    }
  }

  public function get_str_nation(){
    return $this->str_nation;
  }

  public function get_bool_europa(){
    return $this->bool_europa;
  }

  public function get_str_nation_kuerzel(){
    return $this->str_nation_kuerzel;
  }

  public function get_int_id_nation(){
    return $this->int_id_nation;
  }

  public function get_erstellt(){
    return $this->erstellt;
  }

  public function set_erstellt($erstellt){
    $this->erstellt = $erstellt;
  }

}

?>