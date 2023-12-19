<?php
$bool_show_data = 0;
$bool_show_delete = 0;

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neuen Verein anlegen") {
      $bool_show_data = 1;
      $int_id = -1;
      $str_name = "";
      $str_k_name = "";
      $str_street = "";
      $str_nbr = "";
      $str_plz = "";
      $str_place = "";
      $str_hp_club = "";
      $str_hp_part = "";
      $int_id_leiter = 0;
    }
    else {
      // load from db
      $int_id = trim(substr($key,6));
      $Verein = DBMapper::read_Verein($int_id);
      if($Verein) {
        $bool_show_data = 1;
        $str_name = $Verein->get_str_name();
        $str_k_name = $Verein->get_str_kurzname();
        $str_street = $Verein->get_str_strasse();
        $str_nbr = $Verein->get_str_hausnummer();
        $str_plz = $Verein->get_str_plz();
        $str_place = $Verein->get_str_ort();
        $str_hp_club = $Verein->get_str_homepage_verein();
        $str_hp_part = $Verein->get_str_homepage_sparte();
        $int_id_leiter = $Verein->get_int_id_spartenleiter();
      }
    }
  }
}

if(isset($_POST['save'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $int_id = $_POST['id'];
  if($int_id != -1) $bool_show_data = 1;
  else $bool_show_data = 0;
  $str_name = $_POST['name'];
  $str_k_name = $_POST['k_name'];
  $str_street = $_POST['street'];
  $str_nbr = $_POST['s_nbr'];
  $str_plz = $_POST['plz'];
  $str_place = $_POST['place'];
  $str_hp_club = $_POST['hp_club'];
  $str_hp_part = $_POST['hp_part'];
  $int_id_leiter = $_POST['id_leiter'];
  if($str_name != "" && $str_k_name != "") {
    $verein = new Verein($int_id, $int_id_leiter, $str_name, $str_k_name,
                         $str_street, $str_nbr, $str_plz, $str_place,
                         $str_hp_club, $str_hp_part);
    if($int_id == -1) {
      $int_insert = DBMapper::write_Verein($verein);
      if($int_insert) {
        $int_id = $int_insert;
        $my_message->add_message("Der Verein \"".$str_name."\" wurde erfolgreich angelegt");
      }
    }
    else {
      if(DBMapper::update_Verein($verein))
        $my_message->add_message("Der Verein \"$str_name\" wurde erfolgreich aktualisiert.");
      else
        $my_message->add_error("Datenbankfehler");
    }
    if($int_id > 0 && $int_id_leiter > 0) {
      $person = DBMapper::read_Person($int_id_leiter);
      $id_verein = $person->get_int_id_verein();
      if($id_verein == 0) {
        $person->set_int_id_verein($int_id);
        DBMapper::update_Person($person);
      }
    }
  }
  else {
    $my_message->add_error("Die Felder \"Vereinsname\" und \"Kurzname\" dürfen nicht leer sein um einen Verein anzulegen oder zu ändern.");
    $bool_show_data = 1;
  }
}

if(isset($_POST['delete'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $bool_show_data = 0;
  $bool_show_delete = 1;
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
}
if(isset($_POST['delete_ok'])) {
  if(DBMapper::delete_Verein($_POST['id'])) {
    $my_message->add_message("Der Verein \"".$_POST['name']."\" wurde erfolgreich gelöscht.");
  }
  else {
    $my_message->add_error("Der Verein \"".$_POST['name']."\" konnte nicht gelöscht werden. Wahrscheinlich existiert eine Mannschaft in einem anderen Verband.");
  }
}

if(isset($_POST['freigabe'])) {
  $bool_show_data = 1;
  $bool_show_delete = 0;
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
  $int_id_verband = $_POST['id_verband'];
  if(!DBMapper::write_Verein_Verband($int_id, $int_id_verband)) {
    $my_message->add_error("MySQL-Error: Verein konnte dem Verband nicht freigegeben werden.");
  }
}

if(isset($_POST['refreigabe'])) {
  $bool_show_data = 1;
  $bool_show_delete = 0;
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
  $int_id_gastverband = $_POST['id_gastverband'];
  // testen ob Freigabe zurückgenommen werden darf
  $test = DBMapper::check_Gastverband_Mannschaft($int_id, $int_id_gastverband);
  if(!$test) {
    if(!DBMapper::delete_Verein_Verband($int_id, $int_id_gastverband)) {
      $my_message->add_error("MySQL-Error: Verein konnte dem Verband nicht freigegeben werden.");
    }
  }
  else {
    $my_message->add_error("Die Freigabe kann nicht zurück genommen werden, da bereits Mannschaften in dem anderem Verband angelegt sind.");
  }
}

if(isset($_POST['next'])) {
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

    switch($_POST['next']) {
        case 'Neue Person anlegen':
            $nav = "persons";
            break;
        case 'Mannschaften bearbeiten':
            $nav = "teams";
            break;
        case 'Spieler bearbeiten':
            $nav = "player";
            break;
    }
}

?>
