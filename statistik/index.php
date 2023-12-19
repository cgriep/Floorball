<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

<head>
  <title>Statistik-Floorball</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="pragma" content="no-cache" />
  <meta http-equiv="cache-control" content="no-cache" />
  <link rel="StyleSheet" href="styles/design.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="styles/statistik2.css" type="text/css" media="all" />
  <script language="JavaScript" type="text/javascript">
  <!--

    function pop_menu(str_menuname, bol_show) {
      if(bol_show)
        document.getElementById(str_menuname).style.visibility = 'visible';
      else
        document.getElementById(str_menuname).style.visibility = 'hidden';
    }

  -->
  </script>
</head>

<?php
  // Einbinden der nötigen Dateien

  // Wird in den eingebunden Seiten abgefragt
  define('IN_SMU', true);

  // Laden der Konfiguration
  require_once('config/.htconfig.php');

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


?>


<body>

  <!-- Positionierung auf der Seite -->
  <div id="container">

  <!-- Anfang Kopfzeile -->
    <div id="kopfzeile">

    <div style="float: left;">
      <a id="logo_header" href="index.php">
        <img src="images/floorball_deutschland.gif" alt="Floorball Deutschland" />
      </a>
    </div>
    <div style="float: right; text-align: right;">
      <img src="images/maxx_print_floorball_bundesliga.gif" alt="MaXx Print FBL Floorball Bundesliga" />
    </div>
    <div style="clear: both;"></div>

    </div>
  <!-- Ende Kopfzeile -->

<?php
  // Menü erzeugen

  $menuBar = new MenuBar();

  // Menü für die Spielpläne
  $menuSpielplan = new Menu("Spielpläne");
  // get id's of leagues
  $arr_leagues = DBM_Ligen::get_list_liga();
  // create menu for leagues
  for ($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuSpielplan->addSubEntry($Liga->get_str_name(), "index.php?seite=spielplan&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuSpielplan);

  // Menü für die Tabellen
  $menuTabellen = new Menu("Tabellen");

  // get id's of leagues
  $arr_tmp = DBM_Ligen::get_list_liga();
  $arr_leagues[0] = array();
  $arr_leagues[1] = array();
  $arr_leagues[2] = array();
  for($i=0; $i<count($arr_tmp[0]); $i++) {
    if($arr_tmp[3][$i] != -3 && $arr_tmp[3][$i] != -4) {
      $arr_leagues[0][] = $arr_tmp[0][$i];
      $arr_leagues[1][] = $arr_tmp[1][$i];
      $arr_leagues[2][] = $arr_tmp[2][$i];
    }
  }

  // create menu for leagues
  for($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuTabellen->addSubEntry($Liga->get_str_name(), "index.php?seite=table&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuTabellen);

  // Menü für die Scorer
  $menuScorer = new Menu("Scorer");
  $arr_leagues = DBM_Ligen::get_list_liga();
  // create menu for leagues
  for($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuScorer->addSubEntry($Liga->get_str_name(), "index.php?seite=scorer&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuScorer);

  $menuHTML = $menuBar->getHTML();

  HTMLGen::printToEcho($menuHTML);

?>

  <!-- Anfang Mittelteil -->
    <div id="inhalt">

<?php
    if (isset($_GET['seite'])) {
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
      include("groups/public/sites/spielplan.php");
    }
?>

    </div>
  <!-- Ende Mittelteil -->

  <!-- Anfang Sponsoren -->
  	<div id="sponsoren">
      <a href="http://www.maxxprint.de" target="_blank">
        <img src="images/maxx_print_druckdienstleister.gif" alt="MaXxPrint ihr Druckdienstleister" />
      </a>
    </div>

    <div id="sponsoren">
      <a href="http://www.aohostels.com" target="_blank">
        <img src="images/ao_hostel280.gif" alt="AO Hostel" />
      </a>
    </div>

    <div style="clear: both;"></div>
  <!-- Ende Sponsoren -->


  <!-- Anfang Fusszeile -->
    <div id="fusszeile">
      <div style="float: left;">
        Copyright &copy; Malte R&ouml;mmermann &amp; Michael Rohn
      </div>
      <div style="float: right; text-align: right;">
        <a href="index.php?seite=impressum">Impressum</a>
      </div>
      <div style="clear: both;"></div>
    </div>
  <!-- Ende Fusszeile -->

  </div>
  <!-- Ende der Positionierung -->

</body>

</html>

