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

// prefix für die globalen Tabellen
define('PREFIX_GLOBAL', 'global_');


define('TABLE_SPIELER',          PREFIX_GLOBAL . 'spieler');
define('TABLE_VERBAND',          PREFIX_GLOBAL . 'verband');
define('TABLE_VEREIN',           PREFIX_GLOBAL . 'verein');
define('TABLE_SAISON',           PREFIX_GLOBAL . 'saison');
define('TABLE_SYSTEM',           PREFIX_GLOBAL . 'system');
define('TABLE_KLASSE',           PREFIX_GLOBAL . 'klasse');
define('TABLE_NATION',           PREFIX_GLOBAL . 'nation');
define('TABLE_STRAFE',           PREFIX_GLOBAL . 'strafe');
define('TABLE_SPIELORT',         PREFIX_GLOBAL . 'spielort');
define('TABLE_STRAFCODE',        PREFIX_GLOBAL . 'strafcode');
define('TABLE_KATEGORIE',        PREFIX_GLOBAL . 'kategorie');
define('TABLE_SPIELSYSTEM',      PREFIX_GLOBAL . 'spielsystem');
define('TABLE_LIZENZSTATUS',     PREFIX_GLOBAL . 'lizenzstatus');
define('TABLE_SCHIEDSRICHTER',   PREFIX_GLOBAL . 'schiedsrichter');
define('TABLE_VEREIN_SPIELER',   PREFIX_GLOBAL . 'verein_spieler');
define('TABLE_SCHIRI_LIZENZTYP', PREFIX_GLOBAL . 'schiri_lizenztyp');
define('TABLE_VEREIN_VERBAND',   PREFIX_GLOBAL . 'verein_verband');
define('TABLE_TRANSFER_PROTOKOLL',   PREFIX_GLOBAL . 'transfer_protokoll');


?>