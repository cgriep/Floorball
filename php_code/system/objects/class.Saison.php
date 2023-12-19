<?php
/**
 * Saison
 *
 * Datenobjekt dass die Daten zu einer Saison enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Saison {

  private $int_id_saison = NULL;
  private $str_name      = NULL;
  private $bool_gesperrt = FALSE;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id = NULL,
                       $str_name      = NULL,
                       $bool_gesperrt = FALSE) {
    $this->set_int_id($int_id);
    $this->set_str_name($str_name);
    $this->set_bool_gesperrt($bool_gesperrt);
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

  public function get_bool_gesperrt() {
    return $bool_gesperrt;
  }

  public function set_bool_gesperrt($bool_gesperrt) {
    $this->bool_gesperrt = $bool_gesperrt;
  }

}

?>