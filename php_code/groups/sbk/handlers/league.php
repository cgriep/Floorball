<?php
$bool_show_data = 0;
$bool_show_delete = 0;
// Ligen aus Datenbank laden

if(isset($_POST['save'])) {
  // unterscheiden zwischen person speichern und verein speichern
  $bool_show_data = 0;
  $int_id = $_POST['id'];
  $str_l_name = $_POST['l_name'];
  $str_l_kurzname = $_POST['l_kurzname'];
  $int_id_klasse = $_POST['id_klasse'];
  $int_id_kate = $_POST['id_kate'];
  $int_id_spielsystem = $_POST['id_spielsystem'];
  $str_l_date = $_POST['l_date'];
  $bool_stich = isset($_POST['stich_min']);
  $bool_weiblich = $_POST['l_weiblich'];
  $int_l_ord = $_POST['l_ordnungsnr'];
  $Liga = new Liga();
  $Liga->set_int_id($int_id);
  $Liga->set_str_name($str_l_name);
  $Liga->set_int_id_klasse($int_id_klasse);
  $Liga->set_int_id_kategorie($int_id_kate);
  $Liga->set_int_id_saison($_SESSION['id_saison']);
  $Liga->set_int_id_spielsystem($int_id_spielsystem);
  $Liga->set_date_stichtag($str_l_date);
  $Liga->set_bool_stichtag_typ($bool_stich);
  $Liga->set_str_kurzname($str_l_kurzname);
  $Liga->set_bool_weiblich($bool_weiblich);
  $Liga->set_int_ordnungsnr($int_l_ord);

  if($int_id == -1) {
    if(DBM_Ligen::write_Liga($Liga))
      $my_message->add_message("Die Liga \"$str_l_name\" wurde erfolgreich angelegt.");
    else
      $my_message->add_error("Datenbankfehler");
  }
  else {
    $count_lizenz = DBMapper::get_count_Lizenz_of_Liga($int_id);
    if($count_lizenz > 0) {
      $Liga_old = DBM_Ligen::read_Liga($int_id);
      $Liga->set_int_id_klasse($Liga_old->get_int_id_klasse());
      $Liga->set_int_id_kategorie($Liga_old->get_int_id_kategorie());
      $Liga->set_bool_weiblich($Liga_old->get_bool_weiblich());
    }
    if(DBM_Ligen::update_Liga($Liga)) {
      $my_message->add_message("Die Liga \"$str_l_name\" wurde erfolgreich bearbeitet.");
    }
    else
      $my_message->add_error("Datenbankfehler");
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
  $str_l_date = $_POST['l_date'];
  $bool_stich = isset($_POST['stich_min']);
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
$arr_ligen[0][0] = 0;
$arr_ligen[1][0] = "Neue Liga anlegen";
$arr_ligen[0][1] = 0;
$arr_ligen[1][1] = "";

$arr_tmp = DBM_Ligen::get_list_Liga();
if($arr_tmp) for($i=0; $i<count($arr_tmp[0]); $i++) {
    $arr_ligen[0][$i+2] = $arr_tmp[0][$i];
    $arr_ligen[1][$i+2] = $arr_tmp[1][$i];
}

$arr_kate = DBMapper::get_list_Kategorie();
$arr_klasse = DBMapper::get_list_Klasse();

$arr_spielsysteme = DBM_Ligen::get_list_spielsystem();

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neue Liga anlegen") {
      $bool_show_data = 1;
      $int_id = -1;
      $str_l_name = "";
      $int_id_klasse = 1;
      $int_id_kate = 1;
      $str_l_kurzname = "";
      $str_l_date = "";
      $int_id_spielsystem = 0;
      $bool_stich = 1;
      $bool_weiblich = 0;
      $int_l_ord = 0;
    }
    else {
      // load from db
      $int_id = trim(substr($key,6));
      $Liga = DBM_Ligen::read_Liga($int_id);
      if($Liga) {
        $bool_show_data = 1;
        $str_l_name = $Liga->get_str_name();
        $int_id_klasse = $Liga->get_int_id_klasse();
        $int_id_kate = $Liga->get_int_id_kategorie();
        $str_l_date = $Liga->get_date_stichtag();
        $str_l_kurzname = $Liga->get_str_kurzname();
        $int_id_spielsystem = $Liga->get_int_id_spielsystem();
        $bool_stich = $Liga->get_bool_stichtag_typ();
        $bool_weiblich = $Liga->get_bool_weiblich();
        $int_l_ord = $Liga->get_int_ordnungsnr();
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
