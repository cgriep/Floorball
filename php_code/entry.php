<?php
/**
 * Diese Datei wird von der index.php des jeweiligen Verbandes eingebunden.
 *
 * @package    SYSTEM
 */

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}
if ( isset($_GET['seite']) && $_GET['seite'] == 'impressum' )
{
	// nutze das Impressum der floorball-Seite 
  header ("HTTP/1.1 301 Moved Permanently");
  header ("Location: http://www.floorball.de/Impressum.html");
  exit(); 
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

  // Pruefen ob eine "action" gesetzt ist
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
    case 'do_logout':
      Login::do_logout();
      break;
    case 'do_login':
      if (!Login::do_login()) {
        $_SESSION['seite'] = "showLogin";
      }
      break;
    case 'send_pw':
      Login::sendNewPW();
      $_SESSION['seite'] = "showPWsended";
      break;
    case 'set_pw':
      Login::setNewPW($_POST['passwort']);
      break;
    case 'set_datenschutz':
      Login::setDatenschutz();
      break;
    } // switch
  }

  // Erzeugen des HTML Objektes
  $HTML = new HTMLGen();

  include('includes/menue.php');

  $HTML->addHeadEntry(new HeadEntry(HeadEntry::LINK_CSS, "styles/design.css"));
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::LINK_CSS, "styles/form.css"));
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::LINK_CSS, "styles/classes.css"));
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::LINK_CSS, "styles/statistik2.css"));

  if (defined('TESTSYSTEM') && TESTSYSTEM) {
    $tmpCSS[] = "body {";
    $tmpCSS[] = "  background-color: #EE5555;";
    $tmpCSS[] = "}";
    $HTML->addHeadEntry(new HeadEntry(HeadEntry::CODE_CSS, $tmpCSS));
    unset($tmpCSS);
  }

  // gefällt mir noch nicht
  $tmpJS[] = "function set_periode(ab, periode) {";
  $tmpJS[] = "  for(i=ab+1; i<100; i++) {";
  $tmpJS[] = "    document.getElementsByName(\"per_\"+i)[0].value = periode;";
  $tmpJS[] = "  }";
  $tmpJS[] = "}";
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::CODE_JS, $tmpJS));

  $tmpJS   = array();
  $tmpJS[] = "function pop_menu(str_menuname, bol_show) {";
  $tmpJS[] = "  if(bol_show)";
  $tmpJS[] = "    document.getElementById(str_menuname).style.visibility = 'visible';";
  $tmpJS[] = "  else";
  $tmpJS[] = "    document.getElementById(str_menuname).style.visibility = 'hidden';";
  $tmpJS[] = "}";
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::CODE_JS, $tmpJS));

  $tmpJS   = array();
  $tmpJS[] = "function show_save() {";
  $tmpJS[] = "  document.getElementById(\"save_widget\").style.visibility = 'visible';";
  $tmpJS[] = "}";
  $HTML->addHeadEntry(new HeadEntry(HeadEntry::CODE_JS, $tmpJS));

  $HTML->printHTML();


  // Prüfen ob der Benutzer eingeloggt ist
  if (Login::isloggedin()) {
    $my_form = new Formular;
    $my_message = new Message;

    require_once('loggedin.php');

  } else { // wenn nicht eingelogged
    if (isset($_SESSION['seite'])) {
      $str_seite = $_SESSION['seite'];
      // existiert die gewünschte Seite
      switch($str_seite) {
        case 'showLogin':
          Login::showLogin();
          break;
        case 'showChangePW':
          Login::showChangePW();
          break;
        case 'showDatenschutz':
          Login::showDatenschutz();
          break;
        case 'showDatenschutz2':
          echo "<h2 style=\"color:red;\">Sie müssen die Bestimmungen annehmen bevor Sie sich einloggen können!</h2>";
          Login::showDatenschutz();
          break;
        case 'showPWsended':
          Login::showPWsended();
          break;
        default:
          echo "<br />Die gewünschte Seite $str_seite existiert nicht oder konnte nicht
                geladen werden.<br />";
      } // switch
    } else if (isset($_GET['seite'])) {
      $str_seite = $_GET['seite'];
      // existiert die gewünschte Seite
      switch($str_seite){
        case 'impressum':
          include("impressum.php");
          break;
        case 'table':
          include("groups/public/sites/tables2.php");
          break;
        case 'spielplan':
          include("groups/public/sites/spielplan.php");
          break;
        case 'scorer':
          include("groups/public/sites/scorer2.php");
          break;
        case 'game':
          include("groups/public/sites/game.php");
          break;
        default:
          echo "<br />Die gewünschte Seite $str_seite existiert nicht oder konnte nicht
                geladen werden.<br />";
      } // switch
    } else {
      echo "<br />Willkommen bei dem SaisonManager-Floorball.";
      echo "<br /><br /><br />";
      echo "Die Daten der &auml;lteren Saisons sind unter folgendem Link zu finden:<br />";
      echo '<a href="http://saison2010-11.floorball-verband.de">Saison 2010/11</a><br />
      <a href="http://saison2011-12.floorball-verband.de">Saison 2011/12</a><br />
      <a href="http://saison2012-13.floorball-verband.de">Saison 2012/13</a><br />';
      
    }
  }


  $HTML->printHTML2();


  // aufräumen
  if (isset($_SESSION['seite'])) {
    unset($_SESSION['seite']);
  }

?>