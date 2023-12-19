<?php
/**
 * HTMLBody
 *
 * Diese Klasse bietet Funktionen um HTML zu erzeugen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 08.03.2010
 * @version 0.1
 */
class HTMLBody {


  private $Kopfzeile = NULL;

  private $MenuBar   = NULL;

  private $Inhalt    = NULL;

  private $Fusszeile = NULL;


  /**
   * HTMLBody::HTMLBody()
   *
   */
  public function HTMLBody() {
    $this->Kopfzeile = new Kopfzeile();
    $this->MenuBar   = new MenuBar();
//    $this->Inhalt    = new Inhalt();
    $this->Fusszeile = new Fusszeile();
  }


  public function getHTML() {
    $tmpKopfzeile = $this->Kopfzeile->getHTML();
    $tmpMenuBar   = $this->MenuBar->getHTML();
//    $tmpInhalt    = $this->Inhalt->getHTML();
//    $tmpFusszeile = $this->Fusszeile->getHTML();

    // Einrückung erzeugen
    HTMLGen::addSpaces($tmpKopfzeile, 1);
    HTMLGen::addSpaces($tmpMenuBar, 1);
//    HTMLGen::addSpaces($tmpInhalt, 1);
//    HTMLGen::addSpaces($tmpFusszeile, 1);

    // endgültiges bauen von dem HTML Array
    $arrHTML[] = "<body>";
    $arrHTML[] = "";
    $arrHTML[] = "  <!-- Positionierung auf der Seite -->";
    $arrHTML[] = "  <div id=\"container\">";
    $arrHTML[] = "";

    $arrHTML = array_merge($arrHTML, $tmpKopfzeile);
    $arrHTML = array_merge($arrHTML, $tmpMenuBar);

    $arrHTML[] = "  <!-- Anfang Mittelteil -->";
    $arrHTML[] = "    <div id=\"inhalt\">";
    $arrHTML[] = "";

// @todo -cHTMLBody Implement HTMLBody.getHTML
/*
    $arrHTML = array_merge($arrHTML, $tmpInhalt);
    $arrHTML = array_merge($arrHTML, $tmpFusszeile);

    $arrHTML[] = "  </div>";
    $arrHTML[] = "  <!-- Ende der Positionierung -->";
    $arrHTML[] = "";
    $arrHTML[] = "  <div id=\"save_widget\">";
    $arrHTML[] = "    <b>Die Seite wird geladen!</b> <br />";
    $arrHTML[] = "    <br />";
    $arrHTML[] = "    Bitte warten, der Vorgang kann mehrere Minuten in Anspruch nehmen.";
    $arrHTML[] = "  </div>  <!-- save_widget -->";
    $arrHTML[] = "";
    $arrHTML[] = "</body>";
    $arrHTML[] = "";
    $arrHTML[] = "</html>";
   */

    return $arrHTML;
  }


  public function getHTML2() {
    $arrHTML[] = "";
    $arrHTML[] = "    </div>";
    $arrHTML[] = "  <!-- Ende Mittelteil -->";
    $arrHTML[] = "";

    $tmpFusszeile = $this->Fusszeile->getHTML();

    // Einrückung erzeugen
    HTMLGen::addSpaces($tmpFusszeile, 1);

    // endgültiges bauen von dem HTML Array
    $arrHTML = array_merge($arrHTML, $tmpFusszeile);

    $arrHTML[] = "  </div>";
    $arrHTML[] = "  <!-- Ende der Positionierung -->";
    $arrHTML[] = "";
    $arrHTML[] = "  <div id=\"save_widget\">";
    $arrHTML[] = "    <b>Die Seite wird geladen!</b> <br />";
    $arrHTML[] = "    <br />";
    $arrHTML[] = "    Bitte warten, der Vorgang kann mehrere Minuten in Anspruch nehmen.";
    $arrHTML[] = "  </div> <!-- save_widget -->";
    $arrHTML[] = "";
    $arrHTML[] = "</body>";
    $arrHTML[] = "";
    $arrHTML[] = "</html>";

    return $arrHTML;
  }


  public function setMenuBar(MenuBar $newMenuBar) {
    $this->MenuBar = $newMenuBar;
  }


} // class

?>