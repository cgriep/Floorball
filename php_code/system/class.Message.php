<?php
/**
 * Message
 *
 * Wird zum Erstellen von Nachrichten genutzt.
 *
 * @package SYSTEM
 *
 * @author Malte Römmermann
 * @copyright 2009 MR2
 * @since 11.08.2007
 * @version 0.1
 * @access public
 *
 * @todo -c Diese Klasse sollte teil des HTML-Systems werden
 */
class Message {

  private $arr_errors = array();
  private $arr_warnings = array();
  private $arr_messages = array();
  private $width = 50;

  public function __construct(){
  }

  public function add_error($str_error) {
    $this->arr_errors[] = $str_error;
  }

  public function add_warning($str_warning) {
    $this->arr_warnings[] = $str_warning;
  }

  public function add_message($str_message) {
    $this->arr_messages[] = $str_message;
  }

  public function show_errors() {
    if(count($this->arr_errors)) {
      echo "<div style=\"border:2px solid red; background-color:#ffcccc; padding: 4px 4px 4px 4px; text-align:center; width:".$this->width."em;\">";
      for($i=0; $i<count($this->arr_errors); $i++) {
	echo $this->arr_errors[$i]."<br/>";
      }
      echo "</div><br/>";
    }
  }

  public function clear_errors() {
    $this->arr_errors = array();
  }

  public function show_warnings() {
    if(count($this->arr_warnings)) {
      echo "<div style=\"border:2px solid #bbaa55; background-color:#ffbb88; padding: 4px 4px 4px 4px; text-align:center; width:".$this->width."em;\">";
      for($i=0; $i<count($this->arr_warnings); $i++) {
	echo $this->arr_warnings[$i]."<br/>";
      }
      echo "</div><br/>";
    }
  }

  public function clear_warnings() {
    $this->arr_warnings = array();
  }

  public function show_messages() {
    if(count($this->arr_messages)) {
      echo "<div style=\"border:2px solid green; background-color:#ccffcc; padding: 4px 4px 4px 4px; text-align:center; width:".$this->width."em;\">";
      for($i=0; $i<count($this->arr_messages); $i++) {
	echo $this->arr_messages[$i]."<br/>";
      }
      echo "</div><br/>";
    }
  }

  public function clear_messages() {
    $this->arr_messages = array();
  }

  public function show_all() {
    $this->show_errors();
    $this->show_warnings();
    $this->show_messages();
  }

  public function clear_all() {
    $this->clear_errors();
    $this->clear_warnings();
    $this->clear_messages();
  }

  public function paint() {
    $this->show_all();
    $this->clear_all();
  }
}

?>