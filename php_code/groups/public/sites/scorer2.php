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
	echo "<div id=\"table\" style=\"font-size:10px;\">";
	echo "<div id=\"legend\">";
	echo "S = Spiele <br />";
	echo "T = Tore <br />";
	echo "V = Vorlagen <br />";
	echo "P = Punkte <br />";
	echo "</div>";

	echo "<div id=\"legend2\">";
	echo "2' = 2 Minuten <br />";
	echo "5' = 5 Minuten <br />";
	echo "10' = 10 Minuten <br />";
	echo "MS = Matchstrafe <br />";
	echo "</div>";
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

	$playoffs = 0;
	if(file_exists("tables/".$file_name."_scorer_pl.tab")) {
		$file = file("tables/".$file_name."_scorer_pl.tab");
		if(count($file) > 0) {
			$arr_row = explode(";", $file[0]);
			if(count($arr_row) >= 11) {
				$playoffs = 1;
				$html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":<br/>(Playoffs)</b><br/>";
				$html[0] .="<table class=\"statistik\">";
				$html[0] .= "<tr><th class=\"right\">Pl.</th>";
				$html[0] .= "<th class=\"left\">Spieler</th>";
				$html[0] .= "<th class=\"left\">Mannschaft</th>";
				$html[0] .= "<th class=\"right\">S</th>";
				$html[0] .= "<th class=\"right\">T</th>";
				$html[0] .= "<th class=\"right\">V</th>";
				$html[0] .= "<th class=\"\">P</th>";
				$html[0] .= "<th class=\" line_l\">2'</th>";
				$html[0] .= "<th class=\"\">5'</th>";
				$html[0] .= "<th class=\"\">10'</th>";
				$html[0] .= "<th class=\"\">MS</th></tr>";
				$j = 1;
				$lines = 0;
				for($i=1; $i<1+count($file); $i++) {
					$arr_row = explode(";", $file[$i-1]);
					if(count($arr_row) > 2 && ($arr_row[3] > 0 ||
					$arr_row[6] > 0 ||
					$arr_row[7] > 0 ||
					$arr_row[8] > 0 ||
					$arr_row[9] > 0)) {
						if($j%2)
						$html[$j] = "<tr class=\"colored\">";
						else
						$html[$j] = "<tr>";
						$lines++;
						if(trim($arr_row[0]) != "")
						$html[$j] .= "<td class=\"right\">".$lines."</td>";
						else
						$html[$j] .= "<td class=\"right\"></td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[1]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[2]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[10]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[4]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[5]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[3]."</td>";
						$html[$j] .= "<td class=\" line_l\">".$arr_row[6]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[7]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[8]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[9]."</td>";
						$html[$j] .= "</tr>\n";
						$j++;
					}
				}
				$html[] = "</table>\n";
				for($i=0; $i<count($html); $i++) echo $html[$i];
			}
			else {
				$html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":</b><br/><br/>";
				$html[0] .="<table class=\"statistik\">";
				$html[0] .= "<tr><th class=\"right\">Pl.</th>";
				$html[0] .= "<th class=\"left\">Spieler</th>";
				$html[0] .= "<th class=\"left\">Mannschaft</th>";
				$html[0] .= "<th class=\"right\">T</th>";
				$html[0] .= "<th class=\"right\">V</th>";
				$html[0] .= "<th class=\"right\">P</th></tr>";
				$j = 1;
				for($i=1; $i<1+count($file); $i++) {
					$arr_row = explode(";", $file[$i-1]);
					if(count($arr_row) > 2 && ($arr_row[3] > 0 ||
					$arr_row[6] > 0 ||
					$arr_row[7] > 0 ||
					$arr_row[8] > 0 ||
					$arr_row[9] > 0)) {
						if($j%2)
						$html[$j] = "<tr class=\"colored\">";
						else
						$html[$j] = "<tr>";
						$html[$j] .= "<td class=\"right\">".$arr_row[0]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[1]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[2]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[4]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[5]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[3]."</td>";
						$html[$j] .= "</tr>\n";
						$j++;
					}
				}
				$html[] = "</table>\n";
				for($i=0; $i<count($html); $i++) echo $html[$i];
			}
		}
	}


	if(file_exists("tables/".$file_name."_scorer.tab")) {
		$file = file("tables/".$file_name."_scorer.tab");
		if ( isset($file[0]))
		{
			$arr_row = explode(";", $file[0]);
			if(count($arr_row) >= 11) {
				if($playoffs)
				$html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":<br/>(Liga)</b><br/>";
				else
				$html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":</b><br/><br/>";
				$html[0] .="<table class=\"statistik\">";
				$html[0] .= "<tr><th class=\"right\">Pl.</th>";
				$html[0] .= "<th class=\"left\">Spieler</th>";
				$html[0] .= "<th class=\"left\">Mannschaft</th>";
				$html[0] .= "<th class=\"right\">S</th>";
				$html[0] .= "<th class=\"right\">T</th>";
				$html[0] .= "<th class=\"right\">V</th>";
				$html[0] .= "<th class=\"\">P</th>";
				$html[0] .= "<th class=\" line_l\">2'</th>";
				$html[0] .= "<th class=\"\">5'</th>";
				$html[0] .= "<th class=\"\">10'</th>";
				$html[0] .= "<th class=\"\">MS</th></tr>";
				$j = 1;
				$lines = 0;
				for($i=1; $i<1+count($file); $i++) {
					$arr_row = explode(";", $file[$i-1]);
					if(count($arr_row) > 2 && ($arr_row[3] > 0 ||
					$arr_row[6] > 0 ||
					$arr_row[7] > 0 ||
					$arr_row[8] > 0 ||
					$arr_row[9] > 0)) {
						if($j%2)
						$html[$j] = "<tr class=\"colored\">";
						else
						$html[$j] = "<tr>";
						$lines++;
						if(trim($arr_row[0]) != "")
						$html[$j] .= "<td class=\"right\">".$lines."</td>";
						else
						$html[$j] .= "<td class=\"right\"></td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[1]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[2]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[10]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[4]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[5]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[3]."</td>";
						$html[$j] .= "<td class=\" line_l\">".$arr_row[6]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[7]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[8]."</td>";
						$html[$j] .= "<td class=\"\">".$arr_row[9]."</td>";
						$html[$j] .= "</tr>\n";
						$j++;
					}
				}
				$html[] = "</table>\n";
				for($i=0; $i<count($html); $i++) echo $html[$i];
			}
			else {
				$html[0] = "<br/><br/><b style=\"font-size:1.2em; color:#171717;\">Scorer Tabelle - ".$liga_name.":</b><br/><br/>";
				$html[0] .="<table class=\"statistik\">";
				$html[0] .= "<tr><th class=\"right\">Pl.</th>";
				$html[0] .= "<th class=\"left\">Spieler</th>";
				$html[0] .= "<th class=\"left\">Mannschaft</th>";
				$html[0] .= "<th class=\"right\">T</th>";
				$html[0] .= "<th class=\"right\">V</th>";
				$html[0] .= "<th class=\"right\">P</th></tr>";
				$j = 1;
				for($i=1; $i<1+count($file); $i++) {
					$arr_row = explode(";", $file[$i-1]);
					if(count($arr_row) > 2 && ($arr_row[3] > 0 ||
					$arr_row[6] > 0 ||
					$arr_row[7] > 0 ||
					$arr_row[8] > 0 ||
					$arr_row[9] > 0)) {
						if($j%2)
						$html[$j] = "<tr class=\"colored\">";
						else
						$html[$j] = "<tr>";
						$html[$j] .= "<td class=\"right\">".$arr_row[0]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[1]."</td>";
						$html[$j] .= "<td class=\"left\">".$arr_row[2]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[4]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[5]."</td>";
						$html[$j] .= "<td class=\"right\">".$arr_row[3]."</td>";
						$html[$j] .= "</tr>\n";
						$j++;
					}
				}
				$html[] = "</table>\n";
				for($i=0; $i<count($html); $i++) echo $html[$i];
			}
		}
	}
	unset($html);
	/*
	 if(file_exists("tables/".$file_name."_".$day."_matches.tab")) {
	 $file = file("tables/".$file_name."_".$day."_matches.tab");
	 $html[0] = "<br/><br/><br/><b style=\"font-size:1.2em; color:#171717;\">Spielbegegnungen des ".$day.". Spieltages - ".$liga_name.":</b><br/><br/>";
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
	 $html[] = "</table><br/><br/>\n";
	 for($i=0; $i<count($html); $i++) echo $html[$i];
	 }*/
	//echo "<div id=\"w1\" class=\"werbung\">werbung1</div>";
	//echo "<div id=\"w2\" class=\"werbung\">werbung2</div>";
	//echo "<div id=\"w3\" class=\"werbung\">werbung3</div>";
	echo "</div>";
}
?>
