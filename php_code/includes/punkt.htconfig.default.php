<?php

/****************************************************************************
*                                                                           *
* Hier stehen alle Konfigurationen                                          *
*                                                                           *
*                                                                           *
****************************************************************************/

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}


// achtung VERBAND muss vor dieser Datei gesetzt sein

// VERBAND und LOCALSYS müssen vor dem Include der 'phpini.php' geschehen
define('LOCALSYS', FALSE);
define('TESTSYSTEM', FALSE);
define('DEBUG', FALSE);

// load additional php settings
require_once(rootdir.'includes/phpini.php');

// setzen des DEFINES für den sicheren Login
if (LOCALSYS) {
  define('SSLURL', '');
  //  define('SSLURL', 'index.php');
} else {
  define('SSLURL', '');
//  define('SSLURL', 'https://ssl.sm-u.de/'. VERBAND ."/dologin.php");
}

define('SQL_LOG', TRUE);
define('SQL_LOG_TYPE', 0); // 0 := nur Fehlerhafte Anfgragen, 1 := alle Anfragen

// Liste von den Verbänden
// @todo sollte aus der DB kommen
$arr_ids_verband = array(1  => "fvd",
                         2  => "fvn",
                         3  => "fvbb",
                         4  => "fvbw",
                         5  => "flvsh",
                         6  => "sbkost",
                         8  => "fvh",
                         9  => "fvb",
                         10 => "nwuv");


// Auflistung aller Konfigurationen für die Verbände
$arr_config = array();

// Produtiv-System
$arr_config["fvd"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_6",
  "db_users_user_rw" => "p101280d2",
  "db_users_passwd_rw" => "AGMtne",
  "db_users_user_r" => "p101280d3",
  "db_users_passwd_r" => "WllDCe");

$arr_config["fvn"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_8",
  "db_users_user_rw" => "p101280d6",
  "db_users_passwd_rw" => "u03058",
  "db_users_user_r" => "p101280d7",
  "db_users_passwd_r" => "ZodxSh");

$arr_config["fvbb"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_10",
  "db_users_user_rw" => "p101280d10",
  "db_users_passwd_rw" => "6idtTX",
  "db_users_user_r" => "p101280d11",
  "db_users_passwd_r" => "RxP1jm");

$arr_config["fvbw"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_12",
  "db_users_user_rw" => "p101280d14",
  "db_users_passwd_rw" => "Uxj3Zw",
  "db_users_user_r" => "p101280d15",
  "db_users_passwd_r" => "AJXclI");

$arr_config["flvsh"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_14",
  "db_users_user_rw" => "p101280d18",
  "db_users_passwd_rw" => "JOTG8ILm",
  "db_users_user_r" => "p101280d19",
  "db_users_passwd_r" => "XznNFwxE");

// sbkost (uhb und suv)
$arr_config["sbkost"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_16",
  "db_users_user_rw" => "p101280d22",
  "db_users_passwd_rw" => "jcnjM7MI",
  "db_users_user_r" => "p101280d23",
  "db_users_passwd_r" => "FzBapYgN");

$arr_config["fvh"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_20",
  "db_users_user_rw" => "p101280d30",
  "db_users_passwd_rw" => "zbSoKFb4",
  "db_users_user_r" => "p101280d31",
  "db_users_passwd_r" => "ltyO6I7u");

$arr_config["fvb"] = array(
  "db_users_host" => "db1040.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_22",
  "db_users_user_rw" => "p101280d34",
  "db_users_passwd_rw" => "qArq2Syw",
  "db_users_user_r" => "p101280d35",
  "db_users_passwd_r" => "l7RHCFu7");

$arr_config["nwuv"] = array(
  "db_users_host" => "db1042.mydbserver.com",
  "db_users_port" => ":3306",
  "db_users_name" => "usr_p101280_24",
  "db_users_user_rw" => "p101280d41",
  "db_users_passwd_rw" => "1H16T3YG",
  "db_users_user_r" => "p101280d42",
  "db_users_passwd_r" => "9J196N31");


if (defined('TESTSYSTEM') && TESTSYSTEM) {
  define('DB_USERS_HOST', "db1040.mydbserver.com");
  define('DB_USERS_PORT', ":3306");
  define('DB_USERS_NAME', "usr_p101280_18");

  // der Login mit lese Rechten
  define('DB_USERS_USER_R', "p101280d27");
  define('DB_USERS_PASSWD_R', "X2AuYAl1");

  // der Login mit allen Rechten
  define('DB_USERS_USER_RW', "p101280d26");
  define('DB_USERS_PASSWD_RW', "GECz8GiL");


  // Datenbank Config Saisondaten etc.
  define('DB_HOST', "db1040.mydbserver.com");
  define('DB_PORT', ":3306");
  define('DB_NAME', "usr_p101280_19");

  // der Login mit lese Rechten
  define('DB_USER_R', "p101280d29");
  define('DB_PASSWD_R', "B7yjIDzL");

  // der Login mit allen Rechten
  define('DB_USER_RW', "p101280d28");
  define('DB_PASSWD_RW', "MiqMWRDD");


  // Datenbank Config fürs Logging
  //if (LOCALSYS) {
  //  define('DB_LOG_HOST', 'localhost');
  //} else {
  define('DB_LOG_HOST', 'db1040.mydbserver.com');
  //}
  define('DB_LOG_PORT', ':3306');
  define('DB_LOG_NAME', 'usr_p101280_7');
  define('DB_LOG_USER', 'p101280d4');
  define('DB_LOG_PASSWD', 'CIPvBE');

} else {

  define('DB_USERS_HOST', $arr_config[VERBAND]["db_users_host"]);
  define('DB_USERS_PORT', $arr_config[VERBAND]["db_users_port"]);
  define('DB_USERS_NAME', $arr_config[VERBAND]["db_users_name"]);

  // der Login mit lese Rechten
  define('DB_USERS_USER_R', $arr_config[VERBAND]["db_users_user_r"]);
  define('DB_USERS_PASSWD_R', $arr_config[VERBAND]["db_users_passwd_r"]);

  // der Login mit allen Rechten
  define('DB_USERS_USER_RW', $arr_config[VERBAND]["db_users_user_rw"]);
  define('DB_USERS_PASSWD_RW', $arr_config[VERBAND]["db_users_passwd_rw"]);


  // Datenbank Config Saisondaten etc.
  define('DB_HOST', "db1040.mydbserver.com");
  define('DB_PORT', ":3306");
  define('DB_NAME', "usr_p101280_9");

  // der Login mit lese Rechten
  define('DB_USER_R', "p101280d9");
  define('DB_PASSWD_R', "lejNaW");

  // der Login mit allen Rechten
  define('DB_USER_RW', "p101280d8");
  define('DB_PASSWD_RW', "QW3zyv");

}

// Datenbank Config fürs Logging
//if (LOCALSYS) {
//  define('DB_LOG_HOST', 'localhost');
//} else {
define('DB_LOG_HOST', 'db1040.mydbserver.com');
//}
define('DB_LOG_PORT', ':3306');
define('DB_LOG_NAME', 'usr_p101280_7');
define('DB_LOG_USER', 'p101280d4');
define('DB_LOG_PASSWD', 'CIPvBE');


?>