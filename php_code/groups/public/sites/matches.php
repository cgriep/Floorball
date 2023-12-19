<?php

/****** NOTICE: script error if no leagues are presented ******/

// get id's of leagues
$arr_leagues = DBM_ligen::get_list_liga();


// set id of current leagues to show
if (isset($_GET['table'])) {
  $table = $_GET['table'];
} else {
  $table = $arr_leagues[0][0];
}
if (isset($_GET['day'])) {
  $day = $_GET['day'];
} else {
  $day = 0;
}
$file_name = "";

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

    $file_name = $Liga->get_int_id();
    $liga_name = $Liga->get_str_name();
    $arr_match_days = DBMapper::get_list_Spieltag($arr_leagues[0][$i]);
    $count_matchdays = 0;
    for($k=0; $k<count($arr_match_days[0]); $k++) {

      if($count_matchdays < $arr_match_days[5][$k])
        $count_matchdays = $arr_match_days[5][$k];
    }
    //$count_matchdays = count($arr_match_days[0]);
  }
}

if (!isset($int_num_reg)) {
  $int_num_reg = 3;
}

// display table
// first read table file
// make sure we have the file_name and it exists
if($file_name != "") {

  unset($html);

  for ($j=1; $j<1+$count_matchdays; $j++) {
    if(file_exists("tables/".$file_name."_".$j."_matches.tab")) {
      $file = file("tables/".$file_name."_".$j."_matches.tab");
      $html[0] = "<br/><br/><br/><center><b style=\"font-size:1.2em; color:#171717;\">Spielbegegnungen des ".$j.". Spieltages - ".$liga_name.":</b><br/><br/>";
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
      for ($i=0; $i<count($html); $i++) {
        echo $html[$i];
      }
    }
  }
}
?>