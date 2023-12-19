<?php

require_once('class.DB.php');

/**
 * DBM_root
 *
 * Diese Klasse stellt die Grundlage für alle DBMapper dar. Hier werden die
 * offenen Verbindunge verwaltet.
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_root {

  // Für jede DB und Benutzer eine Variable
  private static $db_objekt_benutzer_r  = NULL;
  private static $db_objekt_benutzer_rw = NULL;

  private static $db_objekt_r  = NULL;
  private static $db_objekt_rw = NULL;


  protected static function db_connect($datenbank = DB_NAME,
                                       $db_user_type = "r") {
    // Prüfen zu welcher DB verbunden werden soll
    if ($datenbank == DB_NAME) {
      $db_host = DB_HOST;
      $db_port = DB_PORT;
      $db_name = DB_NAME;

      if ($db_user_type == "rw" || $db_user_type == "RW") {
        $db_objekt_name = "db_objekt_rw";
        $db_user   = DB_USER_RW;
        $db_passwd = DB_PASSWD_RW;
      } else {
        $db_objekt_name = "db_objekt_r";
        $db_user   = DB_USER_R;
        $db_passwd = DB_PASSWD_R;
      }
    } else { // if ($datenbank == DB_USERS_NAME)
      $db_host = DB_USERS_HOST;
      $db_port = DB_USERS_PORT;
      $db_name = DB_USERS_NAME;

      if ($db_user_type == "rw" || $db_user_type == "RW") {
        $db_objekt_name = "db_objekt_benutzer_rw";
        $db_user   = DB_USERS_USER_RW;
        $db_passwd = DB_USERS_PASSWD_RW;
      } else {
        $db_objekt_name = "db_objekt_benutzer_r";
        $db_user   = DB_USERS_USER_R;
        $db_passwd = DB_USERS_PASSWD_R;
      }
    }

    if ( (self::$$db_objekt_name != NULL)
         && (self::$$db_objekt_name->get_dblink() != FALSE)
         && @mysql_ping(self::$$db_objekt_name->get_dblink()) ) {
      return self::$$db_objekt_name;
    }

    $db = new DB($db_host, $db_port, $db_user, $db_passwd, $db_name);
    self::$$db_objekt_name = $db;
    return self::$$db_objekt_name;

  }


  final protected static function add_quotes($value){
    if ($value === NULL || $value === "") {
      return "NULL";
    } else {
      return "'". $value ."'";
    }
  }


  /**
  *
  * Hier wird der SQL-SET Sub-String gebaut
  *
  * $array -> (SQL-Feld => Var-name im Objekt)
  *
  */
  final protected static function build_set($array, $Objekt) {
    $str_set = "SET ";
    $first = TRUE;
    foreach($array as $key => $value ) {
      if ($first) {
        $first = FALSE;
      } else {
        $str_set .= ",\n";
      }
      if ($key == "geb_datum") {
        $str_set .= "geb_datum = ". self::add_quotes($Objekt->get_date_geb_datum(TRUE));
      } else {
        $function_name = "get_" . $value;
        $str_set .= $key . " = " . self::add_quotes($Objekt->$function_name());
      }
    }
    return $str_set;
  }


}

?>