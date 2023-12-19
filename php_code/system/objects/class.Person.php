<?php
/**
 * Person
 *
 * Datenobjekt dass die Daten zu einer Person enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Person {

  private $int_id          = NULL;
  private $int_id_verein   = NULL;
  private $str_verein_name = NULL; // Wird beim setzen von int_id_verein gesetzt
  private $str_vorname     = NULL;
  private $str_name        = NULL;
  private $str_strasse     = NULL;
  private $str_hausnummer  = NULL;
  private $str_plz         = NULL;
  private $str_ort         = NULL;
  private $str_telefon     = NULL;
  private $str_handy       = NULL;
  private $str_email       = NULL;
  private $date_geb_datum  = NULL; // YYYY-MM-DD


  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $int_id_verein = NULL,
                       $str_vorname = NULL,
                       $str_name = NULL,
                       $str_strasse = NULL,
                       $str_hausnummer = NULL,
                       $str_plz = NULL,
                       $str_ort = NULL,
                       $str_telefon = NULL,
                       $str_handy = NULL,
                       $str_email = NULL,
                       $date_geb_datum = NULL) {
    $this->set_int_id($int_id);
    $this->set_int_id_verein($int_id_verein);
    $this->set_str_vorname($str_vorname);
    $this->set_str_name($str_name);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_str_telefon($str_telefon);
    $this->set_str_handy($str_handy);
    $this->set_str_email($str_email);
    $this->set_date_geb_datum($date_geb_datum);
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
    if ($int_id_verein != NULL && $int_id_verein > 0) {
      $this->int_id_verein = $int_id_verein;
      $Verein = DBMapper::read_Verein($int_id_verein);
      $this->str_verein_name = $Verein->get_str_name();
    }
  }

  public function get_str_verein_name(){
    return $this->str_verein_name;
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

  public function get_str_telefon(){
    return $this->str_telefon;
  }

  public function set_str_telefon($str_telefon){
    $this->str_telefon = $str_telefon;
  }

  public function get_str_handy(){
    return $this->str_handy;
  }

  public function set_str_handy($str_handy){
    $this->str_handy = $str_handy;
  }

  public function get_str_email(){
    return $this->str_email;
  }

  public function set_str_email($str_email){
    $this->str_email = $str_email;
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

}

?>