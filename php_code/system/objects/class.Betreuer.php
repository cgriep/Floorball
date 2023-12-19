<?php
/**
 * Betreuer
 *
 * Datenobjekt dass die Daten zu einem Betreuer enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Betreuer {

  private $int_id             = NULL;
  private $int_id_begegnung   = NULL;
  private $str_betreuer1      = "Betreuer 1";
  private $str_betreuer2      = "Betreuer 2";
  private $str_betreuer3      = "Betreuer 3";
  private $str_betreuer4      = "Betreuer 4";
  private $str_betreuer5      = "Betreuer 5";
  private $bool_unterschrift  = FALSE;
  private $int_team           = NULL;
  private $int_bearbeitungsnr = NULL;

  /**
   * Constructor
   *
   * @param int    $int_id              ID des Benutzers
   * @param int    $int_id_begegnung    ID der Begegnung
   * @param string $str_betreuer1       Name des ersten Betreuers
   * @param string $str_betreuer2       Name des zweiten Betreuers
   * @param string $str_betreuer3       Name des dritten Betreuers
   * @param string $str_betreuer4       Name des vierten Betreuers
   * @param string $str_betreuer5       Name des fünften Betreuers
   * @param bool   $bool_unterschrift
   * @param int    $int_team
   * @param int    $int_bearbeitungsnr
   */
  public function __construct($int_id             = NULL,
                              $int_id_begegnung   = NULL,
                              $str_betreuer1      = "Betreuer 1",
                              $str_betreuer2      = "Betreuer 2",
                              $str_betreuer3      = "Betreuer 3",
                              $str_betreuer4      = "Betreuer 4",
                              $str_betreuer5      = "Betreuer 5",
                              $bool_unterschrift  = FALSE,
                              $int_team           = NULL,
                              $int_bearbeitungsnr = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_begegnung($int_id_begegnung);
    $this->set_str_betreuer1($str_betreuer1);
    $this->set_str_betreuer2($str_betreuer2);
    $this->set_str_betreuer3($str_betreuer3);
    $this->set_str_betreuer4($str_betreuer4);
    $this->set_str_betreuer5($str_betreuer5);
    $this->set_bool_unterschrift($bool_unterschrift);
    $this->set_int_team($int_team);
    $this->set_int_bearbeitungsnr($int_bearbeitungsnr);
  }


  /**
   * Betreuer::get_int_id()
   *
   * @return int
   */
  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_begegnung(){
    return $this->int_id_begegnung;
  }

  public function set_int_id_begegnung($int_id_begegnung){
    $this->int_id_begegnung = $int_id_begegnung;
  }

  public function get_str_betreuer1(){
    return $this->str_betreuer1;
  }

  public function set_str_betreuer1($str_betreuer){
    $this->str_betreuer1 = $str_betreuer;
  }

  public function get_str_betreuer2(){
    return $this->str_betreuer2;
  }

  public function set_str_betreuer2($str_betreuer){
    $this->str_betreuer2 = $str_betreuer;
  }

  public function get_str_betreuer3(){
    return $this->str_betreuer3;
  }

  public function set_str_betreuer3($str_betreuer){
    $this->str_betreuer3 = $str_betreuer;
  }

  public function get_str_betreuer4(){
    return $this->str_betreuer4;
  }

  public function set_str_betreuer4($str_betreuer){
    $this->str_betreuer4 = $str_betreuer;
  }

  public function get_str_betreuer5(){
    return $this->str_betreuer5;
  }

  public function set_str_betreuer5($str_betreuer){
    $this->str_betreuer5 = $str_betreuer;
  }

  public function get_bool_unterschrift(){
    return $this->bool_unterschrift;
  }

  public function set_bool_unterschrift($bool){
    $this->bool_unterschrift = $bool;
  }

  public function get_int_team(){
    return $this->int_team;
  }

  public function set_int_team($int_team){
    switch($int_team){
      case 1:
        $this->int_team = 1;
        break;
      case 2:
        $this->int_team = 2;
        break;
      default:
        $this->int_team = NULL;
    } // switch
  }

  public function get_int_bearbeitungsnr(){
    return $this->int_bearbeitungsnr;
  }

  public function set_int_bearbeitungsnr($int_bearbeitungsnr){
    $this->int_bearbeitungsnr = $int_bearbeitungsnr;
  }

}

?>