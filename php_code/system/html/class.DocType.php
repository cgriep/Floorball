<?php
/**
 * DocType
 *
 * Hält verschiedene DocTypes bereit. Zur Zeit nur XHTML1.1
 *
 * @package    AUSGABE
 * @subpackage HTML
 *
 * @access public
 * @author Michael Rohn
 * @since 21.02.2010
 * @version 0.1
 */
class DocType {

  const XHTML1_1 = 1;
  const XHTML1_1_STRICT = 2;

  private $arrHTML = NULL;


  /**
   * DocType::DocType()
   *
   * @param mixed $docType
   */
  public function DocType($docType=self::XHTML1_1) {
    switch($docType) {
      case self::XHTML1_1:
        $this->setXHTML1_1();
        break;
      case self::XHTML1_1_STRICT:
        $this->setXHTML1_1_STRICT();
        break;
      default:
        $this->setXHTML1_1();
    } // switch($docType)
  }


  /**
   * DocType::setXHTML1_1()
   *
   * Diese Funktion erzeugt den HTML-Code für den XHTML1.1 Transisional DocType
   *
   */
  private function setXHTML1_1() {
    $this->arrHTML = array();
    $this->arrHTML[] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"";
    $this->arrHTML[] = "  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
    $this->arrHTML[] = "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"de\" lang=\"de\">";
  }


  /**
   * DocType::setXHTML1_1_STRICT()
   *
   * Diese Funktion erzeugt den HTML-Code für den XHTML1.1 Strict DocType
   *
   */
  private function setXHTML1_1_STRICT() {
    $this->arrHTML = array();
    $this->arrHTML[] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"";
    $this->arrHTML[] = "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
    $this->arrHTML[] = "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"de\" lang=\"de\">";
  }


  /**
   * DocType::getHTML()
   *
   * Gibt das zum docType passende HTML als Array zurück.
   *
   * @return arr_html
   */
  public function getHTML() {
    return $this->arrHTML;
  }


} // class

?>