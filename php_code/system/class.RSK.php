<?php
/**
 * RSK
 *
 * Diese Klasse enthält Funktionen für den RSK-Bereich.
 *
 * @package    SYSTEM
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.5
 * @access public
 *
 */
class RSK {

  public static function showTableSchiri() {
    // arr_schiri_list:
    // 0 -> id_liga
    // 1 -> name
    // 2 -> kurzname
    // 3 -> schri
    // 4 -> Anzahl
    $arr_schiri_list = DBM_RSK::get_schiriSumme();
    // arrHeadEntry:
    // 0 -> id_liga
    // 1 -> name
    // 2 -> kurzname
    // 3 -> id_kategorie
    $arrHeadEntry    = DBM_Ligen::get_list_Liga2();

    if (!$arr_schiri_list) {
      return array("Keine Schiris gefunden");
    }

    if (!$arr_schiri_list) {
      return array("Keine Liga gefunden");
    }

    foreach ($arr_schiri_list as $tmp) {
      $arrTableEntry[$tmp[3]][$tmp[0]] = $tmp[4];
    }




    // HTML erzeugen

    // Kopf erzeugen
    $arrHTMLHead[] = "  <tr style=\"border: 1px solid black;\">";
    $arrHTMLHead[] = "    <th style=\"text-align: left; padding: 3px; border: 1px solid black;\">Schiedsrichter</th>";
    foreach ($arrHeadEntry as $headEntry) {
      $arrHTMLHead[] = "    <th style=\"border: 1px solid black; padding: 3px;\">". $headEntry[2] ."</th>";
    }
    $arrHTMLHead[] = "  </tr>";


    $arrHTML[] = "";
    $arrHTML[] = "<table style=\"border: 1px solid black; border-collapse:collapse;\">";

    $arrHTML = array_merge($arrHTML, $arrHTMLHead);

    $zeile = 0;
    foreach ($arrTableEntry as $schiriname => $arrAnzahl) {
      if ($zeile%2 == 0) {
        $arrHTML[] = "  <tr style=\"border: 1px solid black; background-color: #F9F9F9;\">";
      } else {
        $arrHTML[] = "  <tr style=\"border: 1px solid black;\">";
      }
      $arrHTML[] = "    <td style=\"text-align: left; border: 1px solid black; padding: 3px;\">$schiriname</td>";
      foreach ($arrHeadEntry as $headEntry) {
        if (isset($arrAnzahl[$headEntry[0]])) {
          $arrHTML[] = "    <td style=\"border: 1px solid black;\">".$arrAnzahl[$headEntry[0]]."</td>";
        } else {
          $arrHTML[] = "    <td style=\"border: 1px solid black;\">-</td>";
        }
      }
      $arrHTML[] = "  </tr>";
      ++$zeile;
      if ($zeile >= 21) {
        $zeile = 0;
        $arrHTML = array_merge($arrHTML, $arrHTMLHead);
      }
    }
    $arrHTML[] = "</table";
    $arrHTML[] = "";

    return $arrHTML;
  }

} //class Login
?>