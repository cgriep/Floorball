<?php
/**
 * Mitspieler
 *
 * Datenobjekt dass die Daten zu einem Mitspieler enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Mitspieler {

  private $int_id             = NULL;
  private $int_id_begegnung   = NULL;
  private $int_id_spieler     = NULL;
  private $str_name           = NULL;
  private $str_vorname        = NULL;
  private $int_trikotnr       = NULL;
  private $bool_torwart       = NULL;
  private $bool_kapitain      = NULL;
  private $int_team           = NULL; // 1->Team1 2->Team2
  private $int_bearbeitungsnr = NULL;


  private static $arr_spieler = NULL;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id             = NULL,
                              $int_id_begegnung   = NULL,
                              $int_id_spieler     = NULL,
                              $int_trikotnr       = NULL,
                              $bool_torwart       = NULL,
                              $bool_kapitain      = NULL,
                              $int_team           = NULL,
                              $str_name           = NULL,
                              $str_vorname        = NULL,
                              $int_bearbeitungsnr = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_begegnung($int_id_begegnung);
    $this->set_int_id_spieler($int_id_spieler);
    $this->set_int_trikotnr($int_trikotnr);
    $this->set_bool_torwart($bool_torwart);
    $this->set_bool_kapitain($bool_kapitain);
    $this->set_int_team($int_team);
    $this->set_str_name($str_name);
    $this->set_str_vorname($str_vorname);
    $this->set_int_bearbeitungsnr($int_bearbeitungsnr);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_begegnung(){
    return $this->int_id_begegnung;
  }

  public function set_int_id_begegnung($int_id_begegnung) {
    $this->int_id_begegnung = $int_id_begegnung;
    if ($int_id_begegnung != NULL && $int_id_begegnung != 0) {
      if (self::$arr_spieler == NULL) {

        $Begegnung = DBMapper::read_Begegnung($int_id_begegnung);
        $arr_tmp = DBM_Spieler::get_list_Spieler_of_Mannschaft($Begegnung->get_int_id_mannschaft1(), 0,
                                                            $Begegnung->get_int_id_verband_team1());
        for ($i=0; $i<count($arr_tmp[0]); $i++) {
          self::$arr_spieler[$arr_tmp[0][$i]] = array($arr_tmp[1][$i], $arr_tmp[2][$i]);
        }
        $arr_tmp = DBM_Spieler::get_list_Spieler_of_Mannschaft($Begegnung->get_int_id_mannschaft2(), 0,
                                                            $Begegnung->get_int_id_verband_team2());
        for ($i=0; $i<count($arr_tmp[0]); $i++) {
          self::$arr_spieler[$arr_tmp[0][$i]] = array($arr_tmp[1][$i], $arr_tmp[2][$i]);
        }
      //self::$arr_spieler = DBM_Spieler::get_list_Spieler_by_Begegnung($int_id_begegnung);
      }
    }
  }

  public function get_int_id_spieler() {
    return $this->int_id_spieler;
  }

  public function set_int_id_spieler($int_id_spieler) {
    $this->int_id_spieler = $int_id_spieler;
    if ($int_id_spieler === NULL) {
      $this->str_name    = NULL;
      $this->str_vorname = NULL;
    } else if ( ($int_id_spieler > 0)
                 && is_array(self::$arr_spieler)
                 && array_key_exists($int_id_spieler, self::$arr_spieler) ) {
      $this->str_name    = self::$arr_spieler[$int_id_spieler][0];
      $this->str_vorname = self::$arr_spieler[$int_id_spieler][1];
    }
  }

  public function set_str_name($str_name){
    if (($this->int_id_spieler == 0) || ($str_name == "")) {
      return FALSE;
    } else {
      $this->str_name = $str_name;
      return true;
    }
  }

  public function get_str_name(){
    return $this->str_name;
  }

  public function set_str_vorname($str_vorname){
    if (($this->int_id_spieler < 0) || ($str_vorname == "")) {
      return FALSE;
    } else {
      $this->str_vorname = $str_vorname;
      return true;
    }
  }

  public function get_str_vorname(){
    return $this->str_vorname;
  }

  public function get_int_trikotnr(){
    return $this->int_trikotnr;
  }

  public function set_int_trikotnr($int_trikotnr){
    $this->int_trikotnr = $int_trikotnr;
  }

  public function get_bool_torwart(){
    return $this->bool_torwart;
  }

  public function set_bool_torwart($bool){
    $this->bool_torwart = $bool;
  }

  public function get_bool_kapitain(){
    return $this->bool_kapitain;
  }

  public function set_bool_kapitain($bool){
    $this->bool_kapitain = $bool;
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