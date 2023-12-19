<?php
if(isset($_GET['game'])) {
  $_SESSION['arr_login']['user_name'] = "Gast";
  $int_id_match = $_GET['game'];
  $Begegnung = DBMapper::read_Begegnung($int_id_match);
  if($Begegnung) {

    $Spieltag = DBMapper::read_Spieltag($Begegnung->get_int_id_spieltag());
    if ( $Spieltag )
    {
	$Spielort = DBMapper::read_Spielort($Spieltag->get_int_id_spielort());
	$Liga = DBM_Ligen::read_Liga($Spieltag->get_int_id_liga());

        $int_id_kategorie = $Liga->get_int_id_kategorie();
        if($int_id_kategorie == 1 || $int_id_kategorie == 4)
           $int_num_reg = 3;
        else 
	   $int_num_reg = 2;
    }
    $Spielbericht = DBMapper::read_Spielbericht($int_id_match);
    if($Spielbericht) {

      $html_out[] = "<div id=\"table\" style=\"font-size:10px;\">";
      //$html_out[] = "<div class=\"game_report\">";
      //$Mannschaft1 = DBMapper::read_Mannschaft($Begegnung->get_int_id_mannschaft1());
      //$Mannschaft2 = DBMapper::read_Mannschaft($Begegnung->get_int_id_mannschaft2());

      $arr_strafen = DBMapper::get_list_strafe();
      $arr_codes = DBMapper::get_list_strafcode(true);

      $arr_Mitspieler1 = $Spielbericht->get_arr_Mitspieler_team1();
      $arr_Mitspieler2 = $Spielbericht->get_arr_Mitspieler_team2();
      $Betreuer1 = $Spielbericht->get_Betreuer_team1();
      $Betreuer2 = $Spielbericht->get_Betreuer_team2();

      $html_out[] = "<b>Spielinfo: ".$Begegnung->get_str_mannschaft1_name()." - ".$Begegnung->get_str_mannschaft2_name()."</b><br/><br/>";
      $html_out[] = "<table>";
      $html_out[] = "\n  <tr>\n    <td>Liga:</td>";
      $html_out[] = "\n    <td>".$Liga->get_str_name()."</td>\n  </tr>";
      $html_out[] = "\n  <tr>\n    <td>Spielnummer:</td>";
      $html_out[] = "\n    <td>".$Begegnung->get_int_spielnummer()."</td>\n  </tr>";
      $html_out[] = "\n  <tr><td> </td><td><br/></td></tr>";
      $html_out[] = "\n  <tr>\n    <td>Datum:</td>";
      $html_out[] = "\n    <td>".$Spieltag->get_date_datum()."</td>\n  </tr>";
      $html_out[] = "\n  <tr>\n    <td style=\"padding-right:10px;\">Austragungsort:";
      if($Spielort)
        $html_out[] = "\n    <td>".$Spielort->get_str_name()."</td>\n  </tr>";
      else $html_out[] = "\n    <td>Kein Spielort angegeben</td>\n  </tr>";
      $html_out[] = "\n  <tr>\n    <td>Spielbeginn:</td>";
      $html_out[] = "\n    <td>".$Begegnung->get_str_uhrzeit()." Uhr</td>\n  </tr>";
      $html_out[] = "\n  <tr><td> </td><td><br/></td></tr>";
      //$html_out[] = "\n  <tr>\n    <td style=\"padding-right:10px;\">Ergebnis (Drittelergebnisse):</td>";
      //$html_out[] = "\n    <td>"."kommt noch"."</td>\n  </tr>";
      $html_out[] = "\n  <tr>\n    <td>Schiedsrichter:</td>";
      if($Spielbericht) {
        $schiris[0] = $Spielbericht->get_str_schiri1_name()." / ";
        $schiris[0] .= $Spielbericht->get_str_schiri2_name();
      }
      else $schiris[0] = "";
      // $schiris = explode("#", $Begegnung->get_str_schiedsrichter());
      $html_out[] = "\n    <td>".$schiris[0]."</td>\n  </tr>";
      $html_out[] = "\n  <tr><td> </td><td><br/></td></tr>";
      $html_out[] = "</table>\n<br/><br/>";
      if($Spielbericht) {


        $e[0] = 0;
        $e[1] = 0;
        $e[2] = 0;
        $e[3] = 0;
        $e[4] = 0;
        $line[1] = array();
        $line[2] = array();
        $line[3] = array();
        $line[4] = array();

        $html_out[] = "<b style=\"color:#116622;\">".$Begegnung->get_str_mannschaft1_name().":</b><br/><div style=\"width:600px; color:#116622;\">";
        for($i=0; $i<count($arr_Mitspieler1) && $i<20; $i++) {
          if($i>0) $html_out[] = ", ";
          if($arr_Mitspieler1[$i]->get_bool_torwart()) $html_out[] = "[T]&nbsp;";
          if($arr_Mitspieler1[$i]->get_bool_kapitain()) $html_out[] = "[C]&nbsp;";
          $html_out[] = $arr_Mitspieler1[$i]->get_str_vorname()."&nbsp;";
          $html_out[] = $arr_Mitspieler1[$i]->get_str_name();
          //$html_out[] = " (".$arr_Mitspieler1[$i]->get_int_trikotnr().")";
        }

        $html_out[] = "</div><br/><br/><b style=\"color:#112266;\">".$Begegnung->get_str_mannschaft2_name()."</b>:<br/><div style=\"width:600px; color:#112266;\">";
        for($i=0; $i<count($arr_Mitspieler2) && $i<20; $i++) {
          if($i>0) $html_out[] = ", ";
          if($arr_Mitspieler2[$i]->get_bool_torwart()) $html_out[] = "[T]&nbsp;";
          if($arr_Mitspieler2[$i]->get_bool_kapitain()) $html_out[] = "[C]&nbsp;";
          $html_out[] = $arr_Mitspieler2[$i]->get_str_vorname()."&nbsp;";
          $html_out[] = $arr_Mitspieler2[$i]->get_str_name();
          //$html_out[] = " (".$arr_Mitspieler2[$i]->get_int_trikotnr().")";
        }
        $html_out[] = "</div>";
        if($Betreuer1) {
          $bh1 = $Betreuer1->get_str_betreuer1();
          $bh2 = $Betreuer1->get_str_betreuer2();
          $bh3 = $Betreuer1->get_str_betreuer3();
          $bh4 = $Betreuer1->get_str_betreuer4();
          $bh5 = $Betreuer1->get_str_betreuer5();
        }

        $arr_events = $Spielbericht->get_arr_Ereignis();
        $arr_events = sort_events($arr_events);
        $scores['team1'] = 0;
        $scores['team2'] = 0;
        $scores['t1'][1] = 0;
        $scores['t2'][1] = 0;
        $scores['t1'][2] = 0;
        $scores['t2'][2] = 0;
        $scores['t1'][3] = 0;
        $scores['t2'][3] = 0;
        $scores['t1'][4] = 0;
        $scores['t2'][4] = 0;
        $bool_extra_time = false;
        $html_out[] = "<br/>";
        for($h=0; $h<count($arr_events); $h++) {
          // check if we reached the extra time
          // wichtig zwischen klein und großfeld unterscheiden
          if($arr_events[$h]->get_int_periode() > $int_num_reg) {
            $bool_extra_time = true;
          }
          $periode = $arr_events[$h]->get_int_periode();
          if($arr_events[$h]->get_int_id_strafe() != "-") {
            $line[$periode][$e[$periode]][0] = $arr_events[$h]->get_str_zeit();
            $line[$periode][$e[$periode]][1] = "Strafe";
            if($arr_events[$h]->get_int_id_strafe() == 1)
              $line[$periode][$e[$periode]][2] = "2'";
            else if($arr_events[$h]->get_int_id_strafe() == 2)
              $line[$periode][$e[$periode]][2] = "5'";
            else if($arr_events[$h]->get_int_id_strafe() == 3)
              $line[$periode][$e[$periode]][2] = "10'";
            else if($arr_events[$h]->get_int_id_strafe() >= 4)
              $line[$periode][$e[$periode]][2] = "Rote Karte";

            if($arr_events[$h]->get_int_nr_team1() != "-") {
              $line[$periode][$e[$periode]][3] = get_player_name($arr_Mitspieler1, $arr_events[$h]->get_int_nr_team1());
              $line[$periode][$e[$periode]][4] = $Begegnung->get_str_mannschaft1_name();
              $line[$periode][$e[$periode]]['color'] = "style=\"color:#116622;\"";
            }
            else if($arr_events[$h]->get_int_nr_team2() != "-") {
              $line[$periode][$e[$periode]][3] = get_player_name($arr_Mitspieler2, $arr_events[$h]->get_int_nr_team2());
              $line[$periode][$e[$periode]][4] = $Begegnung->get_str_mannschaft2_name();
              $line[$periode][$e[$periode]]['color'] = "style=\"color:#112266;\"";
            }
            else {
              $line[$periode][$e[$periode]][3] = " ";
              $line[$periode][$e[$periode]][4] = " ";
              $line[$periode][$e[$periode]]['color'] = "";
            }
            $e[$periode]++;
          }
          else {
            if($arr_events[$h]->get_int_tore_team1() == $scores['team1']+1) {
              // team1 scores; count the score, the scorer and the assist
              $scores['team1'] += 1;
              $scores['t1'][$arr_events[$h]->get_int_periode()]++;
              $periode = $arr_events[$h]->get_int_periode();
              $line[$periode][$e[$periode]][0] = $arr_events[$h]->get_str_zeit();
              $line[$periode][$e[$periode]][1] = "Tor";
              $line[$periode][$e[$periode]][2] = $scores['team1'].":".$scores['team2'];
              $line[$periode][$e[$periode]][4] = $Begegnung->get_str_mannschaft1_name();
              $line[$periode][$e[$periode]]['color'] = "style=\"color:#116622;\"";
              if($arr_events[$h]->get_int_nr_team1() != "-" &&
                 $arr_events[$h]->get_int_nr_team1() != 1000) {
                $line[$periode][$e[$periode]][3] = get_player_name($arr_Mitspieler1, $arr_events[$h]->get_int_nr_team1());
                if($arr_events[$h]->get_int_ass_team1() != "-" &&
                   $arr_events[$h]->get_int_ass_team1() != 1000) {
                  $line[$periode][$e[$periode]][3] .= " (".get_player_name($arr_Mitspieler1, $arr_events[$h]->get_int_ass_team1()).")";
                }
              }
              else $line[$periode][$e[$periode]][3] = " Eigentor";
              $e[$periode]++;
            }
            else if($arr_events[$h]->get_int_tore_team2() == $scores['team2']+1) {
              // team1 scores; count the score, the scorer and the assist
              $scores['team2'] += 1;
              $scores['t2'][$arr_events[$h]->get_int_periode()]++;
              $line[$periode][$e[$periode]][0] = $arr_events[$h]->get_str_zeit();
              $line[$periode][$e[$periode]][1] = "Tor";
              $line[$periode][$e[$periode]][2] = $scores['team1'].":".$scores['team2'];
              $line[$periode][$e[$periode]][4] = $Begegnung->get_str_mannschaft2_name();
              $line[$periode][$e[$periode]]['color'] = "style=\"color:#112266;\"";
              if($arr_events[$h]->get_int_nr_team2() != "-" &&
                 $arr_events[$h]->get_int_nr_team2() != 1000) {
                $line[$periode][$e[$periode]][3] = get_player_name($arr_Mitspieler2, $arr_events[$h]->get_int_nr_team2());
              if($arr_events[$h]->get_int_ass_team2() != "-" &&
                 $arr_events[$h]->get_int_ass_team2() != 1000) {
                $line[$periode][$e[$periode]][3] .=  " (".get_player_name($arr_Mitspieler2, $arr_events[$h]->get_int_ass_team2()).")";
              }
              }
              else $line[$periode][$e[$periode]][3] = " Eigentor";
              $e[$periode]++;
            }
          }
        }
        // write the lines

        $html_out[] = "\n<table style=\"width:600px;\">";
        if($int_num_reg == 2)
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>1. Hälfte:</b></tr>";
        else
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>1. Drittel:</b></tr>";
        if($e[1]==0) $html_out[] = "<tr><td colspan=\"5\">Keine Ereignisse</tr>";
        else {
          for($u=0; $u<$e[1]; $u++) {
            $html_out[] = "\n  <tr ".$line[1][$u]['color'].">\n    <td style=\"padding-right:15px;\">".$line[1][$u][0]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[1][$u][1]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[1][$u][2]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[1][$u][3]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[1][$u][4]."</td>\n  </tr>";
          }
          $html_out[] = "\n";
        }
        if($int_num_reg == 2)
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>2. Hälfte:</b></tr>";
        else
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>2. Drittel:</b></tr>";
        if($e[2]==0) $html_out[] = "<tr><td colspan=\"5\">Keine Ereignisse</tr>";
        else {
          //$html_out[] = "\n<table style=\"width:500px;\">";
          for($u=0; $u<$e[2]; $u++) {
            $html_out[] = "\n  <tr ".$line[2][$u]['color'].">\n    <td style=\"padding-right:15px;\">".$line[2][$u][0]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[2][$u][1]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[2][$u][2]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[2][$u][3]."</td>";
            $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[2][$u][4]."</td>\n  </tr>";
          }
          $html_out[] = "\n";
        }
        if($int_num_reg == 3) {
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>3. Drittel:</b></tr>";
          if($e[3]==0) $html_out[] = "<tr><td colspan=\"5\">Keine Ereignisse</tr>";
        }
        else if($e[3]>0)
          $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>Verlängerung:</b></tr>";
        //$html_out[] = "\n<table style=\"width:500px;\">";
        for($u=0; $u<$e[3]; $u++) {
          $html_out[] = "\n  <tr ".$line[3][$u]['color'].">\n    <td style=\"padding-right:15px;\">".$line[3][$u][0]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[3][$u][1]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[3][$u][2]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[3][$u][3]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[3][$u][4]."</td>\n  </tr>";
        }
        //$html_out[] = "\n</table>";
        if($e[4]>0) $html_out[] = "\n<tr><td colspan=\"5\"><br/><b>Verlängerung:</b></tr>";
        //$html_out[] = "\n<table style=\"width:500px;\">";
        for($u=0; $u<$e[4]; $u++) {
          $html_out[] = "\n  <tr ".$line[4][$u]['color'].">\n    <td style=\"padding-right:15px;\">".$line[4][$u][0]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[4][$u][1]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[4][$u][2]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[4][$u][3]."</td>";
          $html_out[] = "\n    <td style=\"padding-right:15px;\">".$line[4][$u][4]."</td>\n  </tr>";
        }
        $html_out[] = "\n</table>";
        $html_out[] = "</div>";
      }
      else {
        $html_out[] = "<br/> Dieses Spiel wurde forfait gewertet.";//Kein Spielbericht verfügbar, forfeit hier behandeln";
      }
    }
    else 
    {
    	$html_out[] = "Fehler - kein Spielbericht gefunden.";
    }
    for($i=0; $i<count($html_out); $i++) echo $html_out[$i];
  }
}


function sort_events($arr_events) {
  $arr_return = array();
  while(count($arr_events)) {
    $min = 61;
    $event = 61;
    foreach($arr_events as $key=>$value) {
      if($value->get_int_zeile() < $min) {
        $min = $value->get_int_zeile();
        $event = $key;
      }
    }
    $arr_return[] = $arr_events[$event];
    unset($arr_events[$event]);
  }
  return $arr_return;
}

function get_player_name($arrMitspieler, $trikotnr) {
  for($i=0; $i<count($arrMitspieler); $i++) {
    if($arrMitspieler[$i]->get_int_trikotnr() == $trikotnr) {
      $ret = $arrMitspieler[$i]->get_str_vorname();
      $ret .= " ".$arrMitspieler[$i]->get_str_name();
      return $ret;
    }
  }
  return "";
}