<?php

/****** NOTICE: script error if no leagues are presented ******/

// get id's of leagues
$arr_tmp = DBM_Ligen::get_list_liga();
$arr_leagues[0] = array();
$arr_leagues[1] = array();
$arr_leagues[2] = array();
for($i=0; $i<count($arr_tmp[0]); $i++) {
  if($arr_tmp[3][$i] != -3 && $arr_tmp[3][$i] != -4) {
    $arr_leagues[0][] = $arr_tmp[0][$i];
    $arr_leagues[1][] = $arr_tmp[1][$i];
    $arr_leagues[2][] = $arr_tmp[2][$i];
  }
}
// set id of current leagues to show
if(isset($_GET['table'])) $table = $_GET['table'];
else $table = $arr_leagues[0][0];
if(isset($_GET['day'])) $day = $_GET['day'];
else $day = 0;
$file_name = "";
$pokal = false;

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
    if($int_id_kategorie == 3 || $int_id_kategorie == 4)
      $pokal = true;
    else $pokal = false;
    if($int_id_kategorie == 100)
      $dm = 1;
    else if($int_id_kategorie == 101)
      $dm = 2;
    else $dm = 0;
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

if(!isset($int_num_reg)) $int_num_reg = 3;

// display table
// first read table file
// make sure we have the file_name and it exists
if($file_name != "") {
  // first get the last already played matchday
  $k=0;
  for($i=1; $i<1+$count_matchdays; $i++)
    if(file_exists("tables/".$file_name."_".$i."_matches.tab"))
      $k = $i;
  $i = $k;
  // $i is the last machtday
  echo "<div id=\"table\" style=\"font-size:10px;\">";
  if(!$day || $day > $i) $day = $i; // color 424242
/*
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
*/

  if(!$dm && !$pokal && file_exists("tables/".$file_name."_".$day.".tab")) {
    $file = file("tables/".$file_name."_".$day.".tab");
    if($day == $i)
     $html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Aktuelle Tabelle - ".$liga_name.":</b><br/><br/>";
    else
      $html[0] = "<br/><br/><br/><b style=\"font-size:1.2em; color:#171717;\">Tabelle nach dem ".$day.". Spieltag - ".$liga_name.":</b><br/><br>";
    $html[0] .="\n<table class=\"statistik\">";
    $html[0] .= "\n  <tr>\n    <th class=\"right\">Pl.</th>";
    $html[0] .= "\n    <th class=\"left\">Mannschaft</th>";
    $html[0] .= "\n    <th class=\"right\">Sp.</th>";
    $html[0] .= "\n    <th class=\"right\">S</th>";
    $html[0] .= "\n    <th class=\"right\">U</th>";
    $html[0] .= "\n    <th class=\"right\">N</th>";
    $html[0] .= "\n    <th style=\"text-align:center;\">SDS</th>";
    $html[0] .= "\n    <th class=\"np\" style=\"text-align:center;\">SDN</th>";
    $html[0] .= "\n    <th colspan=\"3\" style=\"text-align:center;\">Tore</th>";
    $html[0] .= "\n    <th class=\"right\">Tordiff.</th>";
    $html[0] .= "\n    <th class=\"right\">Pkt.</th>\n  </tr>";
    $j = 1;
    $ordnungsnr = -10;
    for($i=1; $i<1+count($file); $i++) {
      $arr_row = explode(";", $file[$i-1]);

      // was war das???
      if ($table == 2 && $day > 11 &&
            ( $arr_row[0] == "TB Uphusen Vikings" || $arr_row[0] == "SG Seebergen-Vahrenwald" )) {
        $html[$i] = "";
        $arr_row = array();
      }
      if(count($arr_row) > 2) {
        $da = "";
        if(count($arr_row) == 12) {
          if($ordnungsnr != -10 && $ordnungsnr != $arr_row[11]) {
            // we need a separating line
            $da = "<tr><td colspan=\"13\" style=\"border-width:1px 0px 0px 0px; padding:0px 0px 0px 0px;\"></td></tr>";
          }
          $ordnungsnr = $arr_row[11];
        }
      	if($j%2)
      	  $html[$i] = $da."\n  <tr class=\"colored\">";
      	else
      	  $html[$i] = $da."\n  <tr>";
      	$html[$i] .= "\n    <td class=\"right\">".$j."</td>";  // Platz
      	$html[$i] .= "\n    <td class=\"left\">".$arr_row[0]."</td>"; // Name
      	$html[$i] .= "\n    <td class=\"right\">".$arr_row[1]."</td>"; // Spiele 
      	$html[$i] .= "\n    <td class=\"right\">".($arr_row[2]-$arr_row[5])."</td>"; // Siege
      	$html[$i] .= "\n    <td class=\"right\">".$arr_row[3]."</td>"; // unentschieden
      	$html[$i] .= "\n    <td class=\"right\">".($arr_row[4]-$arr_row[6])."</td>"; // Niederlagen
      	$html[$i] .= "\n    <td >".$arr_row[5]."</td>"; // SDS
      	$html[$i] .= "\n    <td>".$arr_row[6]."</td>"; // SDN
      	$html[$i] .= "\n    <td class=\"np, right\">".$arr_row[7]."</td>"; // Tore
      	$html[$i] .= "\n    <td class=\"np\"> : </td>";
      	$html[$i] .= "\n    <td class=\"np, left\">".$arr_row[8]."</td>";
      	$html[$i] .= "\n    <td class=\"right\">".$arr_row[9]."</td>";
      	$html[$i] .= "\n    <td class=\"right\" style=\"color:#2222aa;\">".trim($arr_row[10])."</td>";//333399
      	$html[$i] .= "\n  </tr>";
        $j++;
      }
    }
    $html[] = "\n</table>\n\n";
    for($i=0; $i<count($html); $i++) echo $html[$i];
  }
  else if(!$dm && !$pokal) {
    $html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Aktuelle Tabelle - ".$liga_name.":</b><br/><br/>";
    $html[0] .="\n<table class=\"statistik\">";
    $html[0] .= "\n  <tr>\n    <th class=\"right\">Pl.</th>";
    $html[0] .= "\n    <th class=\"left\">Mannschaft</th>";
    $html[0] .= "\n    <th class=\"right\">Sp.</th>";
    $html[0] .= "\n    <th class=\"right\">S</th>";
    $html[0] .= "\n    <th class=\"right\">U</th>";
    $html[0] .= "\n    <th class=\"right\">N</th>";
    $html[0] .= "\n    <th style=\"text-align:center;\">SDS</th>";
    $html[0] .= "\n    <th class=\"np\" style=\"text-align:center;\">SDN</th>";
    $html[0] .= "\n    <th colspan=\"3\" style=\"text-align:center;\">Tore</th>";
    $html[0] .= "\n    <th class=\"right\">Tordiff.</th>";
    $html[0] .= "\n    <th class=\"right\">Pkt.</th>\n  </tr>";
    $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($file_name, 1);
    $j = 1;
    for($i=1; $i<1+count($arr_teams[0]); $i++) {
      if($j%2)
        $html[$i] = "\n  <tr class=\"colored\">";
      else
        $html[$i] = "\n  <tr>";
      $html[$i] .= "\n    <td class=\"right\">".$j."</td>";
      $html[$i] .= "\n    <td class=\"left\">".$arr_teams[1][$i-1]."</td>";
      $html[$i] .= "\n    <td class=\"right\">0</td>";
      $html[$i] .= "\n    <td class=\"right\">0</td>";
      $html[$i] .= "\n    <td class=\"right\">0</td>";
      $html[$i] .= "\n    <td class=\"right\">0</td>";
      $html[$i] .= "\n    <td >0</td>";
      $html[$i] .= "\n    <td>0</td>";
      $html[$i] .= "\n    <td class=\"np, right\">0</td>";
      $html[$i] .= "\n    <td class=\"np\"> : </td>";
      $html[$i] .= "\n    <td class=\"np, left\">0</td>";
      $html[$i] .= "\n    <td class=\"right\">0</td>";
      $html[$i] .= "\n    <td class=\"right\" style=\"color:#2222aa;\">0</td>";//333399
      $html[$i] .= "\n  </tr>";
      $j++;
    }
    $html[] = "\n</table>\n\n";
    for($i=0; $i<count($html); $i++) echo $html[$i];
  }

  // hier jetzt das ganze von oben nochmal um die zwei dm tabellen auszugeben
  if($dm) {
    if(file_exists("tables/dm_g1_".$file_name.".tab")) {
      $file = file("tables/dm_g1_".$file_name.".tab");
      
      $html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">".$liga_name." Gruppe A:</b><br/><br/>";
      $html[0] .="\n<table class=\"statistik\">";
      $html[0] .= "\n  <tr>\n    <th class=\"right\">Pl.</th>";
      $html[0] .= "\n    <th class=\"left\">Mannschaft</th>";
      $html[0] .= "\n    <th class=\"right\">Sp.</th>";
      $html[0] .= "\n    <th class=\"right\">S</th>";
      $html[0] .= "\n    <th class=\"right\">U</th>";
      $html[0] .= "\n    <th class=\"right\">N</th>";
      $html[0] .= "\n    <th style=\"text-align:center;\">SDS</th>";
      $html[0] .= "\n    <th class=\"np\" style=\"text-align:center;\">SDN</th>";
      $html[0] .= "\n    <th colspan=\"3\" style=\"text-align:center;\">Tore</th>";
      $html[0] .= "\n    <th class=\"right\">Tordiff.</th>";
      $html[0] .= "\n    <th class=\"right\">Pkt.</th>\n  </tr>";
      $j = 1;
      $ordnungsnr = -10;
      for($i=1; $i<1+count($file); $i++) {
        $arr_row = explode(";", $file[$i-1]);

        if(count($arr_row) > 2) {
          $da = "";
          if($j%2)
            $html[$i] = $da."\n  <tr class=\"colored\">";
          else
            $html[$i] = $da."\n  <tr>";
          $html[$i] .= "\n    <td class=\"right\">".$j."</td>";
          $html[$i] .= "\n    <td class=\"left\">".$arr_row[0]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[1]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[2]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[3]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[4]."</td>";
          $html[$i] .= "\n    <td >".$arr_row[5]."</td>";
          $html[$i] .= "\n    <td>".$arr_row[6]."</td>";
          $html[$i] .= "\n    <td class=\"np, right\">".$arr_row[7]."</td>";
          $html[$i] .= "\n    <td class=\"np\"> : </td>";
          $html[$i] .= "\n    <td class=\"np, left\">".$arr_row[8]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[9]."</td>";
          $html[$i] .= "\n    <td class=\"right\" style=\"color:#2222aa;\">".trim($arr_row[10])."</td>";//333399
          $html[$i] .= "\n  </tr>";
          $j++;
        }
      }
      $html[] = "\n</table>\n\n";
      for($i=0; $i<count($html); $i++) echo $html[$i];
    }

    if(file_exists("tables/dm_g2_".$file_name.".tab")) {
      $file = file("tables/dm_g2_".$file_name.".tab");
      
      $html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">".$liga_name." Gruppe B:</b><br/><br/>";
      $html[0] .="\n<table class=\"statistik\">";
      $html[0] .= "\n  <tr>\n    <th class=\"right\">Pl.</th>";
      $html[0] .= "\n    <th class=\"left\">Mannschaft</th>";
      $html[0] .= "\n    <th class=\"right\">Sp.</th>";
      $html[0] .= "\n    <th class=\"right\">S</th>";
      $html[0] .= "\n    <th class=\"right\">U</th>";
      $html[0] .= "\n    <th class=\"right\">N</th>";
      $html[0] .= "\n    <th style=\"text-align:center;\">SDS</th>";
      $html[0] .= "\n    <th class=\"np\" style=\"text-align:center;\">SDN</th>";
      $html[0] .= "\n    <th colspan=\"3\" style=\"text-align:center;\">Tore</th>";
      $html[0] .= "\n    <th class=\"right\">Tordiff.</th>";
      $html[0] .= "\n    <th class=\"right\">Pkt.</th>\n  </tr>";
      $j = 1;
      $ordnungsnr = -10;
      for($i=1; $i<1+count($file); $i++) {
        $arr_row = explode(";", $file[$i-1]);

        if(count($arr_row) > 2) {
          $da = "";
          if($j%2)
            $html[$i] = $da."\n  <tr class=\"colored\">";
          else
            $html[$i] = $da."\n  <tr>";
          $html[$i] .= "\n    <td class=\"right\">".$j."</td>";
          $html[$i] .= "\n    <td class=\"left\">".$arr_row[0]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[1]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[2]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[3]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[4]."</td>";
          $html[$i] .= "\n    <td >".$arr_row[5]."</td>";
          $html[$i] .= "\n    <td>".$arr_row[6]."</td>";
          $html[$i] .= "\n    <td class=\"np, right\">".$arr_row[7]."</td>";
          $html[$i] .= "\n    <td class=\"np\"> : </td>";
          $html[$i] .= "\n    <td class=\"np, left\">".$arr_row[8]."</td>";
          $html[$i] .= "\n    <td class=\"right\">".$arr_row[9]."</td>";
          $html[$i] .= "\n    <td class=\"right\" style=\"color:#2222aa;\">".trim($arr_row[10])."</td>";//333399
          $html[$i] .= "\n  </tr>";
          $j++;
        }
      }
      $html[] = "\n</table>\n\n";
      for($i=0; $i<count($html); $i++) echo $html[$i];
    }
  }


  unset($html);
  if(isset($iframe)) $site = "iframe";
  else $site = "index";
  // ergebnisse
  //for ($j=1; $j<1+$count_matchdays; $j++) {
  for ($j=$count_matchdays; $j>=1; $j--) {
    unset($html);
    if(file_exists("tables/".$file_name."_".$j."_matches.tab")) {
      $file = file("tables/".$file_name."_".$j."_matches.tab");
      $html[0] = "<br/><br/><br/><b style=\"font-size:1.2em; color:#171717;\">Spielbegegnungen des ".$j.". Spieltages - ".$liga_name.":</b><br/><br/>";
      $html[0] .= "\n<table class=\"statistik\">";
      $html[0] .= "\n  <tr>\n    <th>Heim</th>";
      $html[0] .= "\n    <th>Gast</th>";
      $html[0] .= "\n    <th colspan=\"2\">Ergebnis</th>\n  </tr>";
      for($i=1; $i<1+count($file); $i++) {
        $arr_row = explode(";", $file[$i-1]);
        if(count($arr_row) > 2) {
          $Begegnung = 0;
          if(count($arr_row) == 13) {
            $link = "link\" style=\"cursor:pointer;\" onclick=\"location.href='".$site.".php?seite=game&amp;game=".trim($arr_row[12])."'\"";
            if($dm) {
              $_SESSION['arr_login']['user_name'] = "Gast";
              $Begegnung = DBMapper::read_Begegnung(trim($arr_row[12]));
            }
          }
          else {
            $link = "noclass\"";
          }
          if($Begegnung) {
            $spielnummer = $Begegnung->get_int_spielnummer();
            if($spielnummer > 12) {
              $html[$i-1] .= "\n  <tr><td colspan=\"4\"> </td></tr>";
              if($spielnummer == 13) $text = "1. Halbfinale";
              else if($spielnummer == 14) $text = "2. Halbfinale";
              else if($spielnummer == 15) $text = "Spiel um Platz 5";
              else if($spielnummer == 16) $text = "Spiel um Platz 7";
              else if($spielnummer == 17) $text = "Spiel um Platz 3";
              else if($spielnummer == 18) $text = "Finale";
              $html[$i-1] .= "\n  <tr><td colspan=\"4\" style=\"text-align:center; font-weight:bold;\">".$text."</td></tr>";
            }
          }
          if($i%2)
            $html[$i] = "\n  <tr class=\"colored, $link>";
          else
            $html[$i] = "\n  <tr class=\"$link>";
          $html[$i] .= "\n    <td style=\"text-align: left;\">".$arr_row[0]."</td>";
          $html[$i] .= "\n    <td style=\"text-align: left;\">".$arr_row[1]."</td>";
          $html[$i] .= "\n    <td>".$arr_row[2].":".$arr_row[3]."</td>";
          $html[$i] .= "\n    <td>(".$arr_row[4].":".$arr_row[5].";";
          $html[$i] .= $arr_row[6].":".$arr_row[7].";";

          $html[$i] .= $arr_row[8].":".$arr_row[9];
          if($int_num_reg == 3)
            $html[$i] .= ";".$arr_row[10].":".$arr_row[11];
          $html[$i] .= ")</td>";
          $html[$i] .= "\n  </tr>";
        }
      }
      $html[] = "\n</table>\n\n<br/><br/>\n";
      for ($i=0; $i<count($html); $i++) {
        echo $html[$i];
      }
    }
  }  //echo "<div id=\"w1\" class=\"werbung\">werbung1</div>";
  //echo "<div id=\"w2\" class=\"werbung\">werbung2</div>";
  //echo "<div id=\"w3\" class=\"werbung\">werbung3</div>";
  echo "</div>";

}
?>