<?php
/**
 * HTMLGen
 *
 * Diese Klasse bietet Funktionen um HTML zu erzeugen.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class HTMLGen {

  private $DocType = NULL;

  private $Head = NULL;

  private $Body = NULL;


  /**
   * HTMLGen::HTMLGen()
   *
   * Konstruktor für das HTML-Objekt
   *
   */
  public function HTMLGen() {
    $this->DocType = new DocType(DocType::XHTML1_1);
    $this->Head = new HTMLHead();
    $this->Body = new HTMLBody();
  }


  /**
   * HTMLGen::printHTML()
   *
   * @return
   */
  public function printHTML() {
    self::printToEcho($this->DocType->getHTML());
    self::printToEcho($this->Head->getHTML());
    self::printToEcho($this->Body->getHTML());
  }


  /**
   * HTMLGen::printHTML2()
   *
   * @return
   */
  public function printHTML2() {
    self::printToEcho($this->Body->getHTML2());
  }


  /**
   * HTMLGen::setMenuBar()
   *
   * @param MenuBar $newMenuBar Gibt die neue Menuleiste an
   * @return
   */
  public function setMenuBar(MenuBar $newMenuBar) {
    $this->Body->setMenuBar($newMenuBar);
  }


  /**
   * HTMLGen::printHTML()
   *
   * Gibt das übergebene Array mit "echo" aus.
   *
   * @param array $arrHTML
   * @return bool
   */
  public static function printToEcho($arrHTML) {
    if ($arrHTML != NULL) {
      foreach ($arrHTML as $zeile) {
        echo ($zeile . "\n");
      }
      echo ("\n");
      return true;
    } else {
      return false;
    }
  }


  /**
   * HTMLGen::addSpaces()
   *
   * Fügt jedem Element des Array 2x$intSteps am Anfang an.
   *
   * @param array $arrHTML
   * @param int $intSteps
   */
  public static function addSpaces(&$arrHTML, $intSteps=0) {
    $spaces = "";
    for ($i=0; $i < $intSteps; $i++) {
      $spaces .= "  ";
    }
    for ($i=0; $i < count($arrHTML); $i++) {
      $arrHTML[$i] = $spaces . $arrHTML[$i];
    }
  }


  /**
   * HTMLGen::addHeadEntry()
   *
   * @param HeadEntry $newEntry
   */
  public function addHeadEntry(HeadEntry $newEntry) {
    $this->Head->addEntry($newEntry);
  }


} // class

?>