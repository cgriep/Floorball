<?php
/****************************************************************************
*                                                                           *
* Dies ist die Einstiegsseite fuer das Saisonverwaltungssystem              *
*                                                                           *
*                                                                           *
****************************************************************************/

  // Zur Zeitmessung wird die Startzeit gespeichert
  $time_start = microtime(true);

  // Wird in den eingebunden Seiten abgefragt
  define('IN_SMU', true);

  // Laden der Konfiguration
  require_once('config/.htconfig.php');

  // Aufruf der eigentlichen Startseite
  require_once('entry.php');

?>