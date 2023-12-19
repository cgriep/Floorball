<?php
/**
 * Spieltag
 *
 * Datenobjekt dass die Daten zu einem Spieltag enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Spieltag {

  private $int_id          = NULL;
  private $int_id_spielort = NULL;
  private $int_id_liga     = NULL;
  private $date_datum      = NULL; // YYYY-MM-DD
  private $str_spieltag_nr = NULL;

  /**
   * Constructor
   * @access protected
   */
  function __construct($int_id = NULL,
                       $int_id_spielort = NULL,
                       $int_id_liga = NULL,
                       $date_datum = NULL,
                       $str_spieltag_nr = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_spielort($int_id_spielort);
    $this->set_int_id_liga($int_id_liga);
    $this->set_date_datum($date_datum);
    $this->set_str_spieltag_nr($str_spieltag_nr);
  }


  function get_int_id(){
    return $this->int_id;
  }

  function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  function get_int_id_spielort(){
    return $this->int_id_spielort;
  }

  function set_int_id_spielort($int_id_spielort){
    $this->int_id_spielort = $int_id_spielort;
  }

  function get_int_id_liga(){
    return $this->int_id_liga;
  }

  function set_int_id_liga($int_id_liga){
    $this->int_id_liga = $int_id_liga;
  }

  public function get_date_datum($mysql = FALSE){
    if ($mysql) {
      return $this->date_datum;
    } else {
     $datum = $this->date_datum;
     $datum = sprintf("%02d.%02d.%04d",
                              substr($datum, 8, 2),
                              substr($datum, 5, 2),
                              substr($datum, 0, 4));
      return $datum;
    }
  }

  public function set_date_datum($date_datum){
    $datum = $date_datum;

    // wenn Format TT.MM.JJJJ ist
    if (preg_match('/(0[1-9]|[12][0-9]|3[01])[- \/\.](0[1-9]|1[012])[- \/\.](19|20)[0-9]{2}/', $datum)) {
      $this->date_datum = sprintf("%04d-%02d-%02d",
                                substr($datum, 6, 4),
                                substr($datum, 3, 2),
                                substr($datum, 0, 2));
    } else if (preg_match('/\b(19|20)?[0-9]{2}[- \/\.](0?[1-9]|1[012])[- \/\.](0?[1-9]|[12][0-9]|3[01])\b/', $datum)) {
      // wenn Format YYYY-MM-DD ist
      $this->date_datum = sprintf("%04d-%02d-%02d",
                                substr($datum, 0, 4),
                                substr($datum, 5, 2),
                                substr($datum, 8, 2));
    } else {
      $this->date_datum = NULL;
    }
  }

  function get_str_spieltag_nr(){
    return $this->str_spieltag_nr;
  }

  function set_str_spieltag_nr($str_spieltag_nr){
    $this->str_spieltag_nr = $str_spieltag_nr;
  }

}

?>