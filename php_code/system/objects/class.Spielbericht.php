<?php
/**
 * Spielbericht
 *
 * Datenobjekt dass die Daten zu einem Spielbericht enthält.
 *
 * @package OBJEKTE
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.1
 * @access public
 */
class Spielbericht {

  private $int_id             = NULL;
  private $int_bearbeitungsnr = NULL;

  private $int_id_begegnung    = NULL;
  private $date_datum          = NULL;  // wird durch $int_id_begegnung gesetzt
  private $str_team1           = NULL;  // wird durch $int_id_begegnung gesetzt
  private $str_team2           = NULL;  // wird durch $int_id_begegnung gesetzt
  private $str_uhrzeit         = NULL;  // wird durch $int_id_begegnung gesetzt
  private $int_spielnummer     = NULL;  // wird durch $int_id_begegnung gesetzt
  private $str_spielort        = NULL;  // wird durch $int_id_begegnung gesetzt
  private $str_liga            = NULL;  // wird durch $int_id_begegnung gesetzt

  private $int_id_schiri1   = NULL;
  private $int_id_schiri2   = NULL;
  private $str_schiri1_name = NULL;  // wird durch $int_id_schiri1 gesetzt
  private $str_schiri2_name = NULL;  // wird durch $int_id_schiri2 gesetzt

  private $bool_unterschrift_schiri1 = NULL;
  private $bool_unterschrift_Schiri2 = NULL;

  private $str_schiedsgericht1 = NULL;
  private $str_schiedsgericht2 = NULL;

  private $bool_unterschrift_schiedsgericht1 = NULL;
  private $bool_unterschrift_schiedsgericht2 = NULL;

  private $bool_besonderes_ereignis = NULL;

  private $str_timeout1 = NULL;
  private $str_timeout2 = NULL;

  private $bool_matchstrafe1 = NULL;
  private $bool_matchstrafe2 = NULL;
  private $bool_matchstrafe3 = NULL;

  private $bool_protest = NULL;

  private $bool_unterschrift_kaptain1 = NULL;
  private $bool_unterschrift_kaptain2 = NULL;

  private $bool_verlaengerung = NULL;

  private $Betreuer_team1        = NULL;
  private $Betreuer_team2        = NULL;

  private $arr_Mitspieler_team1 = array();  // Vom Typ Mitspieler
  private $arr_Mitspieler_team2 = array();  // Vom Typ Mitspieler

  private $arr_Ereignis = array();  // Vom Typ Ereignis

  private $str_kommentar = NULL;

  private $int_id_eingetragen_von = NULL;
  private $time_eingetragen_am    = NULL;

  /**
   * Constructor
   * @access protected
   */
  public function __construct($int_id                     = NULL,
                              $int_id_begegnung           = NULL,
                              $int_id_schiri1             = NULL,
                              $int_id_schiri2             = NULL,
                              $bool_unterschrift_schiri1  = NULL,
                              $bool_unterschrift_schiri2  = NULL,
                              $str_schiedsgericht1        = NULL,
                              $str_schiedsgericht2        = NULL,
                              $bool_unterschrift_schiedsgericht1 = NULL,
                              $bool_unterschrift_schiedsgericht2 = NULL,
                              $bool_besonderes_ereignis   = NULL,
                              $str_timeout1               = NULL,
                              $str_timeout2               = NULL,
                              $bool_matchstrafe1          = NULL,
                              $bool_matchstrafe2          = NULL,
                              $bool_matchstrafe3          = NULL,
                              $bool_protest               = NULL,
                              $bool_unterschrift_kaptain1 = NULL,
                              $bool_unterschrift_kaptain2 = NULL,
                              $bool_verlaengerung         = NULL,
                              $Betreuer_team1             = NULL,
                              $Betreuer_team2             = NULL,
                              $arr_Mitspieler_team1       = array(),
                              $arr_Mitspieler_team2       = array(),
                              $arr_Ereignis               = array(),
                              $str_kommentar              = NULL,
                              $int_bearbeitungsnr         = NULL){
    $this->set_int_id($int_id);
    $this->set_int_id_begegnung($int_id_begegnung);
    $this->set_int_id_schiri1($int_id_schiri1);
    $this->set_int_id_schiri2($int_id_schiri2);
    $this->set_bool_unterschrift_schiri1($bool_unterschrift_schiri1);
    $this->set_bool_unterschrift_schiri2($bool_unterschrift_schiri2);
    $this->set_str_schiedsgericht1($str_schiedsgericht1);
    $this->set_str_schiedsgericht2($str_schiedsgericht2);
    $this->set_bool_unterschrift_schiedsgericht1($bool_unterschrift_schiedsgericht1);
    $this->set_bool_unterschrift_schiedsgericht2($bool_unterschrift_schiedsgericht2);
    $this->set_bool_besonderes_ereignis($bool_besonderes_ereignis);
    $this->set_str_timeout1($str_timeout1);
    $this->set_str_timeout2($str_timeout2);
    $this->set_bool_matchstrafe1($bool_matchstrafe1);
    $this->set_bool_matchstrafe2($bool_matchstrafe2);
    $this->set_bool_matchstrafe3($bool_matchstrafe3);
    $this->set_bool_protest($bool_protest);
    $this->set_bool_unterschrift_kaptain1($bool_unterschrift_kaptain1);
    $this->set_bool_unterschrift_kaptain2($bool_unterschrift_kaptain2);
    $this->set_bool_verlaengerung($bool_verlaengerung);
    $this->set_Betreuer_team1($Betreuer_team1);
    $this->set_Betreuer_team2($Betreuer_team2);
    $this->set_arr_Mitspieler_team1($arr_Mitspieler_team1);
    $this->set_arr_Mitspieler_team2($arr_Mitspieler_team2);
    $this->set_arr_Ereignis($arr_Ereignis);
    $this->set_str_kommentar($str_kommentar);
    $this->set_int_bearbeitungsnr($int_bearbeitungsnr);
  }


  public function get_int_id(){
    return $this->int_id;
  }

  public function set_int_id($int_id){
    $this->int_id = $int_id;
  }

  public function get_int_id_begegnung() {
    return $this->int_id_begegnung;
  }

  public function get_str_team1() {
    return $this->str_team1;
  }

  public function get_str_team2() {
    return $this->str_team2;
  }

  public function get_date_datum(){
    return $this->date_datum;
  }

  public function get_str_uhrzeit() {
    return $this->str_uhrzeit;
  }

  public function get_int_spielnummer() {
    return $this->int_spielnummer;
  }

  public function get_str_spielort() {
    return $this->str_spielort;
  }

  public function set_int_id_begegnung($int_id) {
    $this->int_id_begegnung = $int_id;
    if ($int_id != NULL) {
      $Begegnung = DBMapper::read_Begegnung($int_id);
      $this->str_team1       = $Begegnung->get_str_mannschaft1_name();
      $this->str_team2       = $Begegnung->get_str_mannschaft2_name();
      $this->str_uhrzeit     = $Begegnung->get_str_uhrzeit();
      $this->int_spielnummer = $Begegnung->get_int_spielnummer();
      $this->str_spielort    = DBMapper::get_Spielort_by_begegnung($int_id);
      $this->date_datum      = DBMapper::get_Spieltagdatum($int_id);
      $this->str_liga        = DBM_Ligen::get_liga_by_begegnung($int_id);
    } else {
      $this->str_team1       = NULL;
      $this->str_team2       = NULL;
      $this->str_uhrzeit     = NULL;
      $this->int_spielnummer = NULL;
      $this->str_spielort    = NULL;
      $this->date_datum      = NULL;
      $this->str_liga        = NULL;
    }
  }

  public function get_str_liga(){
    return $this->str_liga;
  }

  public function get_int_id_schiri1(){
    return $this->int_id_schiri1;
  }

  public function get_int_id_schiri2(){
    return $this->int_id_schiri2;
  }

  public function get_str_schiri1_name(){
    return $this->str_schiri1_name;
  }

  public function get_str_schiri2_name(){
    return $this->str_schiri2_name;
  }

  public function set_str_schiri1_name($str_schiri_name){
    $this->str_schiri1_name = $str_schiri_name;
  }

  public function set_str_schiri2_name($str_schiri_name){
    $this->str_schiri2_name = $str_schiri_name;
  }

  public function set_int_id_schiri1($int_id){
    $this->int_id_schiri1 = $int_id;
    /*
    if ($int_id != NULL) {
      $Schiri = DBMapper::read_Schiedsrichter($int_id);
      $this->str_schiri1_name = DBMapper::get_schiriname($int_id);
    } else {
      $this->str_schiri1_name = NULL;
      }*/
  }

  public function set_int_id_schiri2($int_id){
    $this->int_id_schiri2 = $int_id;
    /* if ($int_id != NULL) {
      $Schiri = DBMapper::read_Schiedsrichter($int_id);
      $this->str_schiri2_name = DBMapper::get_schiriname($int_id);
    } else {
      $this->str_schiri2_name = NULL;
      }*/
  }

  public function get_bool_unterschrift_schiri1() {
    return $this->bool_unterschrift_schiri1;
  }

  public function get_bool_unterschrift_schiri2() {
    return $this->bool_unterschrift_schiri2;
  }

  public function set_bool_unterschrift_schiri1($bool) {
    $this->bool_unterschrift_schiri1 = $bool;
  }

  public function set_bool_unterschrift_schiri2($bool) {
    $this->bool_unterschrift_schiri2 = $bool;
  }

  public function get_str_schiedsgericht1() {
    return $this->str_schiedsgericht1;
  }

  public function set_str_schiedsgericht1($str_name) {
    $this->str_schiedsgericht1 = $str_name;
  }

  public function get_str_schiedsgericht2() {
    return $this->str_schiedsgericht2;
  }

  public function set_str_schiedsgericht2($str_name) {
    $this->str_schiedsgericht2 = $str_name;
  }

  public function get_bool_unterschrift_schiedsgericht1() {
    return $this->bool_unterschrift_schiedsgericht1;
  }

  public function set_bool_unterschrift_schiedsgericht1($bool) {
    $this->bool_unterschrift_schiedsgericht1 = $bool;
  }

  public function get_bool_unterschrift_schiedsgericht2() {
    return $this->bool_unterschrift_schiedsgericht2;
  }

  public function set_bool_unterschrift_schiedsgericht2($bool) {
    $this->bool_unterschrift_schiedsgericht2 = $bool;
  }

  public function get_bool_besonderes_ereignis() {
    return $this->bool_besonderes_ereignis;
  }

  public function set_bool_besonderes_ereignis($bool) {
    $this->bool_besonderes_ereignis = $bool;
  }

  public function get_str_timeout1() {
    return $this->str_timeout1;
  }

  public function set_str_timeout1($str_timeout) {
    $this->str_timeout1 = $str_timeout;
  }

  public function get_str_timeout2() {
    return $this->str_timeout2;
  }

  public function set_str_timeout2($str_timeout) {
    $this->str_timeout2 = $str_timeout;
  }

  public function get_bool_matchstrafe1() {
    return $this->bool_matchstrafe1;
  }

  public function set_bool_matchstrafe1($bool) {
    $this->bool_matchstrafe1 = $bool;
  }

  public function get_bool_matchstrafe2() {
    return $this->bool_matchstrafe2;
  }

  public function set_bool_matchstrafe2($bool) {
    $this->bool_matchstrafe2 = $bool;
  }

  public function get_bool_matchstrafe3() {
    return $this->bool_matchstrafe3;
  }

  public function set_bool_matchstrafe3($bool) {
    $this->bool_matchstrafe3 = $bool;
  }

  public function get_bool_protest() {
    return $this->bool_protest;
  }

  public function set_bool_protest($bool) {
    $this->bool_protest = $bool;
  }

  public function get_bool_unterschrift_kaptain1() {
    return $this->bool_unterschrift_kaptain1;
  }

  public function set_bool_unterschrift_kaptain1($bool) {
    $this->bool_unterschrift_kaptain1 = $bool;
  }

  public function get_bool_unterschrift_kaptain2() {
    return $this->bool_unterschrift_kaptain2;
  }

  public function set_bool_unterschrift_kaptain2($bool) {
    $this->bool_unterschrift_kaptain2 = $bool;
  }

  public function get_bool_verlaengerung() {
    return $this->bool_verlaengerung;
  }

  public function set_bool_verlaengerung($bool) {
    $this->bool_verlaengerung = $bool;
  }

  public function get_Betreuer_team1(){
    return $this->Betreuer_team1;
  }

  public function set_Betreuer_team1($Betreuer){
    if ($Betreuer == NULL) {
      $this->Betreuer_team1 = new Betreuer();
    } else {
      $this->Betreuer_team1 = $Betreuer;
    }
  }

  public function get_Betreuer_team2(){
    return $this->Betreuer_team2;
  }

  public function set_Betreuer_team2($Betreuer){
    if ($Betreuer == NULL) {
      $this->Betreuer_team2 = new Betreuer();
    } else {
      $this->Betreuer_team2 = $Betreuer;
    }
  }


  public function get_arr_Mitspieler_team1(){
    return $this->arr_Mitspieler_team1;
  }

  public function get_arr_Mitspieler_team2(){
    return $this->arr_Mitspieler_team2;
  }

  public function set_arr_Mitspieler_team1($arr_Mitspieler){
    $this->arr_Mitspieler_team1 = $arr_Mitspieler;
  }

  public function set_arr_Mitspieler_team2($arr_Mitspieler){
    $this->arr_Mitspieler_team2 = $arr_Mitspieler;
  }

  public function get_arr_Ereignis(){
    return $this->arr_Ereignis;
  }

  public function set_arr_Ereignis($arr_Ereignis){
    $this->arr_Ereignis = $arr_Ereignis;
  }

  public function get_str_kommentar(){
    return $this->str_kommentar;
  }

  public function set_str_kommentar($str_kommentar){
    $this->str_kommentar = $str_kommentar;
  }

  public function get_int_id_eingetragen_von(){
    return $this->int_id_eingetragen_von;
  }

  public function set_int_id_eingetragen_von($int_id_eingetragen_von){
    $this->int_id_eingetragen_von = $int_id_eingetragen_von;
  }

  public function get_time_eingetragen_am(){
    return $this->time_eingetragen_am;
  }

  public function set_time_eingetragen_am($time_eingetragen_am){
    $this->time_eingetragen_am = $time_eingetragen_am;
  }

  public function get_int_bearbeitungsnr(){
    return $int_bearbeitungsnr;
  }

  public function set_int_bearbeitungsnr($int_bearbeitungsnr){
    $this->int_bearbeitungsnr = $int_bearbeitungsnr;
  }

}

?>