<?php
/**
 * Logik für ...
 *
 * @package    VM
 * @subpackage Handles
 */
$arr_leagues = DBM_Ligen::get_list_Liga();
$arr_status = DBMapper::get_list_status_lizenz();
$bool_show_list = 0;

if(isset($_POST['show'])) {
  // load from db
  $int_l_id_liga = $_POST['l_id_liga'];
  $Liga = DBM_Ligen::read_Liga($int_l_id_liga);
  $str_liga = "";
  for($i=0; $i<count($arr_leagues[0]); $i++) {
    if($int_l_id_liga == $arr_leagues[0][$i]) {
      $str_liga = $arr_leagues[1][$i];
      break;
    }
  }
  $arr_licenses[0] = array();
  $arr_licenses[1] = array();
  $arr_licenses[2] = array();
  $arr_licenses[3] = array();
  $arr_licenses[4] = array();
  $arr_licenses[5] = array();
  $arr_licenses[6] = array();
  $bool_show_list = 1;
  $id_kategorie = $Liga->get_int_id_kategorie();
  if($id_kategorie == 3 || $id_kategorie == 4 || $id_kategorie == 100 || $id_kategorie == 101)
    $arr_teams = DBMapper::get_list_Mannschaft_of_Pokal($int_l_id_liga,
                                                   DBMapper::get_id_Verband());
  else
    $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_l_id_liga, 1);
  if($arr_teams) {
    for($i=0; $i<count($arr_teams[0]); $i++) {
      $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($arr_teams[0][$i], 0, $arr_teams[2][$i]);
      if($arr_player) {
        for($k=0; $k<count($arr_player[0]); $k++) {
          $tmp_player = DBM_Spieler::read_Spieler($arr_player[0][$k]);
          $id_lizenzstatus = DBM_Spieler::get_last_Spieler_Lizenzstatus($arr_player[0][$k], $arr_teams[0][$i], $arr_teams[2][$i]);
          if($id_lizenzstatus[0] < 6) {
            $arr_licenses[0][] = $arr_teams[1][$i];
            $arr_licenses[1][] = $arr_player[1][$k].", ".$arr_player[2][$k];
            $str_status = "fehler";
            for($n=0; $n<count($arr_status[0]); $n++) {
              if($id_lizenzstatus[0] == $arr_status[0][$n]) {
                $str_status = $arr_status[1][$n];
                break;
              }
            }
            $arr_licenses[2][] = $str_status;
            $arr_licenses[3][] = $tmp_player->get_date_geb_datum();
            $arr_licenses[4][] = $tmp_player->get_str_nation();
            $arr_licenses[5][] = $arr_player[5][$k];
            
            $doppel = DBMapper::get_Doppel_nr($arr_player[0][$k],
                                              $arr_teams[0][$i],
                                              $arr_teams[2][$i]);
            if($doppel) {
            	// neu 4.10.2011 - Anzeige ob Heimatverein oder Zweitlizenz statt Ordnungsnummer
            	$arr_list_vereine = DBM_Spieler::get_Verein_by_Spieler($arr_player[0][$k]);
            	for ($n=0; $n<count($arr_list_vereine[0]); $n++ )
            	{
            		if ( $arr_list_vereine[0][$n] == $arr_teams[3][$i] )
            		{
            			// Erstverein
            			if ($arr_list_vereine[1][$n])
            			{
            				$arr_licenses[6][] = " (Erstverein)";			
            			}
            			else 
            			{
            				$arr_licenses[6][] = " (Zweitlizenz)";
            			}
            			
            		}
            	}
            	if ( count($arr_licenses[6]) < count($arr_licenses[5]) )
            	{
            		// Fehler - Anzahl stimmt nicht
            		$arr_licenses[6][] = " (".$doppel.")";
            	}
            }
            else $arr_licenses[6][] = "";
          }
        }
      }
    }
  }
}

?>
