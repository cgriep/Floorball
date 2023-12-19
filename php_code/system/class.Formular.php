<?php
/**
 * Formular
 *
 * Die Klasse enthält Methoden um Formulare in php zu erstellen.
 *
 * @package SYSTEM
 *
 * @author Malte Römmermann
 * @copyright 2009 MR2
 * @since 11.08.2007
 * @version 0.1
 * @access public
 */
class Formular
{
    // --- ATTRIBUTES ---

    /**
     * Speichert die erstellten Formulare ab. Die Formulare können über eine
     * ID erreicht werden.
     *
     * @access private
     * @var array
     */
    private $arr_form = array();

    // --- OPERATIONS ---

    /**
     * Erstellt ein neues Formular, welches in einer privaten Liste verwaltet
     * Jedes Formular erhält eine ID, die als Rückgabewert dieser Funktion
     * wird.
     *
     * @access public
     * @author Malte Römmermann
     * @param string
     * @param int
     * @param int
     * @param int
     * @param int
     * @return int
     */
    public function create_form($str_name, $str_site = "", $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $str_more = "", $int_fsize = -1)
    {
        $returnValue = (int) 0;

        if($this->arr_form == null) $this->arr_form = array();
        $int_fid = count($this->arr_form);
        $this->arr_form[$int_fid][0] = $str_name;
        $style = "position:relative;";
        if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
        if($int_width != -1) $style .= " width:".$int_width."em;";
        if($int_height != -1) $style .= " height:".$int_height."em;";
        if($int_fsize != -1) $style .=" font-size:".$int_fsize."em;";
        $this->arr_form[$int_fid][1] = "<form class=\"generic-form\" name=\"".$str_name."\" action=\"index.php?seite=".$str_site."\" method=\"post\" ".$str_more." style=\"".$style."\">";
        $returnValue = $int_fid;

        return (int) $returnValue;
    }

    /**
     * Die Methode erstellt ein Texteingabefeld.
     *
     * @access public
     * @author Malte Römmermann
     * @param int
     * @param string
     * @param string
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @return void
     */
    public function input_text($int_fid, $str_name, $str_value, $int_size = 20, $int_maxsize = 255, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
            if($int_width != -1) $style .= " width:".$int_width."em;";
            if($int_height != -1) $style .= " height:".$int_height."em;";
            $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-text\" type=\"text\" name=\"$str_name\" value=\"$str_value\" size=\"$int_size\" maxlength=\"$int_maxsize\" style=\"$style\" />";
        }
    }

    public function input_password($int_fid, $str_name, $str_value, $int_size = 20, $int_maxsize = 255, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
            if($int_width != -1) $style .= " width:".$int_width."em;";
            if($int_height != -1) $style .= " height:".$int_height."em;";
            $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-text\" type=\"password\" name=\"$str_name\" value=\"$str_value\" size=\"$int_size\" maxlength=\"$int_maxsize\" style=\"$style\" />";
        }
    }

    public function input_checkbox($int_fid, $str_name, $str_value, $str_text, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $bool_checked = 0, $bool_enabled = 1)
    {
      if(isset($this->arr_form[$int_fid])) {
        $style = "";
        if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
        if($int_width != -1) $style .= " width:".$int_width."em;";
        if($int_height != -1) $style .= " height:".$int_height."em;";
        $checked = "";
        if($bool_checked) $checked = " checked=\"checked\"";
        $enabled = "";
        if(!$bool_enabled) $enabled = " disabled=\"disabled\"";

        $this->arr_form[$int_fid][1] .= "\n<div style=\"$style\"><input type=\"checkbox\" name=\"$str_name\" value=\"$str_value\"$checked$enabled /> $str_text</div>";
      }
    }

    public function input_radio($int_fid, $str_name, $str_value, $str_text, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $bool_checked = 0, $bool_enabled = 1)
    {
      if(isset($this->arr_form[$int_fid])) {
        $style = "";
        if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
        if($int_width != -1) $style .= " width:".$int_width."em;";
        if($int_height != -1) $style .= " height:".$int_height."em;";
        $checked = "";
        if($bool_checked) $checked = " checked=\"checked\"";
        $enabled = "";
        if(!$bool_enabled) $enabled = " disabled=\"disabled\"";

        $this->arr_form[$int_fid][1] .= "\n<div style=\"$style\"><input type=\"radio\" name=\"$str_name\" value=\"$str_value\"$checked$enabled /> $str_text</div>";
      }
    }

    /**
     * Erstellt einen Button für ein Formular.
     *
     * @access public
     * @author Malte Römmermann
     * @return void
     */
    public function input_button($int_fid, $str_name, $str_value, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
            if($int_width != -1) $style .= " width:".$int_width."em;";
            if($int_height != -1) $style .= " height:".$int_height."em;";
            $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-button\" type=\"submit\" name=\"$str_name\" value=\"$str_value\" style=\"$style\" />";
        }
    }

    /**
     * Gibt einen Text innerhalb des Formulares aus.
     *
     * @access public
     * @author Malte Römmermann
     * @param int
     * @param string
     * @param int
     * @param int
     * @param int
     * @param int
     * @return void
     */
    public function print_text($int_fid, $str_text, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $int_fsize = -1, $bg_color = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:". number_format($int_left, 2, ".", "") ."em;";
            if($int_top != -1)  $style .= " top:". number_format($int_top, 2, ".", "") ."em;";
            if($int_width != -1) $style .= " width:".number_format($int_width, 2, ".", "")."em;";
            if($int_height != -1) $style .= " height:". number_format($int_height, 2, ".", "") ."em;";
            if($int_fsize != -1) $style .= " font-size:".$int_fsize."em;";
            if($bg_color != -1) $style .= " background-color:".$bg_color.";";
            if($style == "") $this->arr_form[$int_fid][1] .= "\n$str_text";
            else $this->arr_form[$int_fid][1] .= "\n<div style=\"$style\">$str_text</div>";
        }
    }

    /**
     * Gibt ein Formular in HTML-Code aus.
     *
     * @access public
     * @author Malte Römmermann
     * @param int
     * @return void
     */
    public function echo_form($int_fid)
    {
        if(isset($this->arr_form[$int_fid])) {
            echo $this->arr_form[$int_fid][1]."\n</form>";
        }
    }

    /**
     * Short description of method input_hidden
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param int
     * @param string
     * @param string
     * @return void
     */
    public function input_hidden($int_fid, $str_name, $str_value)
    {
        if(isset($this->arr_form[$int_fid])) {
            $this->arr_form[$int_fid][1] .= "\n<input type=\"hidden\" name=\"$str_name\" value=\"$str_value\" />";
        }
    }

    /**
     * Short description of method input_list
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param int
     * @param string
     * @param array
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @return void
     */
    public function input_list($int_fid, $str_name, $arr_values, $arr_ids = 0, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $int_bwidth = -1, $int_bheight = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
            if($int_width != -1) $style .= " width:".$int_width."em;";
            if($int_height != -1) $style .= " height:".$int_height."em;";
            $style .= " overflow:auto;";
            $this->arr_form[$int_fid][1] .= "\n<div class=\"generic-list\" style=\"$style\">";
            $style = "";
            if($int_bwidth != -1) $style .= "width:".$int_bwidth."em;";
            if($int_bheight != -1) $style .= "height:".$int_bheight."em;";
            for($i=0; $i<count($arr_values); $i++) {
                if($i > 0) $this->arr_form[$int_fid][1] .= "<br/>";
                if(isset($arr_ids[$i])) $ids = "_".$arr_ids[$i];
                else $ids = "";
                if($arr_values[$i]) $this->arr_form[$int_fid][1] .= "\n\t<input class=\"generic-button\" type=\"submit\" name=\"".$str_name.$ids."\" value=\"".$arr_values[$i]."\" style=\"$style\" />";
            }
            $this->arr_form[$int_fid][1] .= "\n</div>";
        }
    }


    /**
     * Short description of method input_select
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param int
     * @param string
     * @param array
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @param int
     * @return void
     */
    public function input_select($int_fid, $str_name, $arr_values, $arr_ids, $str_select, $int_size, $bool_mult, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1)
    {
        if(isset($this->arr_form[$int_fid])) {
            $style = "";
            if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
            if($int_width != -1) $style .= " width:".$int_width."em;";
            if($int_height != -1) $style .= " height:".$int_height."em;";
            if($bool_mult) $str_mult = " multiple";
            else $str_mult = "";
            $this->arr_form[$int_fid][1] .= "\n<select class=\"generic-select\" style=\"$style\" name=\"$str_name\" size=\"$int_size\"$str_mult>";
            for($i=0; $i<count($arr_values); $i++) {
                if($arr_ids && isset($arr_ids[$i])) {
                    $value = $arr_ids[$i];
                    $str_value = "value=\"$value\"";
                }
                else $value = $arr_values[$i];
                if($value == $str_select) $select = "selected=\"selected\"";
                else $select = "";
                if($arr_values[$i]) $this->arr_form[$int_fid][1] .= "\n\t<option class=\"generic-option\" $str_value $select> ".$arr_values[$i]."</option>";
            }
            $this->arr_form[$int_fid][1] .= "\n</select>";
        }
    }

  public function input_menu_button($int_fid, $str_name, $str_value, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1) {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-m-button\" type=\"submit\" name=\"$str_name\" value=\"$str_value\" style=\"$style\" />";
    }
  }

  public function input_t1_button($int_fid, $str_name, $str_value, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $align = 0) {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      if($align) $style .=" text-align:left;";
      $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-t1-button\" type=\"submit\" name=\"$str_name\" value=\"$str_value\" style=\"$style\" />";
    }
  }

  public function input_t2_button($int_fid, $str_name, $str_value, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $align = 0) {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      if($align) $style .=" text-align:left;";
      $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-t2-button\" type=\"submit\" name=\"$str_name\" value=\"$str_value\" style=\"$style\" />";
    }
  }

  public function input_h_list($int_fid, $str_name, $arr_values, $arr_ids = 0, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $int_bwidth = -1, $int_bheight = -1) {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      //$style .= " overflow:auto;";
      $this->arr_form[$int_fid][1] .= "\n<div class=\"generic-h-list\" style=\"$style\">";
      $style = "";
      if($int_bwidth != -1) $style .= "width:".$int_bwidth."em;";
      if($int_bheight != -1) $style .= "height:".$int_bheight."em;";
      for($i=0; $i<count($arr_values); $i++) {
        if($i > 0) $this->arr_form[$int_fid][1] .= "<br/>";
        if(isset($arr_ids[$i])) $ids = "_".$arr_ids[$i];
        else $ids = "";
        if($arr_values[$i]) $this->arr_form[$int_fid][1] .= "\n\t<input class=\"generic-button\" type=\"submit\" name=\"".$str_name.$ids."\" value=\"".$arr_values[$i]."\" style=\"$style\" />";
      }
      $this->arr_form[$int_fid][1] .= "\n</div>";
    }
  }

  public function input_file($int_fid, $str_name, $int_size = 20, $int_maxsize = 255, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1)
  {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      $this->arr_form[$int_fid][1] .= "\n<input class=\"generic-text\" type=\"file\" name=\"$str_name\" size=\"$int_size\" accept=\"text/*\" style=\"$style\" />";
    }
  }

  public function input_check_table($int_fid, $str_name, $arr_values, $arr_ids = 0, $arr_titles, $arr_check, $arr_v, $int_num_check, $int_left = -1, $int_top = -1, $int_width = -1, $int_height = -1, $int_bwidth = -1, $int_bheight = -1) {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      if($int_width != -1) $style .= " width:".$int_width."em;";
      if($int_height != -1) $style .= " height:".$int_height."em;";
      //$style .= " overflow:auto;";
      $this->arr_form[$int_fid][1] .= "\n<div style=\"$style\">";
      $style = "";
      $this->arr_form[$int_fid][1] .= "\n<table class=\"generic-check-table\">";
      $this->arr_form[$int_fid][1] .= "<tr>";
      for($i=0; $i<count($arr_titles); $i++) {
        $this->arr_form[$int_fid][1] .= "<td style='text-align:center;'>".$arr_titles[$i]."</td>";
      }
      $this->arr_form[$int_fid][1] .= "</tr>";
      for($i=0; $i<count($arr_ids); $i++) {
        if($i%2) $row = "class='generic-check-table-r1'";
        else $row = "class='generic-check-table-r2'";
        $this->arr_form[$int_fid][1] .= "<tr ".$row.">";
        for($k=0; $k<count($arr_values); $k++) {
          $this->arr_form[$int_fid][1] .= "<td class='generic-check-table-cell'>".$arr_values[$k][$i]."</td>";
        }
        for($k=0; $k<$int_num_check; $k++) {
          $check = "";
          if($arr_check[$i] == $k) $check = " checked='checked'";
          $this->arr_form[$int_fid][1] .= "<td class='generic-check-table-cell'>"."<input type='radio' name='check".$i."' value='".$k.$arr_v[$i]."'".$check."/>"."</td>";
        }
      $this->arr_form[$int_fid][1] .= "</tr>";
      }
      $this->arr_form[$int_fid][1] .= "</table>";
      $this->arr_form[$int_fid][1] .= "\n</div>";
    }
  }

  public function input_textarea($int_fid, $str_name, $str_value,
                                 $int_width = 20, $int_height = 255,
                                 $int_left = -1, $int_top = -1)
  {
    if(isset($this->arr_form[$int_fid])) {
      $style = "";
      if($int_left != -1) $style = "position:absolute; left:".$int_left."em; top:".$int_top."em;";
      $this->arr_form[$int_fid][1] .= "\n<textarea class=\"generic-text\" name=\"$str_name\" cols=\"$int_width\" rows=\"$int_height\" style=\"$style\" />$str_value</textarea>";
    }
  }

} /* end of class Formular */

?>