<?php
$bool_show_data = 0;
$bool_show_delete = 0;
$bool_show_player = 0;

$int_id = $_POST['id'];
$str_name = $_POST['name'];
$str_k_name = $_POST['k_name'];
$str_street = $_POST['street'];
$str_nbr = $_POST['s_nbr'];
$str_plz = $_POST['plz'];
$str_place = $_POST['place'];
$str_hp_club = $_POST['hp_club'];
$str_hp_part = $_POST['hp_part'];
$int_id_leiter = $_POST['id_leiter'];
$arr_player[0] = array();
$arr_player[1] = array();
$load = 0;

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    $load = 1;
    if($value == "Neue Mannschaft anlegen") {
      $bool_show_data = 1;
      $bool_show_delete = 0;
      $int_t_id = -1;
      $str_t_name = "";
      $str_t_kurzname = "";
      $int_t_id_leiter = 0;
      $int_t_liga = 0;
      $bool_t_sg = 0;
      $arr_player[0] = array();
      $arr_player[1] = array();
    }
    else {
      // load from db
      $int_t_id = trim(substr($key,6));
      $Mannschaft = DBMapper::read_Mannschaft($int_t_id);
      if($Mannschaft) {
        $bool_show_data = 1;
        $bool_show_delete = 0;
        $str_t_name = $Mannschaft->get_str_name();
        $str_t_kurzname = $Mannschaft->get_str_kurzname();
        $int_t_id_leiter = $Mannschaft->get_int_id_betreuer();
        $int_t_liga = $Mannschaft->get_int_id_liga();
        $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id);
        $bool_t_sg = $Mannschaft->get_bool_sg();
        if(!$arr_player) {
          $arr_player[0] = array();
          $arr_player[1] = array();
        }
      }
    }
  }
}

if(!$load) {
  foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 6) == "p_club") {
      if(!isset($_POST["p_del_".substr($key, 7)])) {
        $bool_show_data = 1;
        $bool_show_delete = 0;
        $arr_player[0][] = substr($key, 7);
        $arr_player[1][] = $value;
      }
      $load = 1;
    }
    if(substr($key, 0, 5) == "p_del") $load = 1;
  }

  if(isset($_POST['next'])) {
    $nav = "persons";
    $load = 1;
    $old_nav = 1;
  }

  if($load) {
    $int_t_id = $_POST['t_id'];
    $str_t_name = $_POST['t_name'];
    $str_t_kurzname = $_POST['t_kurzname'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    $bool_t_sg = isset($_POST['t_sg']);
  }

  if(isset($_POST['save'])) {
    $int_t_id = $_POST['t_id'];
    if($int_t_id > 0) $bool_show_data = 1;
    else $bool_show_data = 0;
    $bool_show_delete = 0;
    $str_t_name = $_POST['t_name'];
    $str_t_kurzname = $_POST['t_kurzname'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    //var_dump($_POST);
    $bool_t_sg = isset($_POST['t_sg']);
    if($str_t_name != "") {
      $Mannschaft = new Mannschaft($int_t_id, $int_id, $int_t_liga,
                                   $int_t_id_leiter, $str_t_name, 
                                   $str_t_kurzname, 1,
                                   0, 0, $bool_t_sg);
      if($int_t_id == -1) {
        $int_t_id = DBMapper::write_Mannschaft($Mannschaft);
        // handle sg für mannschaft
        if($bool_t_sg) {
          // ersten Link in die SG Tabelle einfügen
          DBMapper::write_SG($int_id, $int_t_id);
        }
      }
      else {
        // handle sg für mannschaft
        if($bool_t_sg) {
          if(DBMapper::get_count_SG_of_Mannschaft($int_t_id) == 0) {
            DBMapper::write_SG($int_id, $int_t_id);
          }
        }
        else {
          $count_sg = DBMapper::get_count_SG_of_Mannschaft($int_t_id);
          if($count_sg > 1) {
            $Mannschaft->set_bool_sg(0);
            $my_message->add_error("Es bestehen Verknüpfungen anderer Vereine, daher muss die Mannschaft eine Spielgemeinschaft bleiben.");
          }
          else if($count_sg == 1) {
            // sollte Eintrag von Verein und Mannschaft sein 
            DBMapper::delete_Verein_SG($int_t_id, $int_id);
            $count_sg = DBMapper::get_count_SG_of_Mannschaft($int_t_id);
            if($count_sg) {
              $my_message->add_error("Es ist ein Fehler in der Datenbank aufgetreten in Zusammenhang mit Spielgemeinschaften. Bitte die Fehlermeldung an die System-Admins weitergeben.");
            }
          }
        }
        $tmp_count_lizenz = DBMapper::get_count_Lizenz($int_t_id);
        if($tmp_count_lizenz > 0) {
          $Mannschaft_old = DBMapper::read_Mannschaft($int_t_id);
          $Mannschaft->set_int_id_liga($Mannschaft_old->get_int_id_liga());
        }
        DBMapper::update_Mannschaft($Mannschaft);    
        
      }
      // update Person
      if($int_t_id > 0 && $int_t_id_leiter > 0) {
        $person = DBMapper::read_Person($int_t_id_leiter);
        $id_verein = $person->get_int_id_verein();
        if($id_verein == 0) {
          $person->set_int_id_verein($int_id);
          DBMapper::update_Person($person);
        }
      }
      
      
      // get license of team and delete only the  right ones
      $arr_old_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_t_id, 0);
      for($i=0; $i<count($arr_old_player[0]); $i++) {
        $del = true;
        for($k=0; $k<count($arr_player[0]); $k++) {
          if($arr_old_player[0][$i] == $arr_player[0][$k]) {
            $del = false;
            break;
          }
        }
        if($del) DBMapper::del_Lizenz($arr_old_player[0][$i], $int_t_id);
        //DBMapper::del_Lizenz_for_Mannschaft($int_t_id);
      }
      for($i=0; $i<count($arr_player[0]); $i++) {
        $write = true;
        for($k=0; $k<count($arr_old_player[0]); $k++) {
          if($arr_player[0][$i] == $arr_old_player[0][$k]) {
            $write = false;
            break;
          }
        }
        if($write) DBMapper::write_Lizenz($arr_player[0][$i], $int_t_id, 2);
      }
    }
    else {
      $my_message->add_error("Das Feld \"Bezeichnung\" darf nicht leer sein um eine Mannschaft anzulegen oder zu ändern.");
    }
  }
  else if(isset($_POST['delete'])) {
    $bool_show_data = 0;
    $bool_show_delete = 1;
    $int_t_id = $_POST['t_id'];
    $str_t_name = $_POST['t_name'];
    $str_t_kurzname = $_POST['t_kurzname'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    //var_dump($_POST);
    $bool_t_sg = isset($_POST['t_sg']);

  }
  else if(isset($_POST['delete_ok'])) {
    if(DBMapper::delete_Mannschaft($_POST['t_id'])) {
      $my_message->add_message("Die Mannschaft \"".$_POST['t_name']."\" wurde erfolgreich gelöscht.");
    }
    else {
      $my_message->add_error("Die Mannschaft \"".$_POST['t_name']."\" konnte nicht gelöscht werden.");
    }
  }
  else if(isset($_POST['add_pokal'])) {
    $bool_show_data = 1;
    $bool_show_delete = 0;
    $int_t_id = $_POST['t_id'];
    $str_t_name = $_POST['t_name'];
    $str_t_kurzname = $_POST['t_kurzname'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    //var_dump($_POST);
    $bool_t_sg = isset($_POST['t_sg']);

    $id_verband_mannschaft = DBMapper::get_id_Verband();
    $arr_tmp = explode("#", $_POST['t_add_pokal_id']);
    if(count($arr_tmp) && $arr_tmp[0] > 0 && $arr_tmp[1] > 0) {
      DBMapper::add_Mannschaft_to_Pokal($arr_tmp[0], $arr_tmp[1],
                                        $int_t_id, $id_verband_mannschaft);
    }
  }
  else if(isset($_POST['del_pokal'])) {
    $bool_show_data = 1;
    $bool_show_delete = 0;
    $int_t_id = $_POST['t_id'];
    $str_t_name = $_POST['t_name'];
    $str_t_kurzname = $_POST['t_kurzname'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    //var_dump($_POST);
    $bool_t_sg = isset($_POST['t_sg']);

    $id_verband_mannschaft = DBMapper::get_id_Verband();
    $arr_tmp = explode("#", $_POST['t_del_pokal_id']);
    DBMapper::del_Mannschaft_from_Pokal($arr_tmp[0], $arr_tmp[1],
                                        $int_t_id, $id_verband_mannschaft);
  }

}
if(isset($_POST['back'])) {
  $bool_show_data = 1;
  $bool_show_delete = 0;
  $nav = "clubs";
}

?>
