<?php

error_reporting(E_ALL);

/**
 * VorlŠufige Schnittstelle zum Lizenzmanager.
 *
 * @author Michael Rohn & Malte Ršmmermann
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * Liest eine Lieste von Lizenzen erstellt aus dem Lizenzmanager des NUBs.
 *
 * @access public
 * @author Michael Rohn & Malte Ršmmerann
 */
class LizenzInterface {

    public function read_from_file($str_file, $int_id_liga) {
      $arr_file = file($str_file);
      for($i=0; $i<count($arr_file); $i++) {
        $arr_line = utf8_encode($arr_file[$i]);
        $arr_lizenz = explode(",", $arr_line);
        if(count($arr_lizenz) > 3) {
          $p_v_name = trim($arr_lizenz[0]);
          $p_n_name = trim($arr_lizenz[1]);
          $p_date = trim($arr_lizenz[2]);
          $p_club = trim($arr_lizenz[6]);
          $p_team = trim($arr_lizenz[7]);
          //echo "$p_v_name    $p_n_name    $p_date<br/>";
          // check if club exists
          $int_id_club = 0;
          $arr_list_clubs = DBMapper::get_list_Verein();
          for($k=0; $k<count($arr_list_clubs[0]); $k++) {
            if($arr_list_clubs[2][$k] == $p_club) {
              $int_id_club = $arr_list_clubs[0][$k];
              break;
            }
          }
          //echo "$int_id_club";
          if(!$int_id_club) {
            // create club
            $Verein = new Verein(0, 0, $p_club, $p_club);
            $int_id_club = DBMapper::write_Verein($Verein);
          }
          // check if team exists
          $int_id_team = 0;
          $arr_list_teams = DBMapper::get_list_Mannschaft($int_id_club);
          if(count($arr_list_teams) > 0)
            for($k=0; $k<count($arr_list_teams[0]); $k++) {
              if($arr_list_teams[1][$k] == $p_team) {
                $int_id_team = $arr_list_teams[0][$k];
                break;
              }
            }
          if(!$int_id_team) {
            // create new team of club
            $Mannschaft = new Mannschaft(0, $int_id_club, $int_id_liga, 0, $p_team, 1);
            $int_id_team = DBMapper::write_Mannschaft($Mannschaft);
          }
          // check for player
          $int_id_player = 0;
          $arr_list_player = DBM_Spieler::get_list_Spieler();
          for($k=0; $k<count($arr_list_player[0]); $k++) {
            $Spieler = DBM_Spieler::read_Spieler($arr_list_player[0][$k]);
            if($Spieler->get_str_vorname() == $p_v_name &&
               $Spieler->get_str_name() == $p_n_name &&
               $Spieler->get_date_geb_datum(TRUE) == $p_date) {
              $int_id_player = $Spieler->get_int_id();
              break;
            }
          }
          if(!$int_id_player) {
            $Spieler = new Spieler(0, $p_v_name, $p_n_name, -1, $p_date);
            $int_id_player = DBM_Spieler::write_Spieler($Spieler);
            DBM_Spieler::set_Spieler_of_Verein($int_id_player, $int_id_club);
          }
          // now we have everything to create the lizenz
          DBMapper::write_Lizenz($int_id_player, $int_id_team);
        }
      }
    }

    public function update_nation_from_file($str_file) {
      $arr_file = file($str_file);
      $arr_list_player = DBM_Spieler::get_list_Spieler();
      $arr_nation = DBM_Spieler::get_list_nation();

      for($i=0; $i<count($arr_file); $i++) {
        $arr_line = utf8_encode($arr_file[$i]);
        $arr_lizenz = explode(",", $arr_line);
        if(count($arr_lizenz) != 9) {
          echo "Ziele ".$i." fehlerhaft.<br/>";
        }
        else {
          $p_v_name = trim($arr_lizenz[0]);
          $p_n_name = trim($arr_lizenz[1]);
          $p_date = trim($arr_lizenz[2]);
          $p_club = trim($arr_lizenz[6]);
          $p_team = trim($arr_lizenz[7]);
          $p_street = trim($arr_lizenz[3]);
          $p_plz = trim($arr_lizenz[4]);
          $p_place = trim($arr_lizenz[5]);
	  $p_nation = trim($arr_lizenz[8]);
          $arr_address = explode(" ", $p_street);
          $n = 0;
          $str_street = "";
          $str_hnbr = "";
          while($n < count($arr_address) &&
                !self::test_int(substr($arr_address[$n], 0, 1))) {
            $str_street .= " ".$arr_address[$n++];
          }
          while($n < count($arr_address)) $str_hnbr .= " ".$arr_address[$n++];
          $str_street = trim($str_street);
          $str_hnbr = trim($str_hnbr);

          //echo "$p_v_name    $p_n_name    $p_date<br/>";

          // check for player
          $int_id_player = 0;
          for($k=0; $k<count($arr_list_player[0]); $k++) {
	    $Spieler = DBM_Spieler::read_Spieler($arr_list_player[0][$k]);
            if($Spieler->get_str_vorname() == $p_v_name &&
	       $Spieler->get_str_name() == $p_n_name &&
	       $Spieler->get_date_geb_datum(TRUE) == $p_date
	       ) {
	      $int_id_player = $Spieler->get_int_id();
	      break;
            }
          }
          if(!$int_id_player) {
/*
            $Spieler = new Spieler(0, $p_v_name, $p_n_name, -1, $p_date,
                                   $str_street, $str_hnbr, $p_plz, $p_place);
            $int_id_player = DBMapper::write_Spieler($Spieler);
            DBMapper::set_Spieler_of_Verein($int_id_player, $int_id_club);
*/
  echo "Spieler aus Zeile ".$i." nicht correct in der Datenbank.<br/>";
          }
          // now we have everything to create the lizenz
          else {
	    $int_id_nation = 0;
	    for($k=0; $k<count($arr_nation[0]); $k++) {
	      if($p_nation == $arr_nation[1][$k]) {
		$int_id_nation = $arr_nation[0][$k];
		break;
	      }
	    }
	    echo $int_id_nation.";";
            $Spieler->set_int_id_nation($int_id_nation);
            DBM_Spieler::update_Spieler($Spieler);
          }
        }
      }
    }

    public function update_address_from_file($str_file) {
      $arr_file = file($str_file);
      for($i=0; $i<count($arr_file); $i++) {
        $arr_line = utf8_encode($arr_file[$i]);
        $arr_lizenz = explode(",", $arr_line);
        if(count($arr_lizenz) != 9) {
          echo "Ziele ".$i." fehlerhaft.<br/>";
        }
        else {
          $p_v_name = trim($arr_lizenz[0]);
          $p_n_name = trim($arr_lizenz[1]);
          $p_date = trim($arr_lizenz[2]);
          $p_club = trim($arr_lizenz[6]);
          $p_team = trim($arr_lizenz[7]);
          $p_street = trim($arr_lizenz[3]);
          $p_plz = trim($arr_lizenz[4]);
          $p_place = trim($arr_lizenz[5]);
          $arr_address = explode(" ", $p_street);
          $n = 0;
          $str_street = "";
          $str_hnbr = "";
          while($n < count($arr_address) &&
                !self::test_int(substr($arr_address[$n], 0, 1))) {
            $str_street .= " ".$arr_address[$n++];
          }
          while($n < count($arr_address)) $str_hnbr .= " ".$arr_address[$n++];
          $str_street = trim($str_street);
          $str_hnbr = trim($str_hnbr);

          //echo "$p_v_name    $p_n_name    $p_date<br/>";

          // check for player
          $int_id_player = 0;
          $arr_list_player = DBM_Spieler::get_list_Spieler();
          for($k=0; $k<count($arr_list_player[0]); $k++) {
            $Spieler = DBM_Spieler::read_Spieler($arr_list_player[0][$k]);
            if($Spieler->get_str_vorname() == $p_v_name &&
               $Spieler->get_str_name() == $p_n_name &&
               $Spieler->get_date_geb_datum(TRUE) == $p_date) {
              $int_id_player = $Spieler->get_int_id();
              break;
            }
          }
          if(!$int_id_player) {
/*
            $Spieler = new Spieler(0, $p_v_name, $p_n_name, -1, $p_date,
                                   $str_street, $str_hnbr, $p_plz, $p_place);
            $int_id_player = DBMapper::write_Spieler($Spieler);
            DBMapper::set_Spieler_of_Verein($int_id_player, $int_id_club);
*/
  echo "Spieler aus Zeile ".$i." nicht correct in der Datenbank.<br/>";
          }
          // now we have everything to create the lizenz
          else {
            $Spieler->set_str_strasse($str_street);
            $Spieler->set_str_hausnummer($str_hnbr);
            $Spieler->set_str_plz($p_plz);
            $Spieler->set_str_ort($p_place);
            DBM_Spieler::update_Spieler($Spieler);
          }
        }
      }
    }

    public function read_player_from_file($str_file) {
      $arr_file = file($str_file);
      $arr_list_player = DBM_Spieler::get_list_Spieler();
      for($i=0; $i<count($arr_file); $i++) {
        $arr_line = utf8_encode($arr_file[$i]);
        $arr_lizenz = explode(",", $arr_line);
        if(count($arr_lizenz) > 3) {
          $p_v_name = trim($arr_lizenz[0]);
          $p_n_name = trim($arr_lizenz[1]);
          $p_date = trim($arr_lizenz[2]);
          $p_club = trim($arr_lizenz[6]);
          $p_team = trim($arr_lizenz[7]);
          $p_street = trim($arr_lizenz[3]);
          $p_plz = trim($arr_lizenz[4]);
          $p_place = trim($arr_lizenz[5]);
          $arr_address = explode(" ", $p_street);
          $n = 0;
          $str_street = "";
          $str_hnbr = "";
          while($n < count($arr_address) &&
                !self::test_int(substr($arr_address[$n], 0, 1))) {
            $str_street .= " ".$arr_address[$n++];
          }
          while($n < count($arr_address)) $str_hnbr .= " ".$arr_address[$n++];
          $str_street = trim($str_street);
          $str_hnbr = trim($str_hnbr);
          //echo "$p_v_name    $p_n_name    $p_date<br/>";
          // check if club exists
          $int_id_club = 0;
          $arr_list_clubs = DBMapper::get_list_Verein();
          for($k=0; $k<count($arr_list_clubs[0]); $k++) {
            if($arr_list_clubs[2][$k] == $p_club) {
              $int_id_club = $arr_list_clubs[0][$k];
              break;
            }
          }
          //echo "$int_id_club";
          if(!$int_id_club) {
            echo "fehler Mannschaft nich vorhanden<br/>";
            // create club
            //$Verein = new Verein(0, 0, $p_club, $p_club);
            //$int_id_club = DBMapper::write_Verein($Verein);
          }
          // check if team exists
          $int_id_team = 0;
          // check for player
          $int_id_player = 0;
          for($k=0; $k<count($arr_list_player[0]); $k++) {
            $Spieler = DBM_Spieler::read_Spieler($arr_list_player[0][$k]);
            if($Spieler->get_str_vorname() == $p_v_name &&
               $Spieler->get_str_name() == $p_n_name &&
               $Spieler->get_date_geb_datum(TRUE) == $p_date) {
              $int_id_player = $Spieler->get_int_id();
	      echo "fehler Spieler schon vorhanden<br/>";
              break;
            }
          }
          if(!$int_id_player) {
	    echo "<br/>".$p_date."<br/>";
            $Spieler = new Spieler(0, $p_v_name, $p_n_name, -1, $p_date,
				   $str_street, $str_hnbr, $p_plz, $p_place);
            $int_id_player = DBM_Spieler::write_Spieler($Spieler);
            DBM_Spieler::set_Spieler_of_Verein($int_id_player, $int_id_club);
          }
        }
      }
    }

    public function test_int($char) {
      if($char == "0" ||
         $char == "1" ||
         $char == "2" ||
         $char == "3" ||
         $char == "4" ||
         $char == "5" ||
         $char == "6" ||
         $char == "7" ||
         $char == "8" ||
         $char == "9") return TRUE;
      return FALSE;
    }
}
?>