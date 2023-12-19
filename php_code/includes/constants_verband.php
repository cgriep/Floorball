<?php
/**
 * Diese Datei enthält Konstanten für die DB-Zugriffe des Verbandes
 *
 * @package    SYSTEM
 * @subbackage CONFIG
 */

if ( !defined('IN_SMU') )
{
  die("Dies ist nicht die Einstiegseite");
}


// Prefix für alle Verbandstabellen
//define('PREFIX_VERBAND', VERBAND.'_');
define('PREFIX_TMP', 'tmp_');


// Tabellennamen für den Verband
define('TABLE_PERSON', '_' . 'person');


define('TABLE_LIGA',          '_' . PREFIX_SAISON . 'liga');
define('TABLE_LIZENZ',        '_' . PREFIX_SAISON . 'lizenz');
define('TABLE_MANNSCHAFT',    '_' . PREFIX_SAISON . 'mannschaft');
define('TABLE_SPIELTAG',      '_' . PREFIX_SAISON . 'spieltag');
define('TABLE_BEGEGNUNG',     '_' . PREFIX_SAISON . 'begegnung');
define('TABLE_EREIGNIS',      '_' . PREFIX_SAISON . 'ereignis');
define('TABLE_BETREUER',      '_' . PREFIX_SAISON . 'betreuer');
define('TABLE_MITSPIELER',    '_' . PREFIX_SAISON . 'mitspieler');
define('TABLE_SG',            '_' . PREFIX_SAISON . 'spielgemeinschaft');
define('TABLE_SPIELBERICHT',  '_' . PREFIX_SAISON . 'spielbericht');
define('TABLE_LIZENZVERLAUF', '_' . PREFIX_SAISON . 'lizenzverlauf');


// Tabelennamen zum temporären Speichern von Daten
define('TABLE_TMP_EREIGNIS',     '_' . PREFIX_TMP . PREFIX_SAISON . 'ereignis');
define('TABLE_TMP_BETREUER',     '_' . PREFIX_TMP . PREFIX_SAISON . 'betreuer');
define('TABLE_TMP_MITSPIELER',   '_' . PREFIX_TMP . PREFIX_SAISON . 'mitspieler');
define('TABLE_TMP_SPIELBERICHT', '_' . PREFIX_TMP . PREFIX_SAISON . 'spielbericht');


?>