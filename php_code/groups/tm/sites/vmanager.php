<?php
$x1 = 3;
$x2 = 33.2;
$x1 = 3;
$x2 = 11;
$x3 = 22;

//$arr_id_leiter = DBMapper::get_list_Person();
//$arr_id_leiter[0][] = 0;
//$arr_id_leiter[1][] = "leer";

//$arr_teams = DBMapper::get_list_Mannschaft($club_id);
//** filter the teams if we have a team manager **

$arr_ligen = DBM_Ligen::get_list_Liga();

if(Login::isInGroup("VM")) {
  $arr_teams = DBMapper::get_list_Mannschaft($club_id, 1);
  $arr_teams2 = DBMapper::get_list_Mannschaft_SG($club_id);
  for($i=0; $i<count($arr_teams2[0]); $i++) {
    $arr_teams[0][] = $arr_teams2[0][$i];
    $arr_teams[1][] = $arr_teams2[1][$i];
    $arr_teams[2][] = $arr_teams2[2][$i];
  }
}
else
  $arr_teams = DBM_Benutzer::get_mannschaften($_SESSION['arr_login']['id_benutzer']);

for($i=0; $i<count($arr_teams[0]); $i++) {
  for($k=0; $k<count($arr_ligen[0]); $k++) {
    if($arr_ligen[0][$k] == $arr_teams[2][$i]) {
      $arr_teams[1][$i] .= " (".$arr_ligen[2][$k].")";
      break;
    }
  }
}
// #### auch die mannschaften der SGs laden ####//

if($bool_show_data) {
  for($i=0; $i<count($arr_ligen[0]); $i++) {
    if($arr_ligen[0][$i] == $int_t_liga) {
      $int_t_liga = $i;
      break;
    }
  }

  /*
  if($Mannschaft->get_bool_sg()) {
    $arr_player2[0] = array();
    $arr_player2[1] = array();
    $arr_clubs = DBMapper::get_list_Verein_of_SG($int_t_id);
    if($arr_clubs) {
      for($i=0; $i<count($arr_clubs); $i++) {
	$arr_tmp = DBM_Spieler::get_list_Spieler_of_Verein($arr_clubs[$i]);
	$arr_player2[0] = array_merge($arr_player2[0], $arr_tmp[0]);
	$arr_player2[1] = array_merge($arr_player2[1], $arr_tmp[1]);
      }
    }
  }
  else {
    $arr_player2 = DBM_Spieler::get_list_Spieler_of_Verein($club_id);
  }
  */
  $arr_player2 = DBM_Spieler::get_list_Spieler_of_Verein($club_id);
}

$arr_t_id_leiter = DBMapper::get_list_Person($club_id);
$arr_t_id_leiter[0][] = 0;
$arr_t_id_leiter[1][] = "leer";
if($bool_show_data) $count = count($arr_player[0]);
else $count = 0;
$form_id = $my_form->create_form("club_manager", "VereinsManager", -1, -1,
                                 60, 24+$count*1.7);
$my_form->input_hidden($form_id, "nav", "teams");

$my_form->print_text($form_id, $club_name, 3, 1);
$my_form->input_select($form_id, "t_id_team", $arr_teams[1], $arr_teams[0],
		       $int_t_id, 1, 0, 3, 4, 16);
$my_form->input_button($form_id, "show", "anzeigen", 21, 4, 7, 2);

if($bool_show_data) {
  $my_form->input_hidden($form_id, "t_id", $int_t_id);

  $my_form->print_text($form_id, "Liga: ".$arr_ligen[1][$int_t_liga], $x1, 8);
  $my_form->print_text($form_id, "Betreuer:", $x1, 11.3);

  /*
  if (Login::isInGroup("VM")) {
    $my_form->input_select($form_id, "t_id_leiter", $arr_t_id_leiter[1],
  			 $arr_t_id_leiter[0],
  			 $int_t_id_leiter, 1, 0, 9, 11, 12);
    $my_form->input_button($form_id, "saveB", "Betreuer speichern", $x2+14,
  			 11.56, 12, 2);
    $my_form->input_button($form_id, "next", "Neue Person anlegen", $x2+28,
  			 11.56, 12, 2);
             } else {*/
    $my_form->input_hidden($form_id, "t_id_leiter", $int_t_id_leiter);
    $t_leiter = DBMapper::read_Person($int_t_id_leiter);
    $name_t_leiter = $t_leiter->get_str_name() . ", " . $t_leiter->get_str_vorname();
    $my_form->print_text($form_id, $name_t_leiter, 9, 11.3);
    //}

  $my_form->print_text($form_id, "Lizenzen der Mannschaft:", $x1, 14);
  $k = 0;
  $arr_lizenzen = DBMapper::get_list_Lizenz_of_Mannschaft($int_t_id);
  // 0 id_lizenz
  // 1 id_spieler
  for($k=0; $k<count($arr_lizenzen[0]); $k++) {
    $Spieler = DBM_Spieler::read_Spieler($arr_lizenzen[1][$k]);
    
    $arr_table[0][] = $Spieler->get_str_name();
    $arr_table[1][] = $Spieler->get_str_vorname();
    $arr_lizenzstatus = DBMapper::get_last_Lizenzstatus($arr_lizenzen[0][$k]);
    $arr_table[2][] = DBMapper::get_Lizenzstatus($arr_lizenzstatus[0]);
    $arr_lizenzen[2][] = $arr_lizenzstatus[0];
    // 2 id_lizenzstatus
    $arr_ids[] = $k+1;
    $arr_check[] = 0;

  }
  $int_rows = $k;
  if($int_rows > 0) {
    $arr_titles = array("Name", "Vorname", "status");
    $my_form->input_check_table($form_id, "", $arr_table, $arr_ids, $arr_titles,
				$arr_check, 0, 0, 3, 16);
  }
  $arr_player3[0] = array();
  $arr_player3[1] = array();
  for($k=0; $k<count($arr_player2[0]); $k++) {
    $found = false;
    for($n=0; $n<count($arr_player[0]); $n++) {
      if($arr_player[0][$n] == $arr_player2[0][$k]) {
        //if(($arr_player[2][$n] < 4) && ($arr_player[2][$n] != 0))
          $found = true;
        break;
      }
    }
    if(!$found) {
      $arr_player3[0][] = $arr_player2[0][$k];
      $arr_player3[1][] = $arr_player2[1][$k];
    }
  }
  $arr_player4[0] = array();
  $arr_player4[1] = array();
  for($k=0; $k<count($arr_player[0]); $k++) {
    for($l=0; $l<count($arr_lizenzen[0]); $l++) {
      if($arr_lizenzen[1][$l] == $arr_player[0][$k]) {
        if($arr_lizenzen[2][$l] == 2) {
          $arr_player4[0][] = $arr_lizenzen[0][$l].";".$arr_player[0][$k];
          $arr_player4[1][] = $arr_player[1][$k].", ".$arr_player[2][$k];
        }
        break;
      }
    }
  }
  /*
  for($i=0; $i<count($arr_player[1]); $i++) {
    $my_form->print_text($form_id, $arr_player[1][$i], $x1, 16+$i*1.4);
  }
  */

  $my_form->input_select($form_id, "t_id_player", $arr_player3[1],
			 $arr_player3[0], 0, 1, 0, 3, 18+$int_rows*1.5, 16);
  $my_form->input_button($form_id, "offer", "Lizenz beantragen", 21,
			 19.1+$int_rows*1.621, 12, 2);
  $my_form->input_button($form_id, "next", "Neuen Spieler anlegen", 35,
			 19.1+$int_rows*1.621, 12, 2);
  $my_form->input_select($form_id, "t_id_rplayer", $arr_player4[1],
			 $arr_player4[0], 0, 1, 0, 3, 20.5+$int_rows*1.5, 16);
  $my_form->input_button($form_id, "remove", "Lizenz zur&uuml;ckziehen", 21,
			 21.8+$int_rows*1.621, 12, 2);
}
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>
