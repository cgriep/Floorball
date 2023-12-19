<?php
/**
 * Diese Datei wird von der list.php des jeweiligen Verbandes eingebunden.
 *
 * @package    SYSTEM
 */

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}

  function __autoload($class_name) {
    require_once 'class.'. $class_name .'.php';
  }

  // Start der Session
  session_start();

  // Hier stehen die Includes/Requires, die für alle User gültig sind
  require_once('constants_userdb.php');
  require_once('constants_global.php');

  // Aktuelle Saison setzen wenn noch nicht geschehen
  if (!isset($_SESSION['str_saison'])) {
    if ($Saison = DBMapper_Saison::get_aktuelle_Saison()) {
      $_SESSION['str_saison'] = $Saison->get_str_name();
      $_SESSION['id_saison']  = $Saison->get_int_id();
    } else {
      // Fehlermeldung generieren
      $_SESSION['str_saison'] = "2010/2011";
      $_SESSION['id_saison']  = 0;
    }
  }

  $str_prefix = $_SESSION['str_saison'];
  $str_prefix = str_replace("/", "_", $str_prefix) . "_";
  define('PREFIX_SAISON', $str_prefix);
  unset($str_prefix);

  // Kann erst nach setzen der aktuellen Saison eingebunden werden
  require_once('constants_global_season.php');
  require_once('constants_verband.php');

  if ( isset($_GET['table']))
  {
  	// Spielplan anzeigen
  	PDF::gen_Spielplan($_GET['table']);

  }
  // Prüfen ob der Benutzer eingeloggt ist
  elseif (Login::isloggedin()) {

    $liga = $_GET['liga'];
	
    PDF::gen_lizenzliste($liga);

  } else {

    echo "Es gab ein Problem beim Erstellen des Liste: <br /><br />";
    echo "Sie sind nicht eingeloggt.";

  }


?>