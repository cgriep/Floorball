<?php
/**
 * Begegnung
 *
 * Datenobjekt dass die Daten zu einer Begegnung enthält.
 *
 * @package OBJEKTE
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Begegnung {

  private $int_id               = NULL;
  private $int_id_spieltag      = NULL;
  private $int_id_mannschaft1   = NULL;
  private $int_id_verband_team1 = NULL;
  private $str_mannschaft1_name = NULL;  // Wird durch die Team ID gesetzt
  private $int_id_mannschaft2   = NULL;
  private $str_mannschaft2_name = NULL;  // Wird durch die Team ID gesetzt
  private $int_id_verband_team2 = NULL;
  private $str_uhrzeit          = NULL;
  private $str_schiedsrichter   = NULL;
  private $int_spielnummer      = NULL;
  private $int_forfeit          = NULL;
  private $bool_genehmigt       = NULL;
  private $int_genehmigt_von    = NULL;  // ID des Users
  private $str_genehmigt_von    = NULL;  // Name des Users

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id             = NULL,
                       $int_id_spieltag    = NULL,
                       $int_id_mannschaft1 = NULL,
                       $int_id_mannschaft2 = NULL,
                       $str_uhrzeit        = NULL,
                       $str_schedsrichter  = NULL,
                       $int_spielnummer    = NULL,
                       $int_forfeit        = NULL,
                       $int_genehmigt      = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_spieltag($int_id_spieltag);
    $this->set_int_id_mannschaft1($int_id_mannschaft1);
    $this->set_int_id_mannschaft2($int_id_mannschaft2);
    $this->set_str_uhrzeit($str_uhrzeit);
    $this->set_str_schiedsrichter($str_schedsrichter);
    $this->set_int_spielnummer($int_spielnummer);
    $this->set_int_forfeit($int_forfeit);
    $this->set_int_genehmigt_von($int_genehmigt);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_spieltag(){
    return $this->int_id_spieltag;
  }

  public function set_int_id_spieltag($int_id_spieltag){
    $this->int_id_spieltag = $int_id_spieltag;
  }

  public function get_int_id_mannschaft1(){
    return $this->int_id_mannschaft1;
  }

  public function get_int_id_verband_team1(){
    return $this->int_id_verband_team1;
  }

  public function get_str_mannschaft1_name(){
    return $this->str_mannschaft1_name;
  }

  public function set_int_id_mannschaft1($int_id_mannschaft1, $int_id_verband_team1 = NULL){
    $this->int_id_mannschaft1 = $int_id_mannschaft1;
    if ($int_id_mannschaft1 != NULL) {
      $Mannschaft = DBMapper::read_Mannschaft($int_id_mannschaft1, $int_id_verband_team1);
      if ($Mannschaft != FALSE) {
        $this->str_mannschaft1_name = $Mannschaft->get_str_name();
        $this->int_id_verband_team1 = $int_id_verband_team1;
      } else {
        $this->str_mannschaft1_name = NULL;
        $this->int_id_verband_team1 = NULL;
      }
    } else {
      $this->str_mannschaft1_name = NULL;
      $this->int_id_verband_team1 = NULL;
    }
  }

  public function get_int_id_mannschaft2(){
    return $this->int_id_mannschaft2;
  }

  public function get_int_id_verband_team2(){
    return $this->int_id_verband_team2;
  }

  public function get_str_mannschaft2_name(){
    return $this->str_mannschaft2_name;
  }

  public function set_int_id_mannschaft2($int_id_mannschaft2, $int_id_verband_team2 = NULL){
    $this->int_id_mannschaft2 = $int_id_mannschaft2;
    if ($int_id_mannschaft2 != NULL) {
      $Mannschaft = DBMapper::read_Mannschaft($int_id_mannschaft2, $int_id_verband_team2);
      if ($Mannschaft != FALSE) {
        $this->str_mannschaft2_name = $Mannschaft->get_str_name();
        $this->int_id_verband_team2 = $int_id_verband_team2;
      } else {
        $this->str_mannschaft2_name = NULL;
        $this->int_id_verband_team2 = NULL;
      }
    } else {
      $this->str_mannschaft2_name = NULL;
      $this->int_id_verband_team2 = NULL;
    }
  }

  public function get_str_uhrzeit(){
    return $this->str_uhrzeit;
  }

  public function set_str_uhrzeit($str_uhrzeit){
    $this->str_uhrzeit = $str_uhrzeit;
  }

  public function get_str_schiedsrichter(){
    return $this->str_schiedsrichter;
  }

  public function set_str_schiedsrichter($str_schiedsrichter){
    $this->str_schiedsrichter = $str_schiedsrichter;
  }

  public function get_int_spielnummer(){
    return $this->int_spielnummer;
  }

  public function set_int_spielnummer($int_spielnummer){
    $this->int_spielnummer = $int_spielnummer;
  }

  public function get_int_forfeit(){
    return $this->int_forfeit;
  }

  public function set_int_forfeit($int_forfeit){
    $this->int_forfeit = $int_forfeit;
  }

  public function get_bool_genehmigt(){
    $this->bool_genehmigt;
  }

  public function get_str_genehmigt_von(){
    $this->str_genehmigt_von;
  }

  public function get_int_genehmigt_von(){
    $this->int_genehmigt_von;
  }

  public function set_int_genehmigt_von($int_id){
    $this->int_genehmigt_von = $int_id;
    if ($int_id == NULL) {
      $this->bool_genehmigt    = NULL;
      $this->str_genehmigt_von = NULL;
    } else {
      $this->bool_genehmigt = TRUE;
      $this->str_genehmigt_von = $_SESSION['arr_login']['user_name'];
    }
  }

}

?>