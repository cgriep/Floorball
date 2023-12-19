<?php
/**
 * Liga
 *
 * Datenobjekt dass die Daten zu einer Liga enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Liga {

  private $int_id             = NULL;
  private $int_id_kategorie   = NULL;
  private $str_kategorie      = NULL;
  private $int_id_saison      = NULL;
  private $str_saison         = NULL;
  private $str_name           = NULL;
  private $date_stichtag      = NULL;
  private $bool_stichtag_typ  = NULL;  // TRUE -> stichtag ist max / FALSE -> stichtag ist min
  private $int_id_klasse      = NULL;
  private $str_klasse         = NULL;
  private $str_kurzname       = NULL;
  private $int_id_spielsystem = NULL;
  private $str_name_system    = NULL;
  private $bool_weiblich      = NULL;
  private $int_ordnungsnr     = NULL;
  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id             = NULL,
                              $int_id_kategorie   = NULL,
                              $str_kategorie      = NULL,
                              $int_id_saison      = NULL,
                              $str_name           = NULL,
                              $date_stichtag      = NULL,
                              $int_id_klasse      = NULL,
                              $str_klasse         = NULL,
                              $str_kurzname       = NULL,
                              $int_id_spielsystem = NULL,
                              $bool_stichtag_typ  = NULL,
                              $bool_weiblich      = NULL,
                              $int_ordnungsnr     = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_kategorie($int_id_kategorie);
    $this->set_str_kategorie($str_kategorie);
    $this->set_int_id_saison($int_id_saison);
    $this->set_str_name($str_name);
    $this->set_date_stichtag($date_stichtag);
    $this->set_int_id_klasse($int_id_klasse);
    $this->set_str_klasse($str_klasse);
    $this->set_str_kurzname($str_kurzname);
    $this->set_int_id_spielsystem($int_id_spielsystem);
    $this->set_bool_stichtag_typ($bool_stichtag_typ);
    $this->set_bool_weiblich($bool_weiblich);
    $this->set_int_ordnungsnr($int_ordnungsnr);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_kategorie(){
    return $this->int_id_kategorie;
  }

  public function set_int_id_kategorie($int_id_kategorie){
    $this->int_id_kategorie = $int_id_kategorie;
  }

  public function get_str_kategorie(){
    return $this->str_kategorie;
  }

  public function set_str_kategorie($str_kategorie){
    $this->str_kategorie = $str_kategorie;
  }

  public function get_int_id_saison(){
    return $this->int_id_saison;
  }

  public function set_int_id_saison($str_saison){
    $this->int_id_saison = $str_saison;
  }

  public function get_str_name(){
    return $this->str_name;
  }

  public function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  public function get_date_stichtag($mysql = FALSE){
    if ($mysql) {
      return $this->date_stichtag;
    } else {
     $datum = $this->date_stichtag;
     $datum = sprintf("%02d.%02d.%04d",
                              substr($datum, 8, 2),
                              substr($datum, 5, 2),
                              substr($datum, 0, 4));
      return $datum;
    }
  }

  public function set_date_stichtag($date_stichtag){
    $datum = $date_stichtag;
    // wenn Format TT.MM.JJJJ ist
    if (preg_match('/(0[1-9]|[12][0-9]|3[01])[- \/\.](0[1-9]|1[012])[- \/\.](19|20)[0-9]{2}/', $datum)) {
      $this->date_stichtag = sprintf("%04d-%02d-%02d",
                                substr($datum, 6, 4),
                                substr($datum, 3, 2),
                                substr($datum, 0, 2));
    } else if (preg_match('/\b(19|20)?[0-9]{2}[- \/\.](0?[1-9]|1[012])[- \/\.](0?[1-9]|[12][0-9]|3[01])\b/', $datum)) {
      // wenn Format YYYY-MM-DD ist
      $this->date_stichtag = sprintf("%04d-%02d-%02d",
                                substr($datum, 0, 4),
                                substr($datum, 5, 2),
                                substr($datum, 8, 2));
    } else {
      $this->date_stichtag = NULL;
    }
  }

  public function get_int_id_klasse(){
    return $this->int_id_klasse;
  }

  public function set_int_id_klasse($int_id_klasse){
    $this->int_id_klasse = $int_id_klasse;
  }

  public function get_str_klasse(){
    return $this->str_klasse;
  }

  public function set_str_klasse($str_klasse){
    $this->str_klasse = $str_klasse;
  }

  public function get_str_kurzname(){
    return $this->str_kurzname;
  }

  public function set_str_kurzname($str_kurzname){
    $this->str_kurzname = $str_kurzname;
  }

  public function get_str_name_system(){
    return $this->str_name_system;
  }

  public function get_int_id_spielsystem(){
    return $this->int_id_spielsystem;
  }

  public function set_int_id_spielsystem($int_id_spielsystem){
    if ($int_id_spielsystem != NULL && $int_id_spielsystem != 0) {
      $this->int_id_spielsystem = $int_id_spielsystem;
      $this->str_name_system = DBM_Ligen::get_name_spielsystem($int_id_spielsystem);
    } else {
      $this->int_id_spielsystem = $int_id_spielsystem;
    }
  }

  public function set_bool_stichtag_typ($bool_stichtag_typ){
    if ($bool_stichtag_typ == NULL) {
      $this->bool_stichtag_typ = NULL;
    } else if ($bool_stichtag_typ) {
      $this->bool_stichtag_typ = TRUE;
    } else {
      $this->bool_stichtag_typ = FALSE;
    }
  }

  public function get_bool_stichtag_typ(){
    return $this->bool_stichtag_typ;
  }

  public function set_bool_weiblich($bool_weiblich) {
    if ($bool_weiblich === NULL) {
      $this->bool_weiblich = NULL;
    } else if ($bool_weiblich) {
      $this->bool_weiblich = TRUE;
    } else {
      $this->bool_weiblich = FALSE;
    }
  }

  public function get_bool_weiblich(){
    return $this->bool_weiblich;
  }

  public function get_int_ordnungsnr(){
    return $this->int_ordnungsnr;
  }

  public function set_int_ordnungsnr($int_ordnungsnr){
    $this->int_ordnungsnr = $int_ordnungsnr;
  }

}

?>