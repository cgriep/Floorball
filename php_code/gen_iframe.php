<?php
/****************************************************************************
 *                                                                           *
 * Dies ist eine Seite, um Tabellen und Co. an die Verbände zu geben         *
 *                                                                           *
 *                                                                           *
 ****************************************************************************/

  function __autoload($class_name) {
    require_once 'class.'. $class_name .'.php';
  }

  // Start der Session
  session_start();

  // Hier stehen die Includes/Requires, die für alle User gültig sind
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>SManager-Unihockey</title>
  <link rel="StyleSheet" href="styles/design.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="styles/form.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="styles/classes.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="styles/statistic_iframe.css" type="text/css" media="all" />

<?php // Skript für die Eingabe des Spielberichtes ?>

</head>

<body>

  <!-- Positionierung auf der Seite -->

  <!-- Anfang Mittelteil -->
    <div id="iframe">

<?php
  $iframe = true;
  $str_seite = $_GET['seite'];
  // existiert die gewünschte Seite
  switch($str_seite){
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

?>

    </div>
  <!-- Ende Mittelteil -->


  <!-- Ende der Positionierung -->
</body>
</html>