<?php
/**
 * Diese Datei enthält Konstanten für die USER-DB-Zugriffe
 *
 * @package    SYSTEM
 * @subbackage CONFIG
 */

if ( !defined('IN_SMU') )
{
	die("Dies ist nicht die Einstiegseite");
}

// Tabelennamen für die "Users"-DB
define('TABLE_BENUTZER',        'benutzer');
define('TABLE_BENUTZER_GRUPPE', 'benutzer_gruppe');
define('TABLE_BENUTZER_TEAMS',  'benutzer_teams');
define('TABLE_GRUPPE',          'gruppe');
define('TABLE_AKTIVE_LOGS',     'aktive_logs');
define('TABLE_UNGUELTIGE_LOGS', 'ungueltige_logs');


?>