<?php
/**
 * Diese Datei enthält Konstanten für die globalen DB-Zugriffe
 *
 * @package    SYSTEM
 * @subbackage CONFIG
 */

if ( !defined('IN_SMU') )
{
	die("Dies ist nicht die Einstiegseite");
}


define('TABLE_DOPPELLIZENZ',     PREFIX_GLOBAL . PREFIX_SAISON . 'doppellizenz');
define('TABLE_POKAL_TEAM',       PREFIX_GLOBAL . PREFIX_SAISON . 'pokal_team');

define('TABLE_MEISTER_SPALTUNG', PREFIX_GLOBAL . PREFIX_SAISON . 'meister_spaltung');
define('TABLE_PLAYOFF',          PREFIX_GLOBAL . PREFIX_SAISON . 'playoff');


?>