<?php
/**
 * Ereignis
 *
 * Datenobjekt dass die Daten zu einem Ereignis enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Ereignis {

  private static $arr_strafe    = NULL;
  private static $arr_strafcode = NULL;

  private $int_id             = NULL;
  private $int_id_begegnung   = NULL;
  private $int_zeile          = NULL;
  private $int_nr_team1       = NULL;
  private $int_ass_team1      = NULL;
  private $int_periode        = NULL;
  private $str_zeit           = NULL;
  private $int_tore_team1     = NULL;
  private $int_tore_team2     = NULL;
  private $int_id_strafe      = NULL;
  private $str_strafe         = NULL;  // Wird durch den int_id_strafe gesetzt
  private $int_id_strafcode   = NULL;
  private $int_strafcode      = NULL;  // Wird durch den int_id_strafcode gesetzt
  private $int_nr_team2       = NULL;
  private $int_ass_team2      = NULL;
  private $int_bearbeitungsnr = NULL;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id             = NULL,
                              $int_id_begegnung   = NULL,
                              $int_zeile          = NULL,
                              $int_nr_team1       = NULL,
                              $int_ass_team1      = NULL,
                              $int_periode        = NULL,
                              $str_zeit           = NULL,
                              $int_tore_team1     = NULL,
                              $int_tore_team2     = NULL,
                              $int_id_strafe      = NULL,
                              $int_id_strafcode   = NULL,
                              $int_nr_team2       = NULL,
                              $int_ass_team2      = NULL,
                              $int_bearbeitungsnr = NULL){
    if (self::$arr_strafe == NULL) {
      self::$arr_strafe = self::get_list_strafe();
    }
    if (self::$arr_strafcode == NULL) {
      self::$arr_strafcode = self::get_list_strafcode();
    }
    $this->set_int_id($int_id);
    $this->set_int_id_begegnung($int_id_begegnung);
    $this->set_int_zeile($int_zeile);
    $this->set_int_nr_team1($int_nr_team1);
    $this->set_int_ass_team1($int_ass_team1);
    $this->set_int_periode($int_periode);
    $this->set_str_zeit($str_zeit);
    $this->set_int_tore_team1($int_tore_team1);
    $this->set_int_tore_team2($int_tore_team2);
    $this->set_int_id_strafe($int_id_strafe);
    $this->set_int_id_strafcode($int_id_strafcode);
    $this->set_int_nr_team2($int_nr_team2);
    $this->set_int_ass_team2($int_ass_team2);
    $this->set_int_bearbeitungsnr($int_bearbeitungsnr);
  }

  private static function get_list_strafe(){
    $arr_tmp_strafe = DBMapper::get_list_strafe();
    foreach( $arr_tmp_strafe as $strafe ){
      $arr_strafe[$strafe[0]] = $strafe[1];
    }
    return $arr_strafe;
  }

  private static function get_list_strafcode(){
    $arr_tmp_strafcode = DBMapper::get_list_strafcode();
    foreach( $arr_tmp_strafcode as $strafcode ){
      $arr_strafcode[$strafcode[0]] = $strafcode[1];
    }
    return $arr_strafcode;
  }

  private static function get_strafe($int_id){
    return "";//self::$arr_strafe[$int_id];
  }

  private static function get_strafcode($int_id){
    return "";//self::$arr_strafcode[$int_id];
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

  public function set_int_id_begegnung($int_id_begegnung){
    $this->int_id_begegnung = $int_id_begegnung;
  }

  public function get_int_zeile(){
    return $this->int_zeile;
  }

  public function set_int_zeile($int_zeile){
    $this->int_zeile = $int_zeile;
  }

  public function get_int_nr_team1(){
    if($this->int_nr_team1 == 0) return "-";
    return $this->int_nr_team1;
  }

  public function set_int_nr_team1($int_nr_team1){
    $this->int_nr_team1 = $int_nr_team1;
  }

  public function get_int_ass_team1(){
    if($this->int_ass_team1 == 0) return "-";
    return $this->int_ass_team1;
  }

  public function set_int_ass_team1($int_ass_team1){
    $this->int_ass_team1 = $int_ass_team1;
  }

  public function get_int_periode(){
    return $this->int_periode;
  }

  public function set_int_periode($int_periode){
    $this->int_periode = $int_periode;
  }

  public function get_str_zeit(){
    return $this->str_zeit;
  }

  public function set_str_zeit($str_zeit){
    $this->str_zeit = $str_zeit;
  }

  public function get_int_tore_team1(){
    return $this->int_tore_team1;
  }

  public function set_int_tore_team1($int_tore_team1){
    $this->int_tore_team1 = $int_tore_team1;
  }

  public function get_int_tore_team2(){
    return $this->int_tore_team2;
  }

  public function set_int_tore_team2($int_tore_team2){
    $this->int_tore_team2 = $int_tore_team2;
  }

  public function get_int_id_strafe(){
    if($this->int_id_strafe == 0) return "-";
    return $this->int_id_strafe;
  }

  public function get_str_strafe(){
    return $this->str_strafe;
  }

  public function set_int_id_strafe($int_id_strafe){
    $this->int_id_strafe = $int_id_strafe;
    if ($int_id_strafe == NULL) {
      $this->str_strafe = NULL;
    } else {
      $this->str_strafe = self::get_strafe($int_id_strafe);
    }
  }

  public function get_int_id_strafcode(){
    if($this->int_id_strafcode == 0) return "-";
    return $this->int_id_strafcode;
  }

  public function get_int_strafcode(){
    return $this->int_strafcode;
  }

  public function set_int_id_strafcode($int_id_strafcode){
    $this->int_id_strafcode = $int_id_strafcode;
    if ($int_id_strafcode == NULL) {
      $this->int_strafcode = NULL;
    } else {
      $this->int_strafcode = self::get_strafcode($int_id_strafcode);
    }
  }

  public function get_int_nr_team2(){
    if($this->int_nr_team2 == 0) return "-";
    return $this->int_nr_team2;
  }

  public function set_int_nr_team2($int_nr_team2){
    $this->int_nr_team2 = $int_nr_team2;
  }

  public function get_int_ass_team2(){
    if($this->int_ass_team2 == 0) return "-";
    return $this->int_ass_team2;
  }

  public function set_int_ass_team2($int_ass_team2){
    $this->int_ass_team2 = $int_ass_team2;
  }

  public function get_int_bearbeitungsnr(){
    return $this->int_bearbeitungsnr;
  }

  public function set_int_bearbeitungsnr($int_bearbeitungsnr){
    $this->int_bearbeitungsnr = $int_bearbeitungsnr;
  }

}

?>