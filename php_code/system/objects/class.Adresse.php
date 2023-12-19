<?php
/**
 * Adresse
 *
 * Datenobjekt dass die Daten für eine Adresse speichern kann.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Adresse {

  private $int_id          = NULL;
  private $str_strasse     = NULL;
  private $str_hausnummer  = NULL;
  private $str_plz         = NULL;
  private $str_ort         = NULL;
  private $str_telefon     = NULL;
  private $str_handy       = NULL;
  private $str_email       = NULL;


  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $str_strasse = NULL,
                       $str_hausnummer = NULL,
                       $str_plz = NULL,
                       $str_ort = NULL,
                       $str_telefon = NULL,
                       $str_handy = NULL,
                       $str_email = NULL) {
    $this->set_int_id($int_id);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_str_telefon($str_telefon);
    $this->set_str_handy($str_handy);
    $this->set_str_email($str_email);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
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


}

?>