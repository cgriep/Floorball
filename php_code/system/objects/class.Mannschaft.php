<?php
/**
 * Mannschaft
 *
 * Datenobjekt dass die Daten zu einer Mannschaft enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Mannschaft {

  private $int_id           = NULL;
  private $int_id_verein    = NULL;
  private $int_id_liga      = NULL;
  private $int_id_betreuer  = NULL;
  private $str_name         = NULL;
  private $str_kurzname     = NULL;
  private $bool_genehmigt   = FALSE;
  private $int_angelegt_von = NULL;
  private $date_angelegt_am = NULL;
  private $bool_sg          = FALSE;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $int_id_verein = NULL,
                       $int_id_liga = NULL,
                       $int_id_betreuer = NULL,
                       $str_name = NULL,
                       $str_kurzname = NULL,
                       $bool_genehmigt = FALSE,
                       $int_angelegt_von = NULL,
                       $date_angelegt_am = NULL,
                       $bool_sg = FALSE){
    $this->set_int_id($int_id);
    $this->set_int_id_verein($int_id_verein);
    $this->set_int_id_liga($int_id_liga);
    $this->set_int_id_betreuer($int_id_betreuer);
    $this->set_str_name($str_name);
    $this->set_str_kurzname($str_kurzname);
    $this->set_bool_genehmigt($bool_genehmigt);
    $this->set_int_angelegt_von($int_angelegt_von);
    $this->set_date_angelegt_am($date_angelegt_am);
    $this->set_bool_sg($bool_sg);
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
  }

  public function get_int_id_liga(){
    return $this->int_id_liga;
  }

  public function set_int_id_liga($int_id_liga){
    $this->int_id_liga = $int_id_liga;
  }

  public function get_int_id_betreuer(){
    return $this->int_id_betreuer;
  }

  public function set_int_id_betreuer($int_id_betreuer){
    $this->int_id_betreuer = $int_id_betreuer;
  }

  public function get_str_name(){
    return $this->str_name;
  }

  public function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  public function get_str_kurzname(){
    return $this->str_kurzname;
  }

  public function set_str_kurzname($str_kurzname){
    $this->str_kurzname = $str_kurzname;
  }

  public function get_bool_genehmigt(){
    return $this->bool_genehmigt;
  }

  public function set_bool_genehmigt($bool_genehmigt){
    // hack: if we correct it bool_genehmigt should be set to true
    // but currently 2 means the team have to be ignored
    if ($bool_genehmigt) {
      $this->bool_genehmigt = $bool_genehmigt;
    } else {
      $this->bool_genehmigt = FALSE;
    }
  }

  public function get_int_angelegt_von(){
    return $this->int_angelegt_von;
  }

  public function set_int_angelegt_von($int_angelegt_von){
    $this->int_angelegt_von = $int_angelegt_von;
  }

  public function get_date_angelegt_am(){
    return $this->date_angelegt_am;
  }

  public function set_date_angelegt_am($date_angelegt_am){
    $this->date_angelegt_am = $date_angelegt_am;
  }

  public function get_bool_sg(){
    return $this->bool_sg;
  }

  public function set_bool_sg($bool_sg){
    if ($bool_sg) {
      $this->bool_sg = TRUE;
    } else {
      $this->bool_sg = FALSE;
    }
  }


}

?>