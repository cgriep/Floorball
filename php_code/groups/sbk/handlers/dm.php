<?php
$bool_show_data = 0;
$bool_show_delete = 0;
// Ligen aus Datenbank laden
$int_id = -1;

for($i=0; $i<18; $i++) {
  $arr_team_id[$i][0] = "0#0";
  $arr_team_id[$i][1] = "0#0";  
  $str_b_date[$i] = "01.01.1970";
  $str_b_time[$i] = "00:00";
}

if(isset($_POST['save'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $bool_show_data = 0;
  $int_id = $_POST['id'];
  $str_l_name = $_POST['l_name'];
  $str_l_kurzname = $_POST['l_kurzname'];
  $int_id_klasse = 0;
  $int_id_spielsystem = $_POST['id_spielsystem'];
  //$str_l_date = $_POST['l_date'];
  //$bool_stich = isset($_POST['stich_min']);
  $bool_weiblich = $_POST['l_weiblich'];
  $int_l_ord = $_POST['l_ordnungsnr'];

  // Liga anlegen
  if($int_id == -1) {
    $int_id_kate = $_POST['id_kate'];
    if($int_id_kate == 100) $dm = true;
    else $dm = false;
    $Liga = new Liga();
    $Liga->set_int_id($int_id);
    $Liga->set_str_name($str_l_name);
    $Liga->set_int_id_klasse($int_id_klasse);
    $Liga->set_int_id_kategorie($int_id_kate);
    $Liga->set_int_id_saison($_SESSION['id_saison']);
    $Liga->set_int_id_spielsystem($int_id_spielsystem);
    //$Liga->set_date_stichtag($str_l_date);
    //$Liga->set_bool_stichtag_typ($bool_stich);
    $Liga->set_str_kurzname($str_l_kurzname);
    $Liga->set_bool_weiblich($bool_weiblich);
    $Liga->set_int_ordnungsnr($int_l_ord);

    // erst Liga anlegen
    if($int_id = DBM_Ligen::write_Liga($Liga))
      $my_message->add_message("Die Liga \"$str_l_name\" wurde erfolgreich angelegt.");
    else
      $my_message->add_error("Datenbankfehler");
    $arr_spieltage = array();
    // dann zwei Spieltage anlegen

    // dazu alle daten lesen
    $k = 0;
    for($i=1; $i<19; $i++) {
      if(!isset($arr_spieltage[$_POST["beg".$i."_date"]])) {
        $arr_spieltage[$_POST["beg".$i."_date"]] = $_POST["beg".$i."_date"];
        $arr_spieltage2[$k] = $_POST["beg".$i."_date"];
        $k++;
      }
    }
    $i=0;
    foreach($arr_spieltage as $key) {
      $Spieltage[$i] = new Spieltag(-1, 0, $int_id,
                                   $key, 1);
      //echo "<br/>".$key;
      $int_id_spieltag[$i] = DBMapper::write_Spieltag($Spieltage[$i]);      
      $i++;
    }
    // dann Begegnungen anlegen
    $l=0;
    if($dm) {
      // bei der dm brauchen wir 18 Begegnungen
      // ersten 12 spiele sind mit gewählten mannschaften
      for($i=1; $i<=12; $i++) {
        $str_date = $_POST["beg".$i."_date"];
        $str_time = $_POST["beg".$i."_time"];
        $id_team1 = $_POST["team1_".$i];
        $id_team2 = $_POST["team2_".$i];
        for($k=0; $k<count($arr_spieltage2); $k++) {
          if($arr_spieltage2[$k] == $str_date) break;
        }
        // $k ist der index für die spieltags_id
        $Begegnung = new Begegnung(-1, $int_id_spieltag[$k], NULL,
                                   NULL, $str_time,
                                   "-", $i);
        $int_insert[$l] = DBMapper::write_Begegnung($Begegnung);
        $l++;
      }
      // dannach nur noch zeit eintragen und spieltag zuordnung machen
      for($i=13; $i<=18; $i++) {
        $str_date = $_POST["beg".$i."_date"];
        $str_time = $_POST["beg".$i."_time"];
        for($k=0; $k<count($arr_spieltage2); $k++) {
          if($arr_spieltage2[$k] == $str_date) break;
        }
        // $k ist der index für die spieltags_id
        $Begegnung = new Begegnung(-1, $int_id_spieltag[$k], NULL,
                                   NULL, $str_time,
                                   "-", $i);
        $int_insert[$l] = DBMapper::write_Begegnung($Begegnung);  
        $l++;
      }
    }
    else {
      // bei der Quali sind es nur 8 Begegnungen
      // es müssen aber Eingaben übersprungen werden (drei pro Gruppe)
      for($i=1; $i<=3; $i++) {
        $str_date = $_POST["beg".$i."_date"];
        $str_time = $_POST["beg".$i."_time"];
        $id_team1 = $_POST["team1_".$i];
        $id_team2 = $_POST["team2_".$i];
        for($k=0; $k<count($arr_spieltage2); $k++) {
          if($arr_spieltage2[$k] == $str_date) break;
        }
        // $k ist der index für die spieltags_id
        $Begegnung = new Begegnung(-1, $int_id_spieltag[$k], NULL,
                                   NULL, $str_time,
                                   "-", $i);
        $int_insert[$l] = DBMapper::write_Begegnung($Begegnung);
        $l++;
      }
      for($i=7; $i<=9; $i++) {
        $str_date = $_POST["beg".$i."_date"];
        $str_time = $_POST["beg".$i."_time"];
        $id_team1 = $_POST["team1_".$i];
        $id_team2 = $_POST["team2_".$i];
        for($k=0; $k<count($arr_spieltage2); $k++) {
          if($arr_spieltage2[$k] == $str_date) break;
        }
        // $k ist der index für die spieltags_id
        $Begegnung = new Begegnung(-1, $int_id_spieltag[$k], NULL,
                                   NULL, $str_time,
                                   "-", $i);
        $int_insert[$l] = DBMapper::write_Begegnung($Begegnung);
        $l++;        
      }
      //13 1. Halbfinale
      //14 2. Halbfinale
      for($i=13; $i<=14; $i++) {
        $str_date = $_POST["beg".$i."_date"];
        $str_time = $_POST["beg".$i."_time"];
        for($k=0; $k<count($arr_spieltage2); $k++) {
          if($arr_spieltage2[$k] == $str_date) break;
        }
        // $k ist der index für die spieltags_id
        $Begegnung = new Begegnung(-1, $int_id_spieltag[$k], NULL,
                                   NULL, $str_time,
                                   "-", $i);
        $int_insert[$l] = DBMapper::write_Begegnung($Begegnung);  
        $l++;
      }
    }
    // nun die dm infos anlegen
    
  }
  // Liga updaten
  else {
    // erstmal die Liga updaten
    $Liga = DBM_Ligen::read_Liga($int_id);
    $Liga->set_str_name($str_l_name);
    $Liga->set_str_kurzname($str_l_kurzname);
    $Liga->set_int_ordnungsnr($int_l_ord);
  
    if(DBM_Ligen::update_Liga($Liga)) {
      $my_message->add_message("Die Liga \"$str_l_name\" wurde erfolgreich bearbeitet.");
    }
    else
      $my_message->add_error("Datenbankfehler");

    // dann die uhrzeiten und die team_ids
    for($i=1; $i<=18; $i++)
      $arr_time[$i-1] = $_POST["beg".$i."_time"];

    // ähnliches vorgehen wie beim laden der liga
    
    // alle spieltage der liga holen
    $arr_spieltage = DBMapper::get_list_Spieltag($int_id);
    for($i=0; $i<count($arr_spieltage[0]); $i++) {
      // alle begegnungen zu den spieltagen holen
      $arr_games = DBMapper::get_list_Begegnung($arr_spieltage[0][$i]);
      for($l=0; $l<count($arr_games[0]); $l++) {
        // 5 = spielnummer
        $index = $arr_games[5][$l]-1;
        $Begegnung = DBMapper::read_Begegnung($arr_games[0][$l]);
        $Begegnung->set_str_uhrzeit($arr_time[$index]);

        // die team id's noch richtig holen
        if($index < 12) {
          $arr_t1 = explode("#", $_POST["team1_".($index+1)]);
          $Begegnung->set_int_id_mannschaft1($arr_t1[0], $arr_t1[1]);

          $arr_t2 = explode("#", $_POST["team2_".($index+1)]);
          $Begegnung->set_int_id_mannschaft2($arr_t2[0], $arr_t2[1]);
        }
        if(DBMapper::update_Begegnung($Begegnung)) {
          $my_message->add_message("Das Spiel \"".($index+1)."\" wurde erfolgreich bearbeitet.");
        }
        else
          $my_message->add_error("Datenbankfehler");
      }
    }    
  }
}

if(isset($_POST['delete'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $bool_show_data = 0;
  $bool_show_delete = 1;
  $int_id = $_POST['id'];
  $str_l_name = $_POST['l_name'];
  $str_l_kurzname = $_POST['l_kurzname'];
  $int_id_klasse = $_POST['id_klasse'];
  $int_id_kate = $_POST['id_kate'];
  $int_id_spielsystem = $_POST['id_spielsystem'];
  //$str_l_date = $_POST['l_date'];
  //$bool_stich = isset($_POST['stich_min']);
  $bool_weiblich = isset($_POST['l_weiblich']);
}
if(isset($_POST['delete_ok'])) {
  $result = DBM_Ligen::delete_Liga($_POST['id']);
  if($result) {
    $my_message->add_message("Die Liga \"".$_POST['l_name']."\" wurde erfolgreich gelöscht.");
  }
  else {
    $my_message->add_error("Die Liga \"".$_POST['l_name']."\" konnte nicht gelöscht werden.");
  }
}
$arr_ligen[0][0] = -1;
$arr_ligen[1][0] = "Neue Liga anlegen";
$arr_ligen[0][1] = 0;
$arr_ligen[1][1] = "";

$arr_tmp = DBM_Ligen::get_list_Liga();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_ligen[0][$i+2] = $arr_tmp[0][$i];
    $arr_ligen[1][$i+2] = $arr_tmp[1][$i];
}

//$arr_kate = DBMapper::get_list_Kategorie();
$arr_kate[0][0] = 100;
$arr_kate[1][0] = "DM 2x4";
$arr_kate[0][1] = 101;
$arr_kate[1][1] = "DM Quali 2x3";

$arr_klasse = DBMapper::get_list_Klasse();

$arr_spielsysteme = DBM_Ligen::get_list_spielsystem();

//foreach ($_POST as $key=>$value) {
//if(substr($key, 0, 5) == "list1") {
if(isset($_POST['load'])) {
  if($_POST['list1'] == -1) {
    $bool_show_data = 1;
    $int_id = -1;
    $str_l_name = "";
    $int_id_klasse = 1;
    $int_id_kate = 100;
    $str_l_kurzname = "";
    //$str_l_date = "";
    $int_id_spielsystem = 0;
    //$bool_stich = 1;
    $bool_weiblich = 0;
    $int_l_ord = 0;
  }
  else if($_POST['list1'] > 0){
    // load from db
    $int_id = $_POST['list1'];
    $Liga = DBM_Ligen::read_Liga($int_id);
    if($Liga) {
      $bool_show_data = 1;
      $str_l_name = $Liga->get_str_name();
      $int_id_klasse = $Liga->get_int_id_klasse();
      $int_id_kate = $Liga->get_int_id_kategorie();
      //$str_l_date = $Liga->get_date_stichtag();
      $str_l_kurzname = $Liga->get_str_kurzname();
      $int_id_spielsystem = $Liga->get_int_id_spielsystem();
      //$bool_stich = $Liga->get_bool_stichtag_typ();
      $bool_weiblich = $Liga->get_bool_weiblich();
      $int_l_ord = $Liga->get_int_ordnungsnr();
    }
    // alle spieltage der liga holen
    $arr_spieltage = DBMapper::get_list_Spieltag($int_id);
    for($i=0; $i<count($arr_spieltage[0]); $i++) {
      // alle begegnungen zu den spieltagen holen
      $arr_games = DBMapper::get_list_Begegnung($arr_spieltage[0][$i]);
      for($l=0; $l<count($arr_games[0]); $l++) {
        // anhand der spielnummer die daten, uhrzeiten und team_ids zuweisen
        // 1 = id_team1
        // 2 = id_team2
        // 3 = uhrzeit
        // 5 = spielnummer
        $index = $arr_games[5][$l]-1;
        $str_b_date[$index] = $arr_spieltage[1][$i];
        $str_b_time[$index] = $arr_games[3][$l];
        $arr_team_id[$index][0] = $arr_games[1][$l]."#".$arr_games[7][$l];
        $arr_team_id[$index][1] = $arr_games[2][$l]."#".$arr_games[8][$l];
      }
    }    
  }
}


if(isset($_POST['read_file'])) {
  $str_file = $_FILES['l_file']['tmp_name'];//$_POST['l_file'];
  $int_id = $_POST['id'];
  LizenzInterface::read_from_file($str_file, $int_id);
}

if(isset($_POST['read_ad_file'])) {
  $str_file = $_FILES['ad_file']['tmp_name'];//$_POST['l_file'];
  LizenzInterface::update_address_from_file($str_file);
}

if(isset($_POST['read_ad_pl_file'])) {
  $str_file = $_FILES['ad_pl_file']['tmp_name'];//$_POST['l_file'];
  LizenzInterface::read_player_from_file($str_file);
}

if(isset($_POST['read_ad_nation_file'])) {
  $str_file = $_FILES['ad_nation_file']['tmp_name'];//$_POST['l_file'];
  LizenzInterface::update_nation_from_file($str_file);
}
?>
