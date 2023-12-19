<?php
$x1 = 25;
$x2 = 36.7;

$form_id = $my_form->create_form("license", "Lizenzen", -1, -1, 60,
                                    30, 'enctype="multipart/form-data"');
$my_form->print_text($form_id, "Lizenzen", 3, 1);
if(isset($arr_ligen) && count($arr_ligen[0]) > 0) {
  $my_form->input_select($form_id, "int_id_league", $arr_ligen[1],
                         $arr_ligen[0], $int_id_league, 1, 0, 3, 3, 16);
  $my_form->input_button($form_id, "show_league", "Liga w&auml;hlen",
                         23, 3, 10, 2);
  $my_form->print_text($form_id, "ok = Lizenz angenommen", 48, 0.8, -1, -1, 0.86, "#99bbff");
  $my_form->print_text($form_id, "ab = Lizenz abgelehnt", 48, 2.2, -1, -1, 0.86, "#99bbff");
  $my_form->print_text($form_id, "be = Lizenz beantragt", 48, 3.6, -1, -1, 0.86, "#99bbff");
  $my_form->print_text($form_id, "tr = Transfer vollzogen", 48, 5, -1, -1, 0.86, "#99bbff");
  $my_form->print_text($form_id, "ig = Lizenz ignorieren", 48, 6.4, -1, -1, 0.86, "#99bbff");
}
if($bool_show_table) {
  $n = 0;
  $bg = "";
  $ids = 1;
  $arr_titles = array("Verein", "Spieler", "geb.-D", "beantragt", "bearbeitet", "ok", "ab", "be", "tr", "ig");
  $arr_v = array();
  $arr_check = array();
  $arr_ids = array();
  $arr_table[0] = array();
  $arr_table[1] = array();
  $arr_table[2] = array();
  $arr_table[3] = array();
  $arr_table[4] = array();

  for($i=0; $i<count($arr_teams[0]); $i++) {
    for($k=0; $k<count($arr_licenses[$i][0]); $k++) {
      $arr_table[0][] = $arr_teams[1][$i];        // team name
      $arr_table[1][] = $arr_licenses[$i][1][$k].", ".$arr_licenses[$i][2][$k]; // spieler name
      $arr_table[2][] = $arr_licenses[$i][3][$k]; // alter
      $arr_table[3][] = $arr_licenses[$i][6][$k][0];
      $arr_table[4][] = $arr_licenses[$i][5][$k][1];
      $arr_ids[] = $ids++;
      $arr_v[] = ":".$arr_licenses[$i][5][$k][0].":".$arr_licenses[$i][0][$k].":".$arr_teams[0][$i];
      $found = 0;
      for($n=0; $n<count($arr_status_lizenz[0]); $n++) {
        if($arr_licenses[$i][5][$k][0] == $arr_status_lizenz[0][$n]) {
          if($arr_status_lizenz[1][$n] == "erteilt") $found = 1;
          else if($arr_status_lizenz[1][$n] == "abgelehnt") $found = 2;
          else if($arr_status_lizenz[1][$n] == "beantragt") $found = 3;
          else if($arr_status_lizenz[1][$n] == "Transfer") $found = 4;
          else $found = 5;
          break;
        }
      }
      if($found) $arr_check[] = $found-1;
      else $arr_check[] = 3;//count($arr_status_lizenz[0]);
    }
  }
  //  for($i=0; $i<count($arr_status_lizenz[0]); $i++)
  $my_form->input_check_table($form_id, "", $arr_table, $arr_ids, $arr_titles,
            $arr_check, $arr_v, 5, 3, 10);
  /*
  for($i=0; $i<count($arr_teams[0]); $i++) {
    for($k=0; $k<count($arr_licenses[$i][0]); $k++) {
      if($bg == "") $bg = "#ccccee";
      else $bg = "";
      $my_form->print_text($form_id, $arr_teams[1][$i], 5, 8+$n,-1,-1,0.8,$bg);
      $my_form->print_text($form_id, $arr_licenses[$i][1][$k], 30, 8+$n,-1,-1,0.8,$bg);
      $n+=1.1;
    }
  }
  */
  $my_form->input_button($form_id, "save", "Änderungen &uuml;bernehmen", 45.6, 8, 13, 2);
  $my_form->input_button($form_id, "save_all", "alle akzeptieren", 3, 8, 10, 2);
  $my_form->print_text($form_id, "<br/>", 0, 11+$n,-1,-1,0.8);
}
/*
$my_form->input_list($form_id, "list1", $arr_ligen[1], $arr_ligen[0], 2, 3, 20, 20, 19, 2);
if($bool_show_data) {
    $my_form->input_hidden($form_id, "id", $int_id);
    $my_form->input_hidden($form_id, "nav", "league");
    $my_form->print_text($form_id, "Bezeichnung der Liga:", $x1, 5);
    $my_form->input_text($form_id, "l_name", $str_l_name, 25, 255, $x2, 5);
    $my_form->print_text($form_id, "Saison:", $x1, 7);
    $my_form->input_select($form_id, "id_season", $arr_seasons[1], $arr_seasons[0],$int_id_season, 1, 0, $x2, 7, 12);
    $my_form->print_text($form_id, "Kategorie:", $x1, 9);
    $my_form->input_select($form_id, "id_kate", $arr_kate[1], $arr_kate[0],$int_id_kate, 1, 0, $x2, 9, 12);
    $my_form->print_text($form_id, "Klasse:", $x1, 11);
    $my_form->input_select($form_id, "id_klasse", $arr_klasse[1], $arr_klasse[0],$int_id_klasse, 1, 0, $x2, 11, 12);
    $my_form->input_button($form_id, "save", "Daten speichern", $x1+15, 22, 13, 2);
    $my_form->input_file($form_id, "l_file", 25, 255, $x1, 25);
    $my_form->input_button($form_id, "read_file", "Datei einlesen", $x1+2, 29, 13, 2);
}
*/
echo "\n<br/><center>\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>
