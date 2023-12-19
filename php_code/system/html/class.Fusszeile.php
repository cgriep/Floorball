<?php
/**
 * Fusszeile
 *
 * Die Klasse enthält Methoden um eine Fusszeile in php zu erstellen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class Fusszeile {


  public function Fusszeile() {

  }


  public function getHTML() {
    global $time_start;

    if (DEBUG) {
      $tmpFooter[] = "<span style=\"position:absolute; left:30px; z-index:20;\">";
      $tmpFooter[] = "  Speicher: ";
      $tmpFooter[] = "  ". memory_get_usage();
      $tmpFooter[] = "</span>";
    }
    $tmpFooter[] = "<img src=\"images/fusszeile_bg_auslauf.gif\" style=\"position: absolute; top: 0px; left: 0px;\" alt=\"\" />";
    $tmpFooter[] = "Copyright by Malte R&ouml;mmermann &amp; Michael Rohn";
    $tmpFooter[] = "<span style=\"position:absolute; right:10px;\">";
    $tmpFooter[] = "  <a href=\"index.php?seite=impressum\">Impressum</a>";
    if (DEBUG) {
      $tmpFooter[] = "  Seite generiert in";
      $tmpFooter[] = "  ". substr(microtime(true)-$time_start, 0, 5) ." sek";
    }
    $tmpFooter[] = "</span>";

    HTMLGen::addSpaces($tmpFooter, 2);

    $arrHTML[] = "<!-- Anfang Fusszeile -->";
    $arrHTML[] = "  <div id=\"fusszeile\">";

    $arrHTML = array_merge($arrHTML, $tmpFooter);

    $arrHTML[] = "  </div>";
    $arrHTML[] = "<!-- Ende Fusszeile -->";
    $arrHTML[] = "";

    return $arrHTML;
  }


} // class

?>