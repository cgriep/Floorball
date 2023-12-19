<?php
/**
 * Erstellung für ...
 *
 * @package    VM
 * @subpackage Sites
 */
$user = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$int_id_club = $user->get_int_id_verein();

$x1 = 1;
$x2 = 5;
$x3 = 26;
$x4 = 50;

$Zeitfenster = 20; // in Tagen ab 0:00 Uhr des Spieltages

$arr_league = DBM_Ligen::get_list_Liga();
$arr_tmp = DBMapper::get_list_Spieltag(0);
for($i=0; $i<count($arr_tmp[0]); $i++) {
  $Spielort = DBMapper::read_Spielort($arr_tmp[4][$i]);
  if($Spielort && $Spielort->get_int_id_verein() == $int_id_club) {
    $now = strtotime('now');
    $time = strtotime($arr_tmp[1][$i]);
    if($now > $time && $now < $time + 86400*$Zeitfenster) {
      for($k=0; $k<count($arr_league[0]); $k++) {
        if($arr_tmp[2][$i] == $arr_league[0][$k]) {
          $arr_tmp[1][$i] .= " ".$arr_league[1][$k];
          break;
        }
      }
      $arr_matches[0][] = $arr_tmp[0][$i];
      $arr_matches[1][] = $arr_tmp[1][$i];
      $arr_matches[2][] = $arr_tmp[2][$i];
      $arr_matches[3][] = $arr_tmp[3][$i];
      $arr_matches[4][] = $arr_tmp[4][$i];
      $arr_matches[5][] = $arr_tmp[5][$i];
      //$arr_matches[6][] = $arr_tmp[6][$i];
    }
  }
  /*
  if($arr_matches[0][$i] == $int_id_match) {
    $str_match1 = $arr_matches[1][$i];
  }*/
}

$arr_matches[0][] = 0;
$arr_matches[1][] = "leer";
$arr_matches[2][] = 0;
$form_id = $my_form->create_form("game_list", "Spielberichte", -1, -1, 60, 30);


$my_form->print_text($form_id, "Spieltag:", 3, 1);
$my_form->input_select($form_id, "list", $arr_matches[1], $arr_matches[0], $int_id_matchday, 1, 0, $x2+3, 1-0.2, 30);
$my_form->input_button($form_id, "select_day", "laden", $x4-8, 1-0.2, 8, 1.5);

//$my_form->input_hidden($form_id, "nav", "game_list");
//$my_form->print_text($form_id, "Liga:", 3, 1);
//$my_form->input_select($form_id, "id_liga", $arr_league[1], $arr_league[0], $int_id_liga, 1, 0, $x2+3, 1-0.2, 15);
//$my_form->input_button($form_id, "show", "anzeigen", 25, 1-0.2, 8, 1.7);
//$my_form->input_button($form_id, "gf", "GF", 23, 1, 4, 1.5);
//$my_form->input_button($form_id, "kf", "KF", 28, 1, 4, 1.5);
//$my_form->input_button($form_id, "jugend", "Jugend", 33, 1, 6, 1.5);
if($int_id_liga > 0)
  $my_form->input_button($form_id, "statistics", "Statistiken erzeugen",
                         46, 1, 12, 1.5);
if($bool_show_data) {
  $my_form->input_hidden($form_id, "id_menu", $int_id_menu);
  $my_form->input_hidden($form_id, "id_liga", $int_id_liga);
  $my_form->input_hidden($form_id, "id_matchday", $int_id_matchday);
  if($int_id_matchday) {
    $arr_games = DBMapper::get_list_Begegnung($int_id_matchday);
    $my_form->print_text($form_id, "Begegnungen:", $x1, 5);
    for($i=0; $i<count($arr_games[0]); $i++) {
      $Team1 = DBMapper::read_Mannschaft($arr_games[1][$i], $arr_games[7][$i]);
      $Team2 = DBMapper::read_Mannschaft($arr_games[2][$i], $arr_games[8][$i]);
      $str_text = $arr_games[3][$i]." ".$Team1->get_str_name()." : ".$Team2->get_str_name();
      if($i%2) {
        $my_form->input_t1_button($form_id, "game_".$arr_games[0][$i],
                                  $str_text, $x1, 7.2+$i*2, 50, 2, 1);
      } else {
        $my_form->input_t2_button($form_id, "game_".$arr_games[0][$i],
                                  $str_text, $x1, 7.2+$i*2, 50, 2, 1);
      }
      /*
      $my_form->print_text($form_id, $arr_games[3][$i], 1, 7+$i*2);
      $my_form->print_text($form_id, $Team1->get_str_name(), $x2, 7+$i*2);
      $my_form->print_text($form_id, ": ".$Team2->get_str_name(), $x3, 7+$i*2);
      $my_form->input_button($form_id, "game_".$arr_games[0][$i], "edit", $x4, 7.2+$i*2.18, 6, 1.4);
      */
    }
  }
  else {
    //if(count($arr_ligen) >= 2)
      //$my_form->input_h_list($form_id, "list1", $arr_ligen[1], $arr_ligen[0], 1, 5, -1, -1, 19, 2);
    if($int_id_liga) {
      $arr_matchdays = DBMapper::get_list_Spieltag_by_liga($int_id_liga);
      $my_form->print_text($form_id, "Datum, Spielort:", $x1, 5);
      for($i=0; $i<count($arr_matchdays[0]); $i++) {
        $Spieltag = DBMapper::read_Spieltag($arr_matchdays[0][$i]);
        $Spielort = DBMapper::read_Spielort($Spieltag->get_int_id_spielort());
        if(!$Spielort) $Spielort = new Spielort();
        if($i%2) {
          $my_form->input_t1_button($form_id, "matchday_".$arr_matchdays[0][$i],
                                    $arr_matchdays[1][$i].", ".$Spielort->get_str_name(),
                                    $x1+1, 7.2+$i*2, 22, 2, 1);
        } else {
          $my_form->input_t2_button($form_id, "matchday_".$arr_matchdays[0][$i],
                                    $arr_matchdays[1][$i].", ".$Spielort->get_str_name(),
                                    $x1+1, 7.2+$i*2, 22, 2, 1);
        }
      }

      //$my_form->input_h_list($form_id, "list2", $arr_matchdays[1], $arr_matchdays[0], $x3, 7, -1, -1, 19, 2);
    }
  }

}
echo "\n<br/><center style=\"font-size: 1em;\">\n";
$my_form->echo_form($form_id);
echo "\n</center>";

?>