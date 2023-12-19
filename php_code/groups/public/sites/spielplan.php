<?php

/****** NOTICE: script error if no leagues are presented ******/

// get id's of leagues
$arr_leagues = DBM_Ligen::get_list_liga();

// set id of current leagues to show
if(isset($_GET['table'])) $table = $_GET['table'];
else $table = $arr_leagues[0][0];
$count_matchdays = 0;
$liga_name = "Fehler - unbekannte Liga";
// create menu for leagues
for($i=0; $i<count($arr_leagues[0]); $i++) {
  $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
  if($arr_leagues[0][$i] == $table) {
    $int_id_kategorie = $Liga->get_int_id_kategorie();
    if($int_id_kategorie == 1 || $int_id_kategorie == 4)
      $int_num_reg = 3;
    else $int_num_reg = 2;

    $file_name = $Liga->get_int_id();//$Liga->get_str_kurzname();
    $liga_name = $Liga->get_str_name();
    $arr_match_days = DBMapper::get_list_Spieltag($arr_leagues[0][$i]);
    $count_matchdays = count($arr_match_days[0]);
  }
}

if(!isset($int_num_reg)) $int_num_reg = 3;

$arr_new_games = array();
// spieltage umsortieren
for($i=0; $i<$count_matchdays; $i++) {
  // liste der Begegnungen laden
  $Spielort = DBMapper::read_Spielort($arr_match_days[4][$i]);

  $arr_games = DBMapper::get_list_Begegnung($arr_match_days[0][$i]);
  for($k=0; $k<count($arr_games[0]); $k++) {
    if($Spielort)
      $arr_games[9][$k] = $Spielort->get_str_name();
    else $arr_games[9][$k] = "Spielort";
    $arr_games[10][$k] = $arr_match_days[1][$i];    
    for($l=0; $l<11; $l++)
      $arr_new_games[$arr_match_days[5][$i]][$l][] = $arr_games[$l][$k];
  }
}

echo "<div id=\"table\">";

$html[0]  = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Spielplan";
$html[0] .= " - ".$liga_name.":</b> "; //(<a href=''>Druckansicht</a>)<br/><br/>";
$html[0] .= "<table class=\"statistik\">";
$html[0] .= "<tr><th class=\"right\">Nr.</th>";
$html[0] .= "<th>Datum</th>";
$html[0] .= "<th>Zeit</th>";
$html[0] .= "<th class=\"left\">Spielort</th>";
$html[0] .= "<th class=\"right\">Heim</th>";
$html[0] .= "<th class=\"np\">-</th>";
$html[0] .= "<th class=\"left\">Gast</th>";
$html[0] .= "<th>Schiedsrichter</th></tr>";

$index2 = 1;
$row_nr = 0;
foreach($arr_new_games as $key => $value) {
  // key ist spieltags_nummer
  // value entspricht arr_games

  $str_line = " style=\"border-width:1px 0px 0px 0px;\"";
  while(count($value[0])) {
    $index = -1;
    foreach($value[0] as $key2 => $value2) {
      if($index < 0) $index = $key2;
      else if($value[5][$key2] < $value[5][$index])
        $index = $key2;
    }
    
    $Mannschaft1 = DBMapper::read_Mannschaft($value[1][$index], $value[7][$index]);
    $Mannschaft2 = DBMapper::read_Mannschaft($value[2][$index], $value[8][$index]);
    $cont = false;
    if(!$Mannschaft1 || $Mannschaft1->get_bool_genehmigt() == 2) $cont = true;
    if(!$Mannschaft2 || $Mannschaft2->get_bool_genehmigt() == 2) $cont = true;
    if(!$cont) {
      $row_nr++;
      if(!($index2%2)) $str_color = " style=\"background-color:#eeeeee;\"";
      else $str_color = "";
      $html[$index2] = "<tr$str_color>\n";
      $html[$index2] .= "<td class=\"right\"$str_line>".$value[5][$index]."</td>\n";
      $html[$index2] .= "<td class=\"left\"$str_line>".$value[10][$index]."</td>\n";
      $html[$index2] .= "<td class=\"left\"$str_line>".$value[3][$index]."</td>\n";
      $html[$index2] .= "<td class=\"left\"$str_line>".$value[9][$index]."</td>\n";
      $html[$index2] .= "<td class=\"right\"$str_line>".$Mannschaft1->get_str_name()."</td>\n";
      $html[$index2] .= "<td class=\"np\"$str_line>-</td>";
      $html[$index2] .= "<td class=\"left\"$str_line>".$Mannschaft2->get_str_name()."</td>\n";
      // parse schiedsrichter
      $arr_tmp = explode("#", $value[4][$index]);
      $html[$index2] .= "<td class=\"left\"$str_line>".$arr_tmp[0]."</td>\n";
      $html[$index2] .= "</tr>\n";
      $index2++;
    }
    $str_line = "";
    for($i=0; $i<11; $i++)
      unset($value[$i][$index]);
  }
}

/*
$index = 1;
$day_nr = $arr_match_days[5][0];
$row_nr = 0;
for($i=0; $i<$count_matchdays; $i++) {
  // liste der Begegnungen laden
  $arr_games = DBMapper::get_list_Begegnung($arr_match_days[0][$i]);
  $Spielort = DBMapper::read_Spielort($arr_match_days[4][$i]);
  $str_line = "";
  if($day_nr != $arr_match_days[5][$i]) {
    $str_line = " style=\"border-width:1px 0px 0px 0px;\"";
    $day_nr = $arr_match_days[5][$i];
  }

  for($k=0; $k<count($arr_games[0]); $k++) {
    $Mannschaft1 = DBMapper::read_Mannschaft($arr_games[1][$k], $arr_games[7][$k]);
    $Mannschaft2 = DBMapper::read_Mannschaft($arr_games[2][$k], $arr_games[8][$k]);
    if(!$Mannschaft1 || $Mannschaft1->get_bool_genehmigt() == 2) continue;
    if(!$Mannschaft2 || $Mannschaft2->get_bool_genehmigt() == 2) continue;
    $row_nr++;
    if($k>0) $str_line = "";
    if(!($index%2)) $str_color = " style=\"background-color:#eeeeee;\"";
    else $str_color = "";
    $html[$index] = "<tr$str_color>\n";
    $html[$index] .= "<td class=\"right\"$str_line>".$arr_games[5][$k]."</td>\n";
    $html[$index] .= "<td class=\"left\"$str_line>".$arr_match_days[1][$i]."</td>\n";
    $html[$index] .= "<td class=\"left\"$str_line>".$arr_games[3][$k]."</td>\n";
    if($Spielort)
      $html[$index] .= "<td class=\"left\"$str_line>".$Spielort->get_str_name()."</td>\n";
    else
      $html[$index] .= "<td class=\"left\"$str_line>Spielort</td>\n";
    $html[$index] .= "<td class=\"right\"$str_line>".$Mannschaft1->get_str_name()."</td>\n";
    $html[$index] .= "<td class=\"np\"$str_line>-</td>";
    $html[$index] .= "<td class=\"left\"$str_line>".$Mannschaft2->get_str_name()."</td>\n";
    // parse schiedsrichter
    $arr_tmp = explode("#", $arr_games[4][$k]);
    $html[$index] .= "<td class=\"left\"$str_line>".$arr_tmp[0]."</td>\n";
    $html[$index] .= "</tr>\n";
    $index++;
  }
}
*/
$html[] = "</table>";
for($i=0; $i<count($html); $i++) echo $html[$i];
echo "</div>";

?>
