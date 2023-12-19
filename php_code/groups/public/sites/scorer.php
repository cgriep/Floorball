<?php

/****** NOTICE: script error if no leagues are presented ******/

// get id's of leagues
$arr_leagues = DBM_Ligen::get_list_liga();

// set id of current leagues to show
$file_name = "";

// create menu for leagues
for($i=0; $i<count($arr_leagues[0]); $i++) {
  $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
  // if we have the selected league save the shortname of it
  if($arr_leagues[0][$i] == $scorer) {
    $file_name = $Liga->get_str_kurzname();
    $liga_name = $Liga->get_str_name();
  }
}


// display table
// first read table file
// make sure we have the file_name and it exists
if($file_name != "") {
  if(file_exists("tables/".$file_name."_scorer.tab")) {
    $file = file("tables/".$file_name."_scorer.tab");
    $html[0] = "<br/><br/><br/><center><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":</b><br/><br/>";
    $html[0] .="<table class=\"statistik\">";
    $html[0] .= "<tr><th>Platz</th>";
    $html[0] .= "<th>Spieler</th>";
    $html[0] .= "<th>Mannschaft</th>";
    $html[0] .= "<th>Tore</th>";
    $html[0] .= "<th>Vorl.</th>";
    $html[0] .= "<th>Punkte</th>";
    for($i=1; $i<1+count($file); $i++) {
      $arr_row = explode(";", $file[$i-1]);
      if(count($arr_row) > 2) {
        if($arr_row[3] == 0) break;
	if($i%2)
	  $html[$i] = "<tr class=\"colored\">";
	else
	  $html[$i] = "<tr>";
	$html[$i] .= "<td>".$arr_row[0].".</td>";
	$html[$i] .= "<td class=\"left\">".$arr_row[1]."</td>";
	$html[$i] .= "<td class=\"left\">".$arr_row[2]."</td>";
	$html[$i] .= "<td>".$arr_row[4]."</td>";
	$html[$i] .= "<td>".$arr_row[5]."</td>";
	$html[$i] .= "<td style=\"color:#333399;\">".$arr_row[3]."</td>";
	$html[$i] .= "</tr>\n";
      }
    }
    $html[] = "</table></center><br/><br/>\n";
    for($i=0; $i<count($html); $i++) echo $html[$i];
  }
  unset($html);
}
?>