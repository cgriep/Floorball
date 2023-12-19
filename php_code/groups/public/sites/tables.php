<?php

/****** NOTICE: script error if no leagues are presented ******/

// get id's of leagues
$arr_leagues = DBM_Ligen::get_list_liga();

// set id of current leagues to show
if(isset($_GET['table'])) $table = $_GET['table'];
else $table = $arr_leagues[0][0];
if(isset($_GET['day'])) $day = $_GET['day'];
else $day = 0;
$file_name = "";
var_dump($arr_leagues);
// create menu for leagues
for($i=0; $i<count($arr_leagues[0]); $i++) {
  $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
  //if($i>0) echo " - ";
  //echo "<a href=\"index.php?table=".$arr_leagues[0][$i]."\">";
  //echo $Liga->get_str_kurzname()."</a>";
  // if we have the selected league save the shortname of it
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

// display table
// first read table file
// make sure we have the file_name and it exists
if($file_name != "") {
  // first get the last already played matchday
  for($i=1; $i<1+$count_matchdays; $i++)
    if(!file_exists("tables/".$file_name."_".$i."_matches.tab"))
      break;
  $i--;
  // $i is the last machtday
  if(!$day || $day > $i) $day = $i; // color 424242
  echo "<b style=\"font-size:1.2em; color:#424242;\"><br/>Spieltage:<br/>";
  for($n=0; $n<$i; $n++) {
    if($n>0) echo " &bull; ";
    echo "<a  href=\"index.php?table=".$table."&amp;day=".($n+1)."\" style=\"color:#11bb11;\">".($n+1)."</a>";
  }
  for($n=$i; $n<$count_matchdays; $n++) {
    if($n>0) echo " &bull; ";
    echo "<b style=\"color:#4444cc;\">".($n+1)."</b>";
  }
  echo "</b>";
  if(file_exists("tables/".$file_name."_".$day.".tab")) {
    $file = file("tables/".$file_name."_".$day.".tab");
    if($day == $i)
     $html[0] = "<br/><br/><br/><center><b style=\"font-size:1.2em; color:#171717;\">Aktuelle Tabelle - ".$liga_name.":</b><br/><br/>";
    else
      $html[0] = "<br/><br/><br/><center><b style=\"font-size:1.2em; color:#171717;\">Tabelle nach dem ".$day.". Spieltag - ".$liga_name.":</b><br/><br/>";
    $html[0] .="<table class=\"statistik\">";
    $html[0] .= "<tr><th>Platz</th>";
    $html[0] .= "<th>Mannschaft</th>";
    $html[0] .= "<th>Spiele</th>";
    $html[0] .= "<th>S</th>";
    $html[0] .= "<th>U</th>";
    $html[0] .= "<th>N</th>";
    $html[0] .= "<th>SDS</th>";
    $html[0] .= "<th>SDN</th>";
    $html[0] .= "<th colspan=\"3\">Tore</th>";
    $html[0] .= "<th>Tordiff.</th>";
    $html[0] .= "<th>Punkte</th></tr>\n";
    $j = 1;
    for($i=1; $i<1+count($file); $i++) {
      $arr_row = explode(";", $file[$i-1]);
      if ($table == 2 && $day > 11 &&
            ( $arr_row[0] == "TB Uphusen Vikings" || $arr_row[0] == "SG Seebergen-Vahrenwald" )) {
        $html[$i] = "";
        $arr_row = array();
      }
      if(count($arr_row) > 2) {
      	if($j%2)
      	  $html[$i] = "<tr class=\"colored\">";
      	else
      	  $html[$i] = "<tr>";
      	$html[$i] .= "<td>".$j.".</td>";
      	$html[$i] .= "<td class=\"left\">".$arr_row[0]."</td>";
      	$html[$i] .= "<td>".$arr_row[1]."</td>";
      	$html[$i] .= "<td>".$arr_row[2]."</td>";
      	$html[$i] .= "<td>".$arr_row[3]."</td>";
      	$html[$i] .= "<td>".$arr_row[4]."</td>";
      	$html[$i] .= "<td>".$arr_row[5]."</td>";
      	$html[$i] .= "<td>".$arr_row[6]."</td>";
      	$html[$i] .= "<td>".$arr_row[7]."</td>";
      	$html[$i] .= "<td> : </td>";
      	$html[$i] .= "<td>".$arr_row[8]."</td>";
      	$html[$i] .= "<td>".$arr_row[9]."</td>";
      	$html[$i] .= "<td style=\"color:#2222aa;\">".$arr_row[10]."</td>";//333399
      	$html[$i] .= "</tr>\n";
        $j++;
      }
    }
    $html[] = "</table></center>\n";
    for($i=0; $i<count($html); $i++) echo $html[$i];
  }
  unset($html);
  if(file_exists("tables/".$file_name."_".$day."_matches.tab")) {
    $file = file("tables/".$file_name."_".$day."_matches.tab");
    $html[0] = "<br/><br/><br/><center><b style=\"font-size:1.2em; color:#171717;\">Spielbegegnungen des ".$day.". Spieltages - ".$liga_name.":</b><br/><br/>";
    $html[0] .= "<table class=\"statistik\">";
    $html[0] .= "<tr><th>Heim</th>";
    $html[0] .= "<th>Gast</th>";
    $html[0] .= "<th colspan=\"2\">Ergebnis</th></tr>";
    for($i=1; $i<1+count($file); $i++) {
      $arr_row = explode(";", $file[$i-1]);
      if(count($arr_row) > 2) {
        if($i%2)
          $html[$i] = "<tr class=\"colored\">";
        else
	        $html[$i] = "<tr>";
	      $html[$i] .= "<td>".$arr_row[0]."</td>";
	      $html[$i] .= "<td>".$arr_row[1]."</td>";
	      $html[$i] .= "<td>".$arr_row[2].":".$arr_row[3]."</td>";
	      $html[$i] .= "<td>(".$arr_row[4].":".$arr_row[5].";";
	      $html[$i] .= $arr_row[6].":".$arr_row[7].";";

	      $html[$i] .= $arr_row[8].":".$arr_row[9];
	      if($int_num_reg == 3)
          $html[$i] .= ";".$arr_row[10].":".$arr_row[11];
        $html[$i] .= ")</td>";
        $html[$i] .= "</tr>\n";
      }
    }
    $html[] = "</table></center><br/><br/>\n";
    for($i=0; $i<count($html); $i++) echo $html[$i];
  }

}
?>