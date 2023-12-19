<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}


 // system settings
 ini_set("register_globals", 0);
 ini_set("arg_separator.output", "&amp;");

 if ($_ENV['windir'] != "") {
   ini_set("include_path", ".;..\\php_code\\;..\\php_code\\includes\\;..\\php_code\\system;..\\php_code\\system\\objects;..\\php_code\\system\\db;..\\php_code\\system\\html;..\\php_code\\system\\tcpdf");
 } else {
   ini_set("include_path", ".:../php_code:../php_code/includes:../php_code/system:../php_code/system/objects:../php_code/system/db:../php_code/system/html:../php_code/system/tcpdf");
 }


 // error settings
 if (defined('DEBUG') && DEBUG) {
   ini_set("display_errors", 1);
   error_reporting(E_ALL);
 } else {
   ini_set("display_errors", 0);
   error_reporting(2047);
 }

 // error log
 if ( !defined('LOCALSYS') || ! LOCALSYS ) {
   ini_set("log_errors", 1);
   if (defined('TESTSYSTEM') && TESTSYSTEM) {
     ini_set("error_log", "/html/sm-u_test/". VERBAND ."/php_logs/error.log");
   } else {
     ini_set("error_log", "/html/sm-u/". VERBAND ."/php_logs/error.log");
   }
 }

 // session setting
 if (TESTSYSTEM) {
   ini_set("session.name", "SM-U_TEST_".VERBAND);
 } else {
   ini_set("session.name", "SM-U_".VERBAND);
 }


?>