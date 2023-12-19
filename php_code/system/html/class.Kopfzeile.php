<?php
/**
 * Kopfzeile
 *
 * Die Klasse enthält Methoden um eine Kopfzeile in php zu erstellen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class Kopfzeile {


  public function Kopfzeile() {

  }


  public function getHTML() {
    $tmpHeader[] = "<a id=\"logo_header\" href=\"index.php\">";
    $tmpHeader[] = "  <img src=\"images/". VERBAND ."/logo_70.png\" alt=\"Logo\" />";
    $tmpHeader[] = "</a>";
    $tmpHeader[] = "<img id=\"sm_logo\" src=\"images/sm_logo3.png\" alt=\"SaisonManager Logo\" />";

    $tmpLogin  = Login::show_status();

    HTMLGen::addSpaces($tmpHeader, 2);
    HTMLGen::addSpaces($tmpLogin, 2);

    $arrHTML[] = "<!-- Anfang Kopfzeile -->";
    $arrHTML[] = "  <div id=\"kopfzeile\">";

    $arrHTML = array_merge($arrHTML, $tmpHeader);
    $arrHTML = array_merge($arrHTML, $tmpLogin);

    $arrHTML[] = "  </div>";
    $arrHTML[] = "<!-- Ende Kopfzeile -->";
    $arrHTML[] = "";

    return $arrHTML;
  }


} // class

?>