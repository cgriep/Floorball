<?php
/**
 * Spielort
 *
 * Datenobjekt dass die Daten zu einem Spielort enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Spielort {

  private $int_id            = NULL;
  private $int_id_verein     = NULL;
  private $str_name          = NULL;
  private $str_strasse       = NULL;
  private $str_hausnummer    = NULL;
  private $str_plz           = NULL;
  private $str_ort           = NULL;
  private $str_anfahrt_pkw   = NULL;
  private $str_anfahrt_oepnv = NULL;
  private $str_zuschauer     = NULL;
  private $str_kommentar     = NULL;

  /**
   * Constructor
   * @access protected
   */
  function __construct($int_id = NULL,
                       $int_id_verein = NULL,
                       $str_name = NULL,
                       $str_strasse = NULL,
                       $str_hausnummer = NULL,
                       $str_plz = NULL,
                       $str_ort = NULL,
                       $str_anfahrt_pkw = NULL,
                       $str_anfahrt_oepnv = NULL,
                       $str_zuschauer = NULL,
                       $str_kommentar = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_verein($int_id_verein);
    $this->set_str_name($str_name);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_str_anfahrt_pkw($str_anfahrt_pkw);
    $this->set_str_anfahrt_oepnv($str_anfahrt_oepnv);
    $this->set_str_zuschauer($str_zuschauer);
    $this->set_str_kommentar($str_kommentar);
  }


  function get_int_id(){
    return $this->int_id;
  }

  function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  function get_int_id_verein(){
    return $this->int_id_verein;
  }

  function set_int_id_verein($int_id_verein){
    $this->int_id_verein = $int_id_verein;
  }

  function get_str_name(){
    return $this->str_name;
  }

  function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  function get_str_strasse(){
    return $this->str_strasse;
  }

  function set_str_strasse($str_strasse){
    $this->str_strasse = $str_strasse;
  }

  function get_str_hausnummer(){
    return $this->str_hausnummer;
  }

  function set_str_hausnummer($str_hausnummer){
    $this->str_hausnummer = $str_hausnummer;
  }

  function get_str_plz(){
    return $this->str_plz;
  }

  function set_str_plz($str_plz){
    $this->str_plz = $str_plz;
  }

  function get_str_ort(){
    return $this->str_ort;
  }

  function set_str_ort($str_ort){
    $this->str_ort = $str_ort;
  }

  function get_str_anfahrt_pkw(){
    return $this->str_anfahrt_pkw;
  }

  function set_str_anfahrt_pkw($str_anfahrt_pkw){
    $this->str_anfahrt_pkw = $str_anfahrt_pkw;
  }

  function get_str_anfahrt_oepnv(){
    return $this->str_anfahrt_oepnv;
  }

  function set_str_anfahrt_oepnv($str_anfahrt_oepnv){
    $this->str_anfahrt_oepnv = $str_anfahrt_oepnv;
  }

  function get_str_zuschauer(){
    return $this->str_zuschauer;
  }

  function set_str_zuschauer($str_zuschauer){
    $this->str_zuschauer = $str_zuschauer;
  }

  function get_str_kommentar(){
    return $this->str_kommentar;
  }

  function set_str_kommentar($str_kommentar){
    $this->str_kommentar = $str_kommentar;
  }

}

?>
