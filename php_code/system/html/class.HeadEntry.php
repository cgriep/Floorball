<?php
/**
 * HeadEntry
 *
 * Diese Klasse stellt Einträge für den HTML-Kopf bereit.
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class HeadEntry {

  const TITLE      = 1;
  const LINK_JS    = 2;
  const LINK_CSS   = 3;
  const CODE_JS    = 4;
  const CODE_CSS   = 5;
  const META       = 6;
  const HTTP_EQUIV = 7;

  private $intType  = NULL;
  private $strValue = NULL;
  private $strName  = NULL;


  public function HeadEntry($intType, $strValue, $strName = NULL) {
    $this->intType  = $intType;
    $this->strValue = $strValue;
    $this->strName  = $strName;
  }


  public function getType() {
    return $this->intType;
  }


  public function getHTML() {
    switch($this->intType) {
      case self::TITLE:
        $arrHTML = $this->getTitle();
        break;
      case self::LINK_JS:
        $arrHTML = $this->getLinkJS();
        break;
      case self::LINK_CSS:
        $arrHTML = $this->getLinkCSS();
        break;
      case self::META:
        $arrHTML = $this->getMeta();
        break;
      case self::HTTP_EQUIV:
        $arrHTML = $this->getHTTP_EQUIV();
        break;
      default:
        $arrHTML   = array();
        $arrHTML[] = "";
    } // switch
    return $arrHTML;
  }


  public function getValue() {
    return $this->strValue;
  }


  public function getName() {
    return $this->strName;
  }


  private function getTitle() {
    $arrHTML = array();
    $arrHTML[] = "<title>$this->strValue</title>";
    return $arrHTML;
  }


  private function getLinkJS() {
    $arrHTML = array();
    $arrHTML[] = "<script src=\"$this->strValue\" type=\"text/javascript\"></script>";
    return $arrHTML;
  }


  private function getLinkCSS() {
    $arrHTML = array();
    $arrHTML[] = "<link rel=\"StyleSheet\" href=\"$this->strValue\" type=\"text/css\" media=\"all\" />";
    return $arrHTML;
  }


  private function getMeta() {
    $arrHTML = array();
    $arrHTML[] = "<meta name=\"$this->strName\" content=\"$this->strValue\" />";
    return $arrHTML;
  }


  private function getHTTP_EQUIV() {
    $arrHTML = array();
    $arrHTML[] = "<meta http-equiv=\"$this->strName\" content=\"$this->strValue\" />";
    return $arrHTML;
  }


} // class

?>