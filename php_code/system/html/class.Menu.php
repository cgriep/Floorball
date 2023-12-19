<?php
/**
 * Menu
 *
 * Die Klasse enthält Methoden um eine Menü in php zu erstellen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class Menu {

  const ENTRY    = 1;
  const SUB_MENU = 2;

  private $strText = NULL;
  private $strLink = NULL;

  private $menuType = NULL;

  private $arrSubEntry = NULL;


  /**
   * Menu::Menu()
   *
   * Erstellt ein neus Menu-Objekt. Wird kein Link übergeben wird das
   * Menü als SubMenü ausgelegt.
   *
   * @param str $strText
   * @param str $strLink
   */
  public function Menu($strText, $strLink = NULL) {
    $this->strText = $strText;
    if ($strLink === NULL) {
      $this->menuType = self::SUB_MENU;
      $this->arrSubEntry = array();
    } else {
      $this->menuType = self::ENTRY;
      $this->strLink = $strLink;
    }
  }


  /**
   * Menu::addSubEntry()
   *
   * Fügt sofern es sich um ein SubMenü handelt einen neuen Eintrag
   * in das Untermenü ein
   *
   * @param str $strText
   * @param str $strLink
   * @return bool
   */
  public function addSubEntry($strText, $strLink) {
    if ($this->menuType == self::SUB_MENU) {
      $this->arrSubEntry[$strText] = $strLink;
      return true;
    } else {
      return false;
    }
  }

  /**
   * Menu::getHTML()
   *
   * Liefert als return-Wert den HTML-Code zu dem Menü.
   *
   * @return array $html
   */
  public function getHTML() {
    if ($this->menuType == self::ENTRY) {
      // Einzelner Menüeintrag
      $html[] = "<li>";
      $html[] = "  <a href=\"$this->strLink\">";
      $html[] = "    $this->strText";
      $html[] = "  </a>";
      $html[] = "</li>";
    } else {

      // Block für Nutzer ohne JavaScript
      if (isset($_GET['menue']) && $_GET['menue'] == "subMenu_".$this->strText) {
        $href    = $_SERVER['PHP_SELF'];
        $visible = "style=\"visibility: visible;\"";
      } else {
        $href    = $_SERVER['PHP_SELF']."?menue=subMenu_$this->strText";
        $visible = "";
      }

      // Ein Komplettes Untermenü
      $html[] = "<li onmouseover=\"pop_menu('subMenu_$this->strText', 1);\" onmouseout=\"pop_menu('subMenu_$this->strText', 0);\">";
      $html[] = "  <a onclick=\"return false;\" href=\"$href\">";
      $html[] = "    $this->strText";
      $html[] = "  </a>";
      $html[] = "  <ul id=\"subMenu_$this->strText\" $visible>";
      foreach ($this->arrSubEntry as $text => $link) {
        $html[] = "    <li>";
        $html[] = "      <a href=\"$link\">";
        $html[] = "        $text";
        $html[] = "      </a>";
        $html[] = "    </li>";
      }
      $html[] = "  </ul>";
      $html[] = "</li>";
    }
    return $html;
  }


} // class

?>