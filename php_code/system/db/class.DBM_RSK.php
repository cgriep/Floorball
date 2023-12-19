<?php

require_once('class.DBM_root.php');

/**
 * DBM_RSK
 *
 * Diese Klasse enthält Funktionen zum Lesen und Schreiben
 * aus der Datenbank, die für die RSK benötigt werden
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR^2
 * @version 0.5
 * @access public
 */
class DBM_RSK extends DBM_root {


  public static function get_schiriSumme() {
    /* Verwendung eines Views:
    CREATE VIEW `flvsh_SchiriSumme` AS select `SL`.`id_liga` AS `id_liga`,`Liga`.`name` AS `name`,`Liga`.`kurzname` AS `kurzname`,`SL`.`schiri` AS `schiri`,count(`SL`.`schiri`) AS `Anzahl` from (`flvsh_SchiriListe` `SL` join `flvsh_2010_2011_liga` `Liga`) where (`SL`.`id_liga` = `Liga`.`id_liga`) group by `SL`.`id_liga`,`SL`.`schiri`
  	CREATE VIEW `flvsh_SchiriListe` AS select `liga`.`id_liga` AS `id_liga`,`spielbericht`.`schiedsrichter1` AS `schiri` from (((`flvsh_2010_2011_begegnung` `begegnung` join `flvsh_2010_2011_liga` `liga`) join `flvsh_2010_2011_spieltag` `spieltag`) join `flvsh_2010_2011_spielbericht` `spielbericht`) where ((`liga`.`id_liga` = `spieltag`.`id_liga`) and (`spieltag`.`id_spieltag` = `begegnung`.`id_spieltag`) and (`begegnung`.`id_begegnung` = `spielbericht`.`id_begegnung`)) union all select `liga`.`id_liga` AS `id_liga`,`spielbericht`.`schiedsrichter2` AS `schiri` from (((`flvsh_2010_2011_begegnung` `begegnung` join `flvsh_2010_2011_liga` `liga`) join `flvsh_2011_2012_spieltag` `spieltag`) join `flvsh_2010_2011_spielbericht` `spielbericht`) where ((`liga`.`id_liga` = `spieltag`.`id_liga`) and (`spieltag`.`id_spieltag` = `begegnung`.`id_spieltag`) and (`begegnung`.`id_begegnung` = `spielbericht`.`id_begegnung`))
  	$sql = "SELECT id_liga, name, kurzname, schiri, Anzahl
              FROM ". VERBAND ."_SchiriSumme
              ORDER BY schiri ASC";
    */
  	$sql = 'SELECT id_liga,name,kurzname,schiri,count(schiri) AS Anzahl 
FROM 
(
SELECT liga.id_liga AS id_liga,liga.name as name, liga.Kurzname as kurzname, spielbericht.schiedsrichter1 AS schiri 
FROM  
'.VERBAND.TABLE_BEGEGNUNG.' begegnung
join '.VERBAND.TABLE_SPIELTAG.' spieltag ON spieltag.id_spieltag=begegnung.id_spieltag
join '.VERBAND.TABLE_SPIELBERICHT.' spielbericht ON begegnung.id_begegnung=spielbericht.id_begegnung
join '.VERBAND.TABLE_LIGA.' liga ON spieltag.id_liga=liga.id_liga
UNION ALL
SELECT liga.id_liga AS id_liga,liga.name as name, liga.Kurzname as kurzname, spielbericht.schiedsrichter2 AS schiri 
FROM 
'.VERBAND.TABLE_BEGEGNUNG.' begegnung
join '.VERBAND.TABLE_SPIELTAG.' spieltag ON spieltag.id_spieltag=begegnung.id_spieltag
join '.VERBAND.TABLE_SPIELBERICHT.' spielbericht ON begegnung.id_begegnung=spielbericht.id_begegnung
join '.VERBAND.TABLE_LIGA.' liga ON spieltag.id_liga=liga.id_liga
) SL
group by id_liga,name,kurzname,schiri';
    
    $db = self::db_connect(DB_NAME, "r");
    if ($result = $db->query($sql)) {
      $arr_list_schiri = array();
      $i = 0;
      while ($arr_row = mysql_fetch_row($result)) {
        $arr_list_schiri[$i][0] = $arr_row[0];
        $arr_list_schiri[$i][1] = $arr_row[1];
        $arr_list_schiri[$i][2] = $arr_row[2];
        $arr_list_schiri[$i][3] = $arr_row[3];
        $arr_list_schiri[$i][4] = $arr_row[4];
        $i++;
      }
      return $arr_list_schiri;
    } else {
      // Fehlermeldung generieren
      return FALSE;
    }
  }


}

?>