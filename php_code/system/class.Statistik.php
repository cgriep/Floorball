<?php
/**
 * Statistik
 *
 * Die Klasse enthält Methoden zum Erstellen der Statistiken.
 *
 * @package SYSTEM
 *
 * @author Malte Römmermann
 * @copyright 2009 MR2
 * @since 11.08.2007
 * @version 0.1
 * @access public
 */
class Statistik {

  public function __construct() {
  }

  static function create_all_tables() {
    $arr_leagues = DBM_Ligen::get_list_liga();
    for($i=0; $i<count($arr_leagues[0]); $i++)
      self::create_tables($arr_leagues[0][$i]);
  }

  static function create_tables($int_id_liga) {
    // initialization
    // get league info
    $Liga = DBM_Ligen::read_Liga($int_id_liga);
    // check if we have 3 or 2 regular periods
    if($Liga->get_int_id_spielsystem() == 1) $int_win_punkte = 3;
    else $int_win_punkte = 2;
    $int_id_kategorie = $Liga->get_int_id_kategorie();
    if($int_id_kategorie == 1 || $int_id_kategorie == 4) {
      $int_num_reg = 3;
      $forfait_punkte = 5;
    }
    else {
      $int_num_reg = 2;
      $forfait_punkte = 8;
    }

     //********************************************************************************
    // get teams of league
    //********************************************************************************
    $pokal = 0;
    $dm = 0;
    if($int_id_kategorie == 3 || $int_id_kategorie == 4 || $int_id_kategorie >= 100) {
      $arr_teams = DBMapper::get_list_Mannschaft_of_Pokal($int_id_liga,
                                                          DBMapper::get_id_Verband());
      $pokal = 1;
      if($int_id_kategorie == 100) $dm = 1;
      else if($int_id_kategorie == 101) $dm = 2;

      for($i=0; $i<count($arr_teams[0]); $i++) {
        $arr_teams[3][$i] = $arr_teams[0][$i];
        $arr_teams[0][$i] .= "#".$arr_teams[2][$i];
      }
    }
    else {
      $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id_liga, 1);
      for($i=0; $i<count($arr_teams[0]); $i++) {
        $arr_teams[3][$i] = $arr_teams[0][$i];
        $arr_teams[2][$i] = 0;
      }
    }
    //--------------------------------------------------------------------------------

    //********************************************************************************
    // create table and scorer list
    //********************************************************************************
    $arr_table = array();
    $arr_scorer = array();
    $arr_scorer_playoff = array();
    $arr_double_player = array();
    $arr_double_playoff = array();
    for($i=0; $i<count($arr_teams[0]); $i++) {
      // add table entries for team
      // Array format: $arr_table[$team_id]['eintrag']
      $arr_table[$arr_teams[0][$i]] = self::create_table_entry($int_id_liga, $arr_teams[1][$i], $arr_teams[0][$i]);
    }
    unset($arr_teams);
    //--------------------------------------------------------------------------------
    // initialization finished

    // counting points over game_reports
    $arr_match_days = DBMapper::get_list_Spieltag($int_id_liga);
    $match_day_nbr = 0;
    $arr_match = array();

    for($d=0; $d<9; $d++)
      $arr_dm_matches[$d] = array();
    $arr_dm_keys = array();

    // dass kann nach unten wo dann auch die datei wieder erstellt werden soll
    for ($j=0; $j<count($arr_match_days[0]); $j++) {
      @unlink("tables/".$Liga->get_int_id()."_".($arr_match_days[5][$j])."_matches.tab");
    }

    for($n=0; $n<count($arr_match_days[0]); $n++) {
      $arr_matches = DBMapper::get_list_Begegnung($arr_match_days[0][$n]);
      if($dm) {
        for($m=0; $m<count($arr_matches[0]); $m++) {
          for($d=0; $d<9; $d++)
            $arr_dm_matches[$d][] = $arr_matches[$d][$m];
          $arr_dm_keys[$arr_matches[5][$m]] = count($arr_dm_matches[0])-1;
        }
      }
      // list to count state for match
      $arr_goals = array();
      for($m=0; $m<count($arr_matches[0]); $m++) {
        // für pokale muss die id aus team_id + verband_id zusammengesetzt werden
        if($pokal) {
          $arr_matches[1][$m] .= "#".$arr_matches[7][$m];
          $arr_matches[2][$m] .= "#".$arr_matches[8][$m];
        }
        // Sicherheitsabfrage, wann kann das passieren?
        if(!array_key_exists($arr_matches[1][$m], $arr_table)) continue;
        if(!array_key_exists($arr_matches[2][$m], $arr_table)) continue;
        
        $playoff = FALSE;
        $playoff = DBMapper::get_Playoff(0, $int_id_liga,
                                         $arr_matches[0][$m]);

        // filtern von dm spielen (finales, etc.)
        $filter_dm = 0;
        if($dm == 1) {
          if($arr_matches[5][$m] > 12) $filter_dm = 1;
        }
        else if($dm == 2) {
          if($arr_matches[5][$m] > 9) $filter_dm = 1;
          else if($arr_matches[5][$m] > 3 && $arr_matches[5][$m] < 7)
            $filter_dm = 1;
        }

        if(!$playoff) {
          // als erstes die Forfait behandlung
          if($arr_matches[6][$m] == 3) {
            if(!$filter_dm) self::add_forfeit_both($arr_table, $arr_matches[1][$m], $arr_matches[2][$m], $forfait_punkte);
            
            // here we can write the matches of on day
            $arr_match[$m] = self::create_forfeit_both_match($arr_matches[0][$m],
                                                             $arr_table[$arr_matches[1][$m]]['name'],
                                                             $arr_table[$arr_matches[2][$m]]['name']);
          }
          else {
            $Spielbericht = DBMapper::read_Spielbericht($arr_matches[0][$m]);
            if(!$Spielbericht && $arr_matches[6][$m] != 0) {
              if($arr_matches[6][$m] == 1) {
                $won = 2;
                $lost = 1;
              }
              else {
                $won = 1;
                $lost = 2;
              }              
              if(!$filter_dm) self::add_forfeit($arr_table, $arr_matches[$won][$m], $arr_matches[$lost][$m],
                                                $forfait_punkte, $int_win_punkte);
            
              // here we can write the matches of one day
              $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m], $arr_table[$arr_matches[1][$m]]['name'],
                                                          $arr_table[$arr_matches[2][$m]]['name'], $forfait_punkte, $won);              
            }
            else if($Spielbericht) {
              
              // create mapping array from tricot nbr to player id
              $mapping_team1 = array();
              $mapping_team2 = array();
              $arr_player = $Spielbericht->get_arr_Mitspieler_team1();
              
              if($arr_player) {
                for($l=0; $l<count($arr_player); $l++) {
                  if($arr_player[$l]->get_int_id_spieler() > 0) {
                    $mapping_team1[$arr_player[$l]->get_int_trikotnr()] = $arr_player[$l]->get_int_id_spieler();
                    $key = $arr_matches[1][$m].".".$arr_player[$l]->get_int_id_spieler();
                    if(array_key_exists($key, $arr_scorer))
                      $arr_scorer[$key]['games'] += 1;
                    else {
                      $arr_double_player[$arr_player[$l]->get_int_id_spieler()][] = $key;
                      $scorer_name = $arr_player[$l]->get_str_name().", ".$arr_player[$l]->get_str_vorname();
                      $team_name = $arr_table[$arr_matches[1][$m]]['name'];
                      $arr_scorer[$key] = self::create_scorer($scorer_name, $team_name, 1);
                    }
                  }
                }
                unset($arr_player);
              }
              $arr_player = $Spielbericht->get_arr_Mitspieler_team2();
              
              if($arr_player) {
                for($l=0; $l<count($arr_player); $l++) {
                  if($arr_player[$l]->get_int_id_spieler() > 0) {
                    $mapping_team2[$arr_player[$l]->get_int_trikotnr()] = $arr_player[$l]->get_int_id_spieler();
                    $key = $arr_matches[2][$m].".".$arr_player[$l]->get_int_id_spieler();
                    if(array_key_exists($key, $arr_scorer))
                      $arr_scorer[$key]['games'] += 1;
                    else {
                      $arr_double_player[$arr_player[$l]->get_int_id_spieler()][] = $key;
                      $scorer_name = $arr_player[$l]->get_str_name().", ".$arr_player[$l]->get_str_vorname();
                      $team_name = $arr_table[$arr_matches[2][$m]]['name'];
                      $arr_scorer[$key] = self::create_scorer($scorer_name, $team_name, 1);
                    }
                  }
                }
                unset($arr_player);
              }
              // count  events
              $scores = self::init_scores();
              $bool_extra_time = false;
              $arr_events = $Spielbericht->get_arr_Ereignis();
              $arr_events = self::sort_events($arr_events);
              // start running over the events
              for($h=0; $h<count($arr_events); $h++) {
                // check if we reached the extra time
                // wichtig zwischen klein und großfeld unterscheiden
                if($arr_events[$h]->get_int_periode() > $int_num_reg) {
                  $bool_extra_time = true;
                }
                self::handle_event($scores, $arr_scorer, $mapping_team1, $mapping_team2, $arr_events[$h],
                                   $arr_matches[1][$m], $arr_matches[2][$m], $arr_matches[6][$m],
                                   $Spielbericht);
              }
              // end running over the events
              // evaluate counted events
              if(!$filter_dm) {
                $arr_table[$arr_matches[1][$m]]['games'] += 1;
                $arr_table[$arr_matches[2][$m]]['games'] += 1;
              }

              if($arr_matches[6][$m] == 1) {
                $arr_table[$arr_matches[1][$m]]['goals'] += 0;
                if($scores['team2'] > $forfait_punkte) $g = $scores['team2'];
                else $g = $forfait_punkte;
                if(!$filter_dm) {
                  $arr_table[$arr_matches[2][$m]]['goals'] += $g;
                  $arr_table[$arr_matches[1][$m]]['goal_against'] += $g;
                }
                $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m],
                                                            "", "", $g, 2);
              }
              else if($arr_matches[6][$m] == 2) {
                $arr_table[$arr_matches[2][$m]]['goals'] += 0;
                if($scores['team1'] > $forfait_punkte) $g = $scores['team1'];
                else $g = $forfait_punkte;
                if(!$filter_dm) {
                  $arr_table[$arr_matches[1][$m]]['goals'] += $g;
                  $arr_table[$arr_matches[2][$m]]['goal_against'] += $g;
                }
                $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m],
                                                            "", "", $g, 1);
              }
              else {
                if(!$filter_dm) {
                  $arr_table[$arr_matches[1][$m]]['goals'] += $scores['team1'];
                  $arr_table[$arr_matches[2][$m]]['goals'] += $scores['team2'];
                  $arr_table[$arr_matches[1][$m]]['goal_against'] += $scores['team2'];
                  $arr_table[$arr_matches[2][$m]]['goal_against'] += $scores['team1'];
                }
                $arr_match[$m]['id'] = $arr_matches[0][$m];
                $arr_match[$m]['team1']['goals'] = $scores['team1'];
                $arr_match[$m]['team2']['goals'] = $scores['team2'];
                $arr_match[$m]['team1']['p1'] = $scores['t1'][1];
                $arr_match[$m]['team1']['p2'] = $scores['t1'][2];
                $arr_match[$m]['team1']['p3'] = $scores['t1'][3];
                $arr_match[$m]['team1']['p4'] = $scores['t1'][4];
                $arr_match[$m]['team2']['p1'] = $scores['t2'][1];
                $arr_match[$m]['team2']['p2'] = $scores['t2'][2];
                $arr_match[$m]['team2']['p3'] = $scores['t2'][3];
                $arr_match[$m]['team2']['p4'] = $scores['t2'][4];
                if($bool_extra_time) $arr_match[$m]['team2']['goals'] .= " (n.V.)";
              }
              // here we can write the matches of on day
              $arr_match[$m]['team1']['name'] = $arr_table[$arr_matches[1][$m]]['name'];
              $arr_match[$m]['team2']['name'] = $arr_table[$arr_matches[2][$m]]['name'];
              if($int_num_reg == 2) {
                if (!$bool_extra_time) {
                  $arr_match[$m]['team1']['p3'] = "-";
                  $arr_match[$m]['team2']['p3'] = "-";
                }
                $arr_match[$m]['team1']['p4'] = "-";
                $arr_match[$m]['team2']['p4'] = "-";
              }
              else {
                if (!$bool_extra_time) {
                  $arr_match[$m]['team1']['p4'] = "-";
                  $arr_match[$m]['team2']['p4'] = "-";
                }
              }
              if($arr_matches[6][$m] == 1) {
                if(!$filter_dm) {
                  $arr_table[$arr_matches[2][$m]]['punkte'] += $int_win_punkte;
                  $arr_table[$arr_matches[2][$m]]['siege'] += 1;
                  $arr_table[$arr_matches[1][$m]]['defeat'] += 1;
                }
              }
              else if($arr_matches[6][$m] == 2) {
                if(!$filter_dm) {
                  $arr_table[$arr_matches[1][$m]]['punkte'] += $int_win_punkte;
                  $arr_table[$arr_matches[1][$m]]['siege'] += 1;
                  $arr_table[$arr_matches[2][$m]]['defeat'] += 1;
                }
              }
              else if($scores['team1'] > $scores['team2']) {
                // team1 won
                if($bool_extra_time) {
                  if(!$filter_dm) {
                    $arr_table[$arr_matches[1][$m]]['punkte'] += 2;
                    $arr_table[$arr_matches[1][$m]]['siege'] += 1;
                    $arr_table[$arr_matches[1][$m]]['sds'] += 1;
                    $arr_table[$arr_matches[2][$m]]['punkte'] += 1;
                    $arr_table[$arr_matches[2][$m]]['defeat'] += 1;
                    $arr_table[$arr_matches[2][$m]]['sdn'] += 1;
                  }
                }
                else {
                  if(!$filter_dm) {
                    $arr_table[$arr_matches[1][$m]]['punkte'] += $int_win_punkte;
                    $arr_table[$arr_matches[1][$m]]['siege'] += 1;
                    $arr_table[$arr_matches[2][$m]]['defeat'] += 1;
                  }
                }
              }
              else if($scores['team1'] < $scores['team2']) {
                // team2 won
                if($bool_extra_time) {
                  if(!$filter_dm) {
                    $arr_table[$arr_matches[2][$m]]['punkte'] += 2;
                    $arr_table[$arr_matches[2][$m]]['siege'] += 1;
                    $arr_table[$arr_matches[2][$m]]['sds'] += 1;
                    $arr_table[$arr_matches[1][$m]]['punkte'] += 1;
                    $arr_table[$arr_matches[1][$m]]['defeat'] += 1;
                    $arr_table[$arr_matches[1][$m]]['sdn'] += 1;
                  }
                }
                else {
                  if(!$filter_dm) {
                    $arr_table[$arr_matches[2][$m]]['punkte'] += $int_win_punkte;
                    $arr_table[$arr_matches[2][$m]]['siege'] += 1;
                    $arr_table[$arr_matches[1][$m]]['defeat'] += 1;
                  }
                }
              }
              else {
                // draw
                if(!$filter_dm) {
                  $arr_table[$arr_matches[1][$m]]['punkte'] += 1;
                  $arr_table[$arr_matches[1][$m]]['draw'] += 1;
                  $arr_table[$arr_matches[2][$m]]['punkte'] += 1;
                  $arr_table[$arr_matches[2][$m]]['draw'] += 1;
                }
              }
            }
          }
        }
        else {
          //hier den ganzen kram nochmal für playoff spiele
          // als erstes die Forfait behandlung
          $Spielbericht = DBMapper::read_Spielbericht($arr_matches[0][$m]);
          if(!$Spielbericht && $arr_matches[6][$m] != 0) {
            if($arr_matches[6][$m] == 1) {
              $won = 2;
              $lost = 1;
            }
            else {
              $won = 1;
              $lost = 2;
            }              
            //self::add_forfeit($arr_table, $arr_matches[$won][$m], $arr_matches[$lost][$m],
            //                  $forfait_punkte, $int_win_punkte);
            
            // here we can write the matches of one day
            $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m], $arr_table[$arr_matches[1][$m]]['name'],
                                                        $arr_table[$arr_matches[2][$m]]['name'], $forfait_punkte, $won);              
          }
          else if($Spielbericht) {
            
            // create mapping array from tricot nbr to player id
            $mapping_team1 = array();
            $mapping_team2 = array();
            $arr_player = $Spielbericht->get_arr_Mitspieler_team1();
            if($arr_player) {
              for($l=0; $l<count($arr_player); $l++) {
                if($arr_player[$l]->get_int_id_spieler() > 0) {
                  $mapping_team1[$arr_player[$l]->get_int_trikotnr()] = $arr_player[$l]->get_int_id_spieler();
                  $key = $arr_matches[1][$m].".".$arr_player[$l]->get_int_id_spieler();
                  if(array_key_exists($key, $arr_scorer))
                    $arr_scorer_playoff[$key]['games'] += 1;
                  else {
                    $arr_double_playoff[$arr_player[$l]->get_int_id_spieler()][] = $key;
                    $scorer_name = $arr_player[$l]->get_str_name().", ".$arr_player[$l]->get_str_vorname();
                    $team_name = $arr_table[$arr_matches[1][$m]]['name'];
                    $arr_scorer_playoff[$key] = self::create_scorer($scorer_name, $team_name, 1);
                  }
                }
              }
              unset($arr_player);
            }
            $arr_player = $Spielbericht->get_arr_Mitspieler_team2();
            if($arr_player) {
              for($l=0; $l<count($arr_player); $l++) {
                if($arr_player[$l]->get_int_id_spieler() > 0) {
                  $mapping_team2[$arr_player[$l]->get_int_trikotnr()] = $arr_player[$l]->get_int_id_spieler();
                  $key = $arr_matches[2][$m].".".$arr_player[$l]->get_int_id_spieler();
                  if(array_key_exists($key, $arr_scorer))
                    $arr_scorer_playoff[$key]['games'] += 1;
                  else {
                    $arr_double_playoff[$arr_player[$l]->get_int_id_spieler()][] = $key;
                    $scorer_name = $arr_player[$l]->get_str_name().", ".$arr_player[$l]->get_str_vorname();
                    $team_name = $arr_table[$arr_matches[2][$m]]['name'];
                    $arr_scorer_playoff[$key] = self::create_scorer($scorer_name, $team_name, 1);
                  }
                }
              }
              unset($arr_player);
            }
            // count  events
            $scores = self::init_scores();
            $bool_extra_time = false;
            $arr_events = $Spielbericht->get_arr_Ereignis();
            $arr_events = self::sort_events($arr_events);
            // start running over the events
            for($h=0; $h<count($arr_events); $h++) {
              // check if we reached the extra time
              // wichtig zwischen klein und großfeld unterscheiden
              if($arr_events[$h]->get_int_periode() > $int_num_reg) {
                $bool_extra_time = true;
              }
              self::handle_event($scores, $arr_scorer_playoff, $mapping_team1, $mapping_team2, $arr_events[$h],
                                 $arr_matches[1][$m], $arr_matches[2][$m], $arr_matches[6][$m],
                                 $Spielbericht);
            }
            // end running over the events
            // evaluate counted events
            //$arr_table[$arr_matches[1][$m]]['games'] += 1;
            //$arr_table[$arr_matches[2][$m]]['games'] += 1;
            
            if($arr_matches[6][$m] == 1) {
              //$arr_table[$arr_matches[1][$m]]['goals'] += 0;
              if($scores['team2'] > $forfait_punkte) $g = $scores['team2'];
              else $g = $forfait_punkte;
              //$arr_table[$arr_matches[2][$m]]['goals'] += $g;
              //$arr_table[$arr_matches[1][$m]]['goal_against'] += $g;
              
              $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m],
                                                            "", "", $g, 2);
            }
            else if($arr_matches[6][$m] == 2) {
              //$arr_table[$arr_matches[2][$m]]['goals'] += 0;
              if($scores['team1'] > $forfait_punkte) $g = $scores['team1'];
              else $g = $forfait_punkte;
              //$arr_table[$arr_matches[1][$m]]['goals'] += $g;
              //$arr_table[$arr_matches[2][$m]]['goal_against'] += $g;
              $arr_match[$m] = self::create_forfeit_match($arr_matches[0][$m],
                                                          "", "", $g, 1);
            }
            else {
              //$arr_table[$arr_matches[1][$m]]['goals'] += $scores['team1'];
              //$arr_table[$arr_matches[2][$m]]['goals'] += $scores['team2'];
              //$arr_table[$arr_matches[1][$m]]['goal_against'] += $scores['team2'];
              //$arr_table[$arr_matches[2][$m]]['goal_against'] += $scores['team1'];
              $arr_match[$m]['id'] = $arr_matches[0][$m];
              $arr_match[$m]['team1']['goals'] = $scores['team1'];
              $arr_match[$m]['team2']['goals'] = $scores['team2'];
              $arr_match[$m]['team1']['p1'] = $scores['t1'][1];
              $arr_match[$m]['team1']['p2'] = $scores['t1'][2];
              $arr_match[$m]['team1']['p3'] = $scores['t1'][3];
              $arr_match[$m]['team1']['p4'] = $scores['t1'][4];
              $arr_match[$m]['team2']['p1'] = $scores['t2'][1];
              $arr_match[$m]['team2']['p2'] = $scores['t2'][2];
              $arr_match[$m]['team2']['p3'] = $scores['t2'][3];
              $arr_match[$m]['team2']['p4'] = $scores['t2'][4];
              if($bool_extra_time) $arr_match[$m]['team2']['goals'] .= " (n.V.)";
            }
            // here we can write the matches of on day
            $arr_match[$m]['team1']['name'] = $arr_table[$arr_matches[1][$m]]['name'];
            $arr_match[$m]['team2']['name'] = $arr_table[$arr_matches[2][$m]]['name'];
            if($int_num_reg == 2) {
              if (!$bool_extra_time) {
                $arr_match[$m]['team1']['p3'] = "-";
                $arr_match[$m]['team2']['p3'] = "-";
              }
              $arr_match[$m]['team1']['p4'] = "-";
              $arr_match[$m]['team2']['p4'] = "-";
            }
            else {
              if (!$bool_extra_time) {
                $arr_match[$m]['team1']['p4'] = "-";
                $arr_match[$m]['team2']['p4'] = "-";
              }
            }
            if($arr_matches[6][$m] == 1) {
              //$arr_table[$arr_matches[2][$m]]['punkte'] += $int_win_punkte;
              //$arr_table[$arr_matches[2][$m]]['siege'] += 1;
              //$arr_table[$arr_matches[1][$m]]['defeat'] += 1;
            }
            else if($arr_matches[6][$m] == 2) {
              //$arr_table[$arr_matches[1][$m]]['punkte'] += $int_win_punkte;
              //$arr_table[$arr_matches[1][$m]]['siege'] += 1;
              //$arr_table[$arr_matches[2][$m]]['defeat'] += 1;
            }
            else if($scores['team1'] > $scores['team2']) {
              // team1 won
            }
            else if($scores['team1'] < $scores['team2']) {
              // team2 won
            }
            else {
              // draw
            }
          }
        }
      }

      // here we finshed counting for the $n matchday
      // create tables
      //if($arr_match_days[5][$n] != $match_day_nbr) {
      {  $match_day_nbr = $arr_match_days[5][$n];
        $arr_tmp_table = $arr_table;
        $file = fopen("tables/".$Liga->get_int_id()."_".($match_day_nbr).".tab", "w");
        while($highest = self::get_highest($arr_tmp_table)) {
          $str_row = $arr_tmp_table[$highest]['name'];
          $str_row .= ";".$arr_tmp_table[$highest]['games'];
          $str_row .= ";".$arr_tmp_table[$highest]['siege'];
          $str_row .= ";".$arr_tmp_table[$highest]['draw'];
          $str_row .= ";".$arr_tmp_table[$highest]['defeat'];
          $str_row .= ";".$arr_tmp_table[$highest]['sds'];
          $str_row .= ";".$arr_tmp_table[$highest]['sdn'];
          $str_row .= ";".$arr_tmp_table[$highest]['goals'];
          $str_row .= ";".$arr_tmp_table[$highest]['goal_against'];
          $str_row .= ";".($arr_tmp_table[$highest]['goals']-$arr_tmp_table[$highest]['goal_against']);
          $str_row .= ";".$arr_tmp_table[$highest]['punkte'];
          if($arr_tmp_table[$highest]['ordnungsnr']) {
            $str_row .= ";".$arr_tmp_table[$highest]['ordnungsnr'];
          }
          $str_row .= "\n";
          fputs($file, $str_row);
          unset($arr_tmp_table[$highest]);
        }
        fclose($file);
        // write table for matches of the $n matchday
        if(count($arr_match)) {
          $file = fopen("tables/".$Liga->get_int_id()."_".($match_day_nbr)."_matches.tab", "a");
          foreach($arr_match as $key => $value) {
            $str_row = $value['team1']['name'];
            $str_row .= ";".$value['team2']['name'];
            $str_row .= ";".$value['team1']['goals'];
            $str_row .= ";".$value['team2']['goals'];

            $str_row .= ";".$value['team1']['p1'];
            $str_row .= ";".$value['team2']['p1'];
            $str_row .= ";".$value['team1']['p2'];
            $str_row .= ";".$value['team2']['p2'];
            $str_row .= ";".$value['team1']['p3'];
            $str_row .= ";".$value['team2']['p3'];
            $str_row .= ";".$value['team1']['p4'];
            $str_row .= ";".$value['team2']['p4'];
            $str_row .= ";".$value['id'];
            $str_row .= "\n";
            fputs($file, $str_row);
          }
          fclose($file);
          unset($arr_match);
          $arr_match = array();
        }
      }
    }
    // counting points finished

    // write tables for scorer
    // sumarize scorer with same player id
    $kill_key = array();
    foreach ($arr_double_player as $key=>$value) {
      if(count($value) > 1) {
        for($i=1; $i<count($value); $i++) {
          $arr_scorer[$value[0]]['team'] .= " / ".$arr_scorer[$value[$i]]['team'];
          $arr_scorer[$value[0]]['goals'] += $arr_scorer[$value[$i]]['goals'];
          $arr_scorer[$value[0]]['assists'] += $arr_scorer[$value[$i]]['assists'];
          $arr_scorer[$value[0]]['2min'] += $arr_scorer[$value[$i]]['2min'];
          $arr_scorer[$value[0]]['5min'] += $arr_scorer[$value[$i]]['5min'];
          $arr_scorer[$value[0]]['10min'] += $arr_scorer[$value[$i]]['10min'];
          $arr_scorer[$value[0]]['rot'] += $arr_scorer[$value[$i]]['rot'];
          $arr_scorer[$value[0]]['games'] += $arr_scorer[$value[$i]]['games'];
          $kill_key[] = $value[$i];
        }
      }
    }
    for($i=0; $i<count($kill_key); $i++) {
      unset($arr_scorer[$kill_key[$i]]);
    }
    $arr_tmp_scorer = $arr_scorer;
    $file = fopen("tables/".$Liga->get_int_id()."_scorer.tab", "w");
    $place = 0;
    $last_place = 0;
    $last = 0;
    while($highest = self::get_highest_scorer($arr_tmp_scorer)) {
      if(!$last || $last['goals'] != $arr_tmp_scorer[$highest]['goals']
         || $last['assists'] != $arr_tmp_scorer[$highest]['assists']) $place += 1;
      $last = $arr_tmp_scorer[$highest];
      if($last_place != $place) {
        $str_row = $place;
        $last_place = $place;
      }
      else $str_row = " ";
      $str_row .= ";".$last['name'];
      $str_row .= ";".$last['team'];
      $str_row .= ";".($last['goals']+$last['assists']);
      $str_row .= ";".$last['goals'];
      $str_row .= ";".$last['assists'];
      $str_row .= ";".$last['2min'];
      $str_row .= ";".$last['5min'];
      $str_row .= ";".$last['10min'];
      $str_row .= ";".$last['rot'];
      $str_row .= ";".$last['games'];
      $str_row .= "\n";
      fputs($file, $str_row);
      unset($arr_tmp_scorer[$highest]);
    }
    fclose($file);
    // create tables finished

    $kill_key = array();
    foreach ($arr_double_playoff as $key=>$value) {
      if(count($value) > 1) {
        for($i=1; $i<count($value); $i++) {
          $arr_scorer_playoff[$value[0]]['team'] .= " / ".$arr_scorer_playoff[$value[$i]]['team'];
          $arr_scorer_playoff[$value[0]]['goals'] += $arr_scorer_playoff[$value[$i]]['goals'];
          $arr_scorer_playoff[$value[0]]['assists'] += $arr_scorer_playoff[$value[$i]]['assists'];
          $arr_scorer_playoff[$value[0]]['2min'] += $arr_scorer_playoff[$value[$i]]['2min'];
          $arr_scorer_playoff[$value[0]]['5min'] += $arr_scorer_playoff[$value[$i]]['5min'];
          $arr_scorer_playoff[$value[0]]['10min'] += $arr_scorer_playoff[$value[$i]]['10min'];
          $arr_scorer_playoff[$value[0]]['rot'] += $arr_scorer_playoff[$value[$i]]['rot'];
          $arr_scorer_playoff[$value[0]]['games'] += $arr_scorer_playoff[$value[$i]]['games'];
          $kill_key[] = $value[$i];
        }
      }
    }
    for($i=0; $i<count($kill_key); $i++) {
      unset($arr_scorer_playoff[$kill_key[$i]]);
    }
    if(count($arr_scorer_playoff) > 0) {
      $arr_tmp_scorer = $arr_scorer_playoff;
      $file = fopen("tables/".$Liga->get_int_id()."_scorer_pl.tab", "w");
      $place = 0;
      $last_place = 0;
      $last = 0;
      while($highest = self::get_highest_scorer($arr_tmp_scorer)) {
        if(!$last || $last['goals'] != $arr_tmp_scorer[$highest]['goals']
           || $last['assists'] != $arr_tmp_scorer[$highest]['assists']) $place += 1;
        $last = $arr_tmp_scorer[$highest];
        if($last_place != $place) {
          $str_row = $place;
          $last_place = $place;
        }
        else $str_row = " ";
        $str_row .= ";".$last['name'];
        $str_row .= ";".$last['team'];
        $str_row .= ";".($last['goals']+$last['assists']);
        $str_row .= ";".$last['goals'];
        $str_row .= ";".$last['assists'];
        $str_row .= ";".$last['2min'];
        $str_row .= ";".$last['5min'];
        $str_row .= ";".$last['10min'];
        $str_row .= ";".$last['rot'];
        $str_row .= ";".$last['games'];
        $str_row .= "\n";
        fputs($file, $str_row);
        unset($arr_tmp_scorer[$highest]);
      }
      fclose($file);
    }
    
    // hier der dm zusatz
    if($dm) {
      $arr_g1 = array();
      $arr_g2 = array();
      
      if($dm == 1) $num_g = 6;
      else $num_g = 3;
      
      for($i=1; $i<=$num_g; $i++) {
        $id1 = $arr_dm_matches[1][$arr_dm_keys[$i]]."#".$arr_dm_matches[7][$arr_dm_keys[$i]];
        $id2 = $arr_dm_matches[2][$arr_dm_keys[$i]]."#".$arr_dm_matches[8][$arr_dm_keys[$i]];
        $found_id1 = 0;
        $found_id2 = 0;
        for($k=0; $k<count($arr_g1); $k++) {
          if($arr_g1[$k] == $id1) $found_id1 = 1;
          if($arr_g1[$k] == $id2) $found_id2 = 1;
        }
        if(!$found_id1) $arr_g1[] = $id1;
        if(!$found_id2) $arr_g1[] = $id2;
      }

      for($i=7; $i<=6+$num_g; $i++) {
        $id1 = $arr_dm_matches[1][$arr_dm_keys[$i]]."#".$arr_dm_matches[7][$arr_dm_keys[$i]];
        $id2 = $arr_dm_matches[2][$arr_dm_keys[$i]]."#".$arr_dm_matches[8][$arr_dm_keys[$i]];
        $found_id1 = 0;
        $found_id2 = 0;
        for($k=0; $k<count($arr_g2); $k++) {
          if($arr_g2[$k] == $id1) $found_id1 = 1;
          if($arr_g2[$k] == $id2) $found_id2 = 1;
        }
        if(!$found_id1) $arr_g2[] = $id1;
        if(!$found_id2) $arr_g2[] = $id2;
      }

      for($k=0; $k<count($arr_g1); $k++) {
        $table_g1[$arr_g1[$k]] = $arr_table[$arr_g1[$k]];
      }

      for($k=0; $k<count($arr_g2); $k++) {
        $table_g2[$arr_g2[$k]] = $arr_table[$arr_g2[$k]];
      }

      $arr_sort1 = array();
      $arr_table_g1 = $table_g1;
      while($highest = self::get_highest($arr_table_g1)) {
        $arr_sort1[] = $highest;
        unset($arr_table_g1[$highest]);
      }

      $arr_sort2 = array();
      $arr_table_g2 = $table_g2;
      while($highest = self::get_highest($arr_table_g2)) {
        $arr_sort2[] = $highest;
        unset($arr_table_g2[$highest]);
      }

      $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[13]]);
      if(!$Spielbericht) {
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[13]]);
        $arr_t1 = explode("#", $arr_sort1[0]);
        $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
        
        $arr_t2 = explode("#", $arr_sort2[1]);
        $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
        DBMapper::update_Begegnung($Begegnung);        
      }

      $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[14]]);
      if(!$Spielbericht) {
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[14]]);
        $arr_t1 = explode("#", $arr_sort1[1]);
        $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
        
        $arr_t2 = explode("#", $arr_sort2[0]);
        $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
        DBMapper::update_Begegnung($Begegnung);        
      }

      if($dm == 1) {
        // hier noch spiel um platz 5 und 7
        $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[15]]);
        if(!$Spielbericht) {
          $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[15]]);
          $arr_t1 = explode("#", $arr_sort1[2]);
          $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
          
          $arr_t2 = explode("#", $arr_sort2[2]);
          $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
          DBMapper::update_Begegnung($Begegnung);        
        }

        $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[16]]);
        if(!$Spielbericht) {
          $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[16]]);
          $arr_t1 = explode("#", $arr_sort1[3]);
          $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);
          
          $arr_t2 = explode("#", $arr_sort2[3]);
          $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
          DBMapper::update_Begegnung($Begegnung);        
        }

      }
      
      //  jetzt noch die dm tabellen seperat wegschreiben und dann wird auch die ausgabe einfach :-)
      $arr_tmp_table = $table_g1;
      $file = fopen("tables/dm_g1_".$Liga->get_int_id().".tab", "w");
      while($highest = self::get_highest($arr_tmp_table)) {
        $str_row = $arr_tmp_table[$highest]['name'];
        $str_row .= ";".$arr_tmp_table[$highest]['games'];
        $str_row .= ";".$arr_tmp_table[$highest]['siege'];
        $str_row .= ";".$arr_tmp_table[$highest]['draw'];
        $str_row .= ";".$arr_tmp_table[$highest]['defeat'];
        $str_row .= ";".$arr_tmp_table[$highest]['sds'];
        $str_row .= ";".$arr_tmp_table[$highest]['sdn'];
        $str_row .= ";".$arr_tmp_table[$highest]['goals'];
        $str_row .= ";".$arr_tmp_table[$highest]['goal_against'];
        $str_row .= ";".($arr_tmp_table[$highest]['goals']-$arr_tmp_table[$highest]['goal_against']);
        $str_row .= ";".$arr_tmp_table[$highest]['punkte'];
        if($arr_tmp_table[$highest]['ordnungsnr']) {
          $str_row .= ";".$arr_tmp_table[$highest]['ordnungsnr'];
        }
        $str_row .= "\n";
        fputs($file, $str_row);
        unset($arr_tmp_table[$highest]);
      }
      fclose($file);

      $arr_tmp_table = $table_g2;
      $file = fopen("tables/dm_g2_".$Liga->get_int_id().".tab", "w");
      while($highest = self::get_highest($arr_tmp_table)) {
        $str_row = $arr_tmp_table[$highest]['name'];
        $str_row .= ";".$arr_tmp_table[$highest]['games'];
        $str_row .= ";".$arr_tmp_table[$highest]['siege'];
        $str_row .= ";".$arr_tmp_table[$highest]['draw'];
        $str_row .= ";".$arr_tmp_table[$highest]['defeat'];
        $str_row .= ";".$arr_tmp_table[$highest]['sds'];
        $str_row .= ";".$arr_tmp_table[$highest]['sdn'];
        $str_row .= ";".$arr_tmp_table[$highest]['goals'];
        $str_row .= ";".$arr_tmp_table[$highest]['goal_against'];
        $str_row .= ";".($arr_tmp_table[$highest]['goals']-$arr_tmp_table[$highest]['goal_against']);
        $str_row .= ";".$arr_tmp_table[$highest]['punkte'];
        if($arr_tmp_table[$highest]['ordnungsnr']) {
          $str_row .= ";".$arr_tmp_table[$highest]['ordnungsnr'];
        }
        $str_row .= "\n";
        fputs($file, $str_row);
        unset($arr_tmp_table[$highest]);
      }
      fclose($file);
      
      // hier noch das update des finales
      if($dm == 1 && $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[13]])) {
        $id_1[0] = $arr_dm_matches[1][$arr_dm_keys[13]];
        $id_2[0] = $arr_dm_matches[7][$arr_dm_keys[13]];
        $id_1[1] = $arr_dm_matches[2][$arr_dm_keys[13]];
        $id_2[1] = $arr_dm_matches[8][$arr_dm_keys[13]];
        $arr_events = $Spielbericht->get_arr_Ereignis();
        $arr_events = self::sort_events($arr_events);
        $t1 = 0;
        $t2 = 0;
        for($i=count($arr_events)-1; $i>0; $i++) {
          $t1 = $arr_events[$i]->get_int_tore_team1();
          $t2 = $arr_events[$i]->get_int_tore_team2();
          if($t1 > 0 || $t2 > 0) break; 
        }
        if($t1 > $t2) {
          $n1 = 0;
          $n2 = 1;
        }
        else {
          $n1 = 1;
          $n2 = 0;
        }
        
        // finale
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[18]]);
        $Begegnung->set_int_id_mannschaft1($id_1[$n1], $id_2[$n1]);          
        DBMapper::update_Begegnung($Begegnung);        
        // spiel um platz 3
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[17]]);
        $Begegnung->set_int_id_mannschaft1($id_1[$n2], $id_2[$n2]);          
        DBMapper::update_Begegnung($Begegnung);

      }

      if($dm == 1 && $Spielbericht = DBMapper::read_Spielbericht($arr_dm_matches[0][$arr_dm_keys[14]])) {
        $id_1[0] = $arr_dm_matches[1][$arr_dm_keys[14]];
        $id_2[0] = $arr_dm_matches[7][$arr_dm_keys[14]];
        $id_1[1] = $arr_dm_matches[2][$arr_dm_keys[14]];
        $id_2[1] = $arr_dm_matches[8][$arr_dm_keys[14]];
        $arr_events = $Spielbericht->get_arr_Ereignis();
        $arr_events = self::sort_events($arr_events);
        $t1 = 0;
        $t2 = 0;
        for($i=count($arr_events)-1; $i>0; $i++) {
          $t1 = $arr_events[$i]->get_int_tore_team1();
          $t2 = $arr_events[$i]->get_int_tore_team2();
          if($t1 > 0 || $t2 > 0) break; 
        }
        if($t1 > $t2) {
          $n1 = 0;
          $n2 = 1;
        }
        else {
          $n1 = 1;
          $n2 = 0;
        }
         
        // finale
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[18]]);
        $Begegnung->set_int_id_mannschaft2($id_1[$n1], $id_2[$n1]);          
        DBMapper::update_Begegnung($Begegnung);        
        // spiel um platz 3
        $Begegnung = DBMapper::read_Begegnung($arr_dm_matches[0][$arr_dm_keys[17]]);
        $Begegnung->set_int_id_mannschaft2($id_1[$n2], $id_2[$n2]);          
        DBMapper::update_Begegnung($Begegnung);

      }
    }
  }

  static function get_highest($arr_table) {
    $highest = 0;
    foreach ($arr_table as $key=>$value) {
      if(!$highest) $highest = $key;
      else if($arr_table[$highest]['ordnungsnr'] < $value['ordnungsnr']) {
        $highest = $key;
      }
      else if($arr_table[$highest]['ordnungsnr'] == $value['ordnungsnr']) {
        if($arr_table[$highest]['punkte'] < $value['punkte']) {
          // we have a new winner
          $highest = $key;
        }
        else if($arr_table[$highest]['punkte'] == $value['punkte']) {
          // have we a new highest?
          /*
            if($arr_table[$highest]['siege'] < $value['siege']) {
            // we have a new winner
            $highest = $key;
            }
            else if($arr_table[$highest]['siege'] == $value['siege']) {
            if($arr_table[$highest]['draw'] < $value['draw']) {
            // we have a new winner
            $highest = $key;
            }
            else if($arr_table[$highest]['draw'] == $value['draw']) {
            // and along
            */
          $goals_diff_team1 = $arr_table[$highest]['goals'] - $arr_table[$highest]['goal_against'];
          $goals_diff_team2 = $value['goals'] - $value['goal_against'];
          if($goals_diff_team1 < $goals_diff_team2) {
            // we have a new winner
            $highest = $key;
          }
          else if($goals_diff_team1 == $goals_diff_team2) {
            // and along
            if($arr_table[$highest]['goals'] < $value['goals']) {
              // we have a new winner
              $highest = $key;
            }
            else if($arr_table[$highest]['goals'] == $value['goals']) {
              // and along

            }
          }
        }
      }
    }
    //}
    //}
    return $highest;
  }

  static function get_highest_scorer($arr_table) {
    $highest = 0;
    foreach ($arr_table as $key=>$value) {
      if(!$highest) $highest = $key;
      else if(($arr_table[$highest]['goals']+$arr_table[$highest]['assists']) < ($value['goals']+$value['assists'])) {
        // we have a new winner
        $highest = $key;
      }
      else if(($arr_table[$highest]['goals']+$arr_table[$highest]['assists']) == ($value['goals']+$value['assists'])) {
        // have we a new highest?
        if($arr_table[$highest]['goals'] < $value['goals']) {
          // we have a new winner
          $highest = $key;
        }
      }
    }
    return $highest;
  }

  static function sort_events($arr_events) {
    $arr_result = array();
    if(!$arr_events) return $arr_result;
    while(count($arr_events)) {
      $min = 1000;
      $event = 1000;
      foreach($arr_events as $key=>$value) {
        if($value->get_int_zeile() < $min) {
          $min = $value->get_int_zeile();
          $event = $key;
        }
      }
      $arr_result[] = $arr_events[$event];
      unset($arr_events[$event]);
    }
    return $arr_result;
  }

  static function create_table_entry($int_id_liga, $team_name, $team_id) {
    $arr_result['name'] = $team_name;
    $arr_result['games'] = 0;
    $arr_result['punkte'] = 0;
    $arr_result['siege'] = 0;
    $arr_result['draw'] = 0;
    $arr_result['defeat'] = 0;
    $arr_result['sds'] = 0;
    $arr_result['sdn'] = 0;
    $arr_result['goals'] = 0;
    $arr_result['goal_against'] = 0;
    $arr_result['ordnungsnr'] = DBMapper::get_Statistik_Ordnung(0, $int_id_liga, $team_id);
    return $arr_result;
  }

  static function add_forfeit_both(&$arr_table, $team1_id, $team2_id, $forfait_punkte) {
    $arr_table[$team1_id]['games'] += 1;
    $arr_table[$team2_id]['games'] += 1;
    $arr_table[$team1_id]['defeat'] += 1;
    $arr_table[$team2_id]['defeat'] += 1;
    $arr_table[$team1_id]['goal_against'] += $forfait_punkte;
    $arr_table[$team2_id]['goal_against'] += $forfait_punkte; 
  }

  static function create_forfeit_both_match($int_id_begegnung, $team1_name, $team2_name) {
    $arr_result['id'] = $int_id_begegnung;
    $arr_result['team1']['name'] = $team1_name;
    $arr_result['team2']['name'] = $team2_name;
    
    $arr_result['team1']['goals'] = "-";
    $arr_result['team2']['goals'] = "-(forfait)";
    $arr_result['team1']['p1'] = "-";
    $arr_result['team1']['p2'] = "-";
    $arr_result['team1']['p3'] = "-";
    $arr_result['team1']['p4'] = "-";
    $arr_result['team2']['p1'] = "-";
    $arr_result['team2']['p2'] = "-";
    $arr_result['team2']['p3'] = "-";
    $arr_result['team2']['p4'] = "-";
    return $arr_result;
  }

  static function add_forfeit(&$arr_table, $team1_id, $team2_id, $forfait_punkte, $int_win_punkte) {
    $arr_table[$team1_id]['games'] += 1;
    $arr_table[$team2_id]['games'] += 1;
    $arr_table[$team1_id]['siege'] += 1;
    $arr_table[$team2_id]['defeat'] += 1;
    $arr_table[$team1_id]['goals'] += $forfait_punkte;
    $arr_table[$team2_id]['goal_against'] += $forfait_punkte;
    $arr_table[$team1_id]['punkte'] += $int_win_punkte;
  }

  static function create_forfeit_match($int_id_begegnung, $team1_name, $team2_name,
                                       $forfait_punkte, $won) {
    $arr_result['id'] = $int_id_begegnung;
    $arr_result['team1']['name'] = $team1_name;
    $arr_result['team2']['name'] = $team2_name;
    if($won == 1) {
      $arr_result['team1']['goals'] = $forfait_punkte;
      $arr_result['team2']['goals'] = "0(forfait)";
    }
    else {
      $arr_result['team1']['goals'] = "0";
      $arr_result['team2']['goals'] = $forfait_punkte."(forfait)";
    }
    $arr_result['team1']['p1'] = "-";
    $arr_result['team1']['p2'] = "-";
    $arr_result['team1']['p3'] = "-";
    $arr_result['team1']['p4'] = "-";
    $arr_result['team2']['p1'] = "-";
    $arr_result['team2']['p2'] = "-";
    $arr_result['team2']['p3'] = "-";
    $arr_result['team2']['p4'] = "-";          
    return $arr_result;
  }          

  static function create_scorer($player_name, $team_name, $nbr_games = 0) {
    $arr_result['name'] = $player_name;
    $arr_result['team'] = $team_name;
    $arr_result['goals'] = 0;
    $arr_result['assists'] = 0;
    $arr_result['2min'] = 0;
    $arr_result['5min'] = 0;
    $arr_result['10min'] = 0;
    $arr_result['rot'] = 0;
    $arr_result['games'] = $nbr_games;
    return $arr_result;
  }  

  static function init_scores() {
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
    return $scores;
  }

  static function handle_event(&$scores, &$arr_scorer, $mapping_team1,
                               $mapping_team2, $event, $team1_id, $team2_id, $forfeit,
                               $Spielbericht) {
    if($event->get_int_id_strafe() != "-") {
      // we have an penalty
      if($event->get_int_id_strafe() == 1) $strafe = '2min';
      else if($event->get_int_id_strafe() == 2) $strafe = '5min';
      else if($event->get_int_id_strafe() == 3) $strafe = '10min';
      else if($event->get_int_id_strafe() >= 4) $strafe = 'rot';
      if(isset($mapping_team1[$event->get_int_nr_team1()]) &&
         $mapping_team1[$event->get_int_nr_team1()] > 0) {
        $arr_scorer[$team1_id.".".$mapping_team1[$event->get_int_nr_team1()]][$strafe] += 1;
      }
      else if(isset($mapping_team2[$event->get_int_nr_team2()]) &&
              $mapping_team2[$event->get_int_nr_team2()] > 0) {
        $arr_scorer[$team2_id.".".$mapping_team2[$event->get_int_nr_team2()]][$strafe] += 1;
      }
    }
    else {
      if($event->get_int_tore_team1() == $scores['team1']+1) {
        // team1 scores; count the score, the scorer and the assist
        $scores['team1'] += 1;
        if($forfeit != 1) {
          $scores['t1'][$event->get_int_periode()]++;
          if($event->get_int_nr_team1() != "-" && $event->get_int_nr_team1() != 1000) {
            if(isset($mapping_team1[$event->get_int_nr_team1()]) &&
               $mapping_team1[$event->get_int_nr_team1()] > 0) {
              $arr_scorer[$team1_id.".".$mapping_team1[$event->get_int_nr_team1()]]['goals'] += 1;
            }
            if($event->get_int_ass_team1() != "-" && $event->get_int_ass_team1() != 1000) {
              if(isset($mapping_team1[$event->get_int_ass_team1()]) &&
                 $mapping_team1[$event->get_int_ass_team1()] > 0)
                $arr_scorer[$team1_id.".".$mapping_team1[$event->get_int_ass_team1()]]['assists'] += 1;
            }
          }
          else {
            // should was an own goal
          }
        }
      }
      else if($event->get_int_tore_team2() == $scores['team2']+1) {
        // team2 scores; count the score, the scorer and the assist
        $scores['team2'] += 1;
        if($forfeit != 2) {
          $scores['t2'][$event->get_int_periode()]++;
          if($event->get_int_nr_team2() != "-" && $event->get_int_nr_team2() != 1000) {
            if(isset($mapping_team2[$event->get_int_nr_team2()]) &&
               $mapping_team2[$event->get_int_nr_team2()] > 0) {
              //if($int_id_liga==2) echo "<br/>".$team2_id.".".$mapping_team2[$event->get_int_nr_team2()];
              $arr_scorer[$team2_id.".".$mapping_team2[$event->get_int_nr_team2()]]['goals'] += 1;
            }
            if($event->get_int_ass_team2() != "-" && $event->get_int_ass_team2() != 1000) {
              if(isset($mapping_team2[$event->get_int_ass_team2()]) &&
                 $mapping_team2[$event->get_int_ass_team2()] > 0)
                $arr_scorer[$team2_id.".".$mapping_team2[$event->get_int_ass_team2()]]['assists'] += 1;
            }
          }
          else {
            // should was an own goal
          }
        }
      }
      else {
        // i think this should be an corrupted game_report
        // if so, we have to mark it
        //if($int_id_liga==2) echo "<br/>".$Spielbericht->get_str_spielort();
        echo " ".$Spielbericht->get_date_datum();
        echo " ".$Spielbericht->get_str_team1();
        echo " ".$Spielbericht->get_str_team2();
        echo "<br/>fehler: ".$event->get_int_tore_team1()." : ".$event->get_int_tore_team2();
      }
    } // end goal event 
  }

}

?>
