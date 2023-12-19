<?php
/**
 * MenuBar
 *
 * Die Klasse enthält Methoden um eine Menüzeile in php zu erstellen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class MenuBar {

  private $arrMenuLeft  = NULL;
  private $arrMenuRight = NULL;

  const LEFT  = 1; // linke Menü-Seite
  const RIGHT = 2; // rechte Menü-Seite


  /**
   * MenuBar::MenuBar()
   *
   * Konstruktor der MenuBar. (macht nichts)
   *
   */
  public function MenuBar() {

  }


  /**
   * MenuBar::addMenu()
   *
   * Fügt ein neues Menü zur Menübar hinzu. Es kann links (standard) oder rechts eingefügt werden.
   *
   * @param Menu $Menu
   * @param int $align
   */
  public function addMenu(Menu $Menu, $align=self::LEFT) {
    if ($align == MenuBar::LEFT) {
      $this->arrMenuLeft[] = $Menu;
    } else {
      $this->arrMenuRight[] = $Menu;
    }
  }


  /**
   * MenuBar::getHTML()
   *
   * Gibt den HTML-Code für das ganze Menü als $arr_html zurück.
   *
   */
  public function getHTML() {
    $html[] = "<!-- Anfang Menue -->";
    $html[] = "  <div id=\"menue_rahmen\">";

    // Linkes Menü ausgeben
    if ($this->arrMenuLeft != NULL) {
      $html[] = "    <ul id=\"menue_links\">";
      foreach($this->arrMenuLeft as $Menu) {
        $menu_html = $Menu->getHTML();
        HTMLGen::addSpaces($menu_html, 3);
        $html = array_merge($html, $menu_html);
      }
      $html[] = "    </ul>";
    } // MenuLeft

    // Rechtes Menü ausgeben
    if ($this->arrMenuRight != NULL) {
      $html[] = "    <ul id=\"menue_rechts\">";
      foreach($this->arrMenuRight as $Menu) {
        $menu_html = $Menu->getHTML();
        HTMLGen::addSpaces($menu_html, 3);
        $html = array_merge($html, $menu_html);
      }
      $html[] = "    </ul>";
    } // MenuRight

    // Grafik-rechts ausgeben
    $html[] = "    <img src=\"images/menue_bg_auslauf.gif\" style=\"position:absolute; top:0px; right:0px;\" alt=\"\" />";
    $html[] = "  </div>";
    $html[] = "<!-- Ende Menue -->";
    $html[] = "";

    return $html;
  }


} // class

?>