<?php
/**
 * Benutzer
 *
 * Datenobjekt dass die Daten zu einem Benutzer enthält.
 *
 * @package    OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Benutzer {

  private $int_id           = NULL;   // INT(10) unsigned
  private $str_user_name    = NULL;   // VARCHAR(20)
  private $str_vorname      = NULL;
  private $str_nachname     = NULL;
  private $date_geb_datum   = NULL;   // YYYY-MM-DD
  private $str_beschreibung = NULL;   // TEXT
  private $str_email        = NULL;   // VARCHAR(255)
  private $str_strasse      = NULL;
  private $str_hausnummer   = NULL;
  private $str_plz          = NULL;
  private $str_ort          = NULL;
  private $time_erstellt    = NULL;   // timestamp
  private $str_passwort     = NULL;   // VARCHAR(45) MD5
  private $bool_aktiv       = FALSE;  // INT(1)
  private $bool_datenschutz = FALSE;  // INT(1)
  private $int_id_verein    = NULL;   // INT(10) unsigned
  private $str_verein_name  = NULL;   // Wird durch $int_is_verein gesetzt

  private $str_passwort2    = NULL;   // VARCHAR(45) MD5
  private $time_paswort2    = NULL;   // timestamp

  private $arr_gruppe       = NULL;   // Gruppen (ID, [Name])
  private $arr_teams        = NULL;   // Temas (ID, [Name])

  /**
   * Constructor
   * @access public
   */
  public function __construct ($int_id           = NULL,
                                $str_user_name    = NULL,
                                $str_beschreibung = NULL,
                                $str_passwort     = NULL,
                                $str_email        = NULL,
                                $bool_aktiv       = FALSE,
                                $str_strasse      = NULL,
                                $int_id_verein    = NULL,
                                $str_hausnummer   = NULL,
                                $str_plz          = NULL,
                                $str_ort          = NULL,
                                $time_erstellt    = NULL,
                                $bool_datenschutz = FALSE,
                                $str_vorname      = NULL,
                                $str_nachname     = NULL,
                                $date_geb_datum   = NULL,
                                $str_passwort2    = NULL,
                                $time_passwort2   = NULL){
    $this->set_int_id($int_id);
    $this->set_str_user_name($str_user_name);
    $this->set_str_vorname($str_vorname);
    $this->set_str_nachname($str_nachname);
    $this->set_date_geb_datum($date_geb_datum);
    $this->set_str_beschreibung($str_beschreibung);
    $this->set_str_email($str_email);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_time_erstellt($time_erstellt);
    $this->set_str_passwort($str_passwort);
    $this->set_bool_aktiv($bool_aktiv);
    $this->set_bool_datenschutz($bool_datenschutz);
    $this->set_int_id_verein($int_id_verein);
    $this->set_str_passwort2($str_passwort2);
    $this->set_time_passwort2($time_passwort2);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_verein(){
    return $this->int_id_verein;
  }

  public function set_int_id_verein($int_id_verein){
    $this->int_id_verein = $int_id_verein;
    if (    $Verein = DBMapper::read_Verein($int_id_verein)) {
      $this->str_verein_name = $Verein->get_str_name();
    }
  }

  public function get_str_verein_name(){
    return $this->str_verein_name;
  }

  public function get_str_user_name(){
    return $this->str_user_name;
  }

  public function set_str_user_name($str_user_name){
    $this->str_user_name = $str_user_name;
  }

  public function get_str_vorname() {
    return $this->str_vorname;
  }

  public function set_str_vorname($str_vorname) {
    $this->str_vorname = $str_vorname;
  }

  public function get_str_nachname() {
    return $this->str_nachname;
  }

  public function set_str_nachname($str_nachname) {
    $this->str_nachname = $str_nachname;
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

  public function get_str_strasse() {
    return $this->str_strasse;
  }

  public function set_str_strasse($str_strasse) {
    $this->str_strasse = $str_strasse;
  }

  public function get_str_hausnummer() {
    return $this->str_hausnummer;
  }

  public function set_str_hausnummer($str_hausnummer) {
    $this->str_hausnummer = $str_hausnummer;
  }

  public function get_str_plz() {
    return $this->str_plz;
  }

  public function set_str_plz($str_plz) {
    $this->str_plz = $str_plz;
  }

  public function get_str_ort() {
    return $this->str_ort;
  }

  public function set_str_ort($str_ort) {
    $this->str_ort = $str_ort;
  }

  public function get_time_erstellt() {
    return $this->time_erstellt;
  }

  public function set_time_erstellt($time_erstellt) {
    $this->time_erstellt = $time_erstellt;
  }

  public function get_bool_datenschutz() {
    return $this->bool_datenschutz;
  }

  public function set_bool_datenschutz($bool_datenschutz) {
    $this->bool_datenschutz = $bool_datenschutz;
  }

  public function get_str_passwort2() {
    return $this->str_passwort2;
  }

  public function set_str_passwort2($str_passwort2) {
    $str_passwort2 = md5($str_passwort2);
    $this->str_passwort2 = $str_passwort2;
  }

  public function get_time_passwort2() {
    return $this->time_passwort2;
  }

  public function set_time_passwort2($time_passwort2) {
    $this->time_passwort2 = $time_passwort2;
  }

  public function get_str_beschreibung(){
    return $this->str_beschreibung;
  }

  public function set_str_beschreibung($str_beschreibung){
    $this->str_beschreibung = $str_beschreibung;
  }

  public function get_str_passwort(){
    return $this->str_passwort;
  }

  public function set_str_passwort($str_passwort){
    $str_passwort = md5($str_passwort);
    $this->str_passwort = $str_passwort;
  }

  public function get_str_email(){
    return $this->str_email;
  }

  public function set_str_email($str_email){
    $this->str_email = $str_email;
  }

  public function get_bool_aktiv(){
    return $this->bool_aktiv;
  }

  public function set_bool_aktiv($bool_aktiv){
    $this->bool_aktiv = $bool_aktiv;
  }

  public function get_arr_gruppe(){
    return $this->arr_gruppe;
  }

  public function set_arr_gruppe($arr_gruppe){
    $this->arr_gruppe = $arr_gruppe;
  }

  public function get_arr_teams(){
    return $this->arr_teams;
  }

  public function set_arr_teams($arr_teams){
    $this->arr_teams = $arr_teams;
  }


}

?>