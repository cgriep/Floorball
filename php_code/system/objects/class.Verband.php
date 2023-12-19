<?php
/**
 * Verband
 *
 * Datenobjekt dass die Daten zu einem Verband enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Verband {

  private $int_id        = NULL;
  private $str_name      = NULL;
  private $str_kuerzel   = NULL;
  private $str_subdomain = NULL;
  private $str_pfad      = NULL;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $str_name = NULL,
                       $str_kuerzel = NULL,
                       $str_subdomain = NULL,
                       $str_pfad = NULL) {
    $this->set_int_id($int_id);
    $this->set_str_name($str_name);
    $this->set_str_kuerzel($str_kuerzel);
    $this->set_str_subdomain($str_subdomain);
    $this->set_str_pfad($str_pfad);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_str_name(){
    return $this->str_name;
  }

  public function set_str_name($str_name){
    $this->str_name = $str_name;
  }

  public function get_str_kuerzel(){
    return $this->str_kuerzel;
  }

  public function set_str_kuerzel($str_kuerzel){
    $this->str_kuerzel = $str_kuerzel;
  }

  public function get_str_subdomain() {
    return $this->str_subdomain;
  }

  public function set_str_subdomain($str_subdomain) {
    $this->str_subdomain = $str_subdomain;
  }

  public function get_str_pfad(){
    return $this->str_pfad;
  }

  public function set_str_pfad($str_pfad){
    $this->str_pfad = $str_pfad;
  }

}

?>