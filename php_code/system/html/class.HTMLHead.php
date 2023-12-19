<?php
/**
 * HTMLHead
 *
 * Erzeugt einen HTML-Head
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class HTMLHead {

  private $Title = NULL;

  private $arrEntries = NULL;

  private $arrCSSCode = array();
  private $arrJSCode  = array();


  /**
   * HTMLHead::HTMLHead()
   *
   * Konstruktor für den HTML-Head
   *
   */
  public function HTMLHead() {
    $this->Title = new HeadEntry(HeadEntry::TITLE, "SManager-Floorball");
  }


  public function addEntry(HeadEntry $newEntry) {
    switch ($newEntry->getType()) {
      case HeadEntry::TITLE :
        $this->Title = $newEntry;
        break;
      case HeadEntry::CODE_CSS :
        $this->addCSS($newEntry->getValue());
        break;
      case HeadEntry::CODE_JS :
        $this->addJS($newEntry->getValue());
        break;
      default:
        $this->arrEntries[] = $newEntry;
    } // switch
  }


  public function getHTML() {
    // erzeugen eines temporären HTML Arrays
    $tmpHTML = $this->Title->getHTML();
    $tmpHTML[] = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
    $tmpHTML[] = "<meta http-equiv=\"pragma\" content=\"no-cache\" />";
    $tmpHTML[] = "<meta http-equiv=\"cache-control\" content=\"no-cache\" />";

    if ($this->arrEntries != NULL) {
      foreach ($this->arrEntries as $Entry ) {
        $arrEntry = $Entry->getHTML();
        $tmpHTML = array_merge($tmpHTML, $arrEntry);
      }
    }

    if (count($this->arrJSCode) > 0) {
      $tmpHTML[] = "<script language=\"JavaScript\" type=\"text/javascript\">";
      $tmpHTML[] = "<!--";
      $tmpHTML[] = "";
      HTMLGen::addSpaces($this->arrJSCode, 1);
      $tmpHTML = array_merge($tmpHTML, $this->arrJSCode);
      $tmpHTML[] = "-->";
      $tmpHTML[] = "</script>";
    }

    if (count($this->arrCSSCode) > 0) {
      $tmpHTML[] = "<style type=\"text/css\">";
      $tmpHTML[] = "<!--";
      $tmpHTML[] = "";
      HTMLGen::addSpaces($this->arrCSSCode, 1);
      $tmpHTML = array_merge($tmpHTML, $this->arrCSSCode);
      $tmpHTML[] = "-->";
      $tmpHTML[] = "</style>";
    }

    // Einrückung erzeugen
    HTMLGen::addSpaces($tmpHTML, 1);

    // endgültiges Bauen von dem HTML Array
    $arrHTML[] = "<head>";

    $arrHTML = array_merge($arrHTML, $tmpHTML);

    $arrHTML[] = "</head>";

    return $arrHTML;
  }


  private function addCSS ($newValue) {
    if (is_array($newValue)) {
      $tmpArray = $newValue;
    } else {
      $tmpArray = explode("\n", $newValue);
    }
    if (end($tmpArray) != "") {
      $tmpArray[] = "";
    }
    $this->arrCSSCode = array_merge($this->arrCSSCode, $tmpArray);
  }


  private function addJS ($newValue) {
    if (is_array($newValue)) {
      $tmpArray = $newValue;
    } else {
      $tmpArray = explode("\n", $newValue);
    }
    if (end($tmpArray) != "") {
      $tmpArray[] = "";
    }
    $this->arrJSCode = array_merge($this->arrJSCode, $tmpArray);
  }

} // class

?>