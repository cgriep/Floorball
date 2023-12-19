<?php
/**
 * Verein
 *
 * Datenobjekt dass die Daten zu einem Verein enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Verein {

  private $int_id = NULL;
  private $int_id_spartenleiter = NULL;
  private $str_name = NULL;
  private $str_kurzname = NULL;
  private $str_strasse = NULL;
  private $str_hausnummer = NULL;
  private $str_plz = NULL;
  private $str_ort = NULL;
  private $str_homepage_verein = NULL;
  private $str_homepage_sparte = NULL;

  /**
   * Constructor
   * @access protected
   */
  function __construct($int_id = NULL,
                       $int_id_spartenleiter = NULL,
                       $str_name = NULL,
                       $str_kurzname = NULL,
                       $str_strasse = NULL,
                       $str_hausnummer = NULL,
                       $str_plz = NULL,
                       $str_ort = NULL,
                       $str_hp_verein = NULL,
                       $str_hp_sparte = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_spartenleiter($int_id_spartenleiter);
    $this->set_str_name($str_name);
    $this->set_str_kurzname($str_kurzname);
    $this->set_str_strasse($str_strasse);
    $this->set_str_hausnummer($str_hausnummer);
    $this->set_str_plz($str_plz);
    $this->set_str_ort($str_ort);
    $this->set_str_homepage_verein($str_hp_verein);
    $this->set_str_homepage_sparte($str_hp_sparte);
  }


  function get_int_id(){
    return $this->int_id;
  }

  function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  function get_int_id_spartenleiter(){
    return $this->int_id_spartenleiter;
  }

  function set_int_id_spartenleiter($int_id_spartenleiter){
    $this->int_id_spartenleiter = $int_id_spartenleiter;
  }

  function get_str_name(){
    return $this->str_name;
  }

  function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  function get_str_kurzname(){
    return $this->str_kurzname;
  }

  function set_str_kurzname($str_kurzname){
    $this->str_kurzname = $str_kurzname;
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

  function get_str_homepage_verein(){
    return $this->str_homepage_verein;
  }

  function set_str_homepage_verein($str_homepage_verein){
    $this->str_homepage_verein = $str_homepage_verein;
  }

  function get_str_homepage_sparte(){
    return $this->str_homepage_sparte;
  }

  function set_str_homepage_sparte($str_homepage_sparte){
    $this->str_homepage_sparte = $str_homepage_sparte;
  }
}

?>