<?php
$bool_show_data = 0;
$user = DBM_Benutzer::read_Benutzer($_SESSION['arr_login']['id_benutzer']);
$club_id = $user->get_int_id_verein();
$club_str = $user->get_str_verein_name();
$load = 0;

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neue Person anlegen") {
      $bool_show_data = 1;
      $int_p_id = -1;
      $str_p_name = "";
      $str_p_name2 = "";
      $str_p_date = "";
      $str_p_street = "";
      $str_p_nbr = "";
      $str_p_place = "";
      $str_p_plz = "";
      $str_p_handy = "";
      $str_p_tel = "";
      $str_p_email = "";
    }
    else {
      // load from db
      $int_p_id = trim(substr($key,6));
      $Person = DBMapper::read_Person($int_p_id);
      if($Person) {
	$load = 1;
        $bool_show_data = 1;
        $str_p_name = $Person->get_str_vorname();
        $str_p_name2 = $Person->get_str_name();
        $str_p_date = $Person->get_date_geb_datum();
        $str_p_street = $Person->get_str_strasse();
        $str_p_nbr = $Person->get_str_hausnummer();
        $str_p_place = $Person->get_str_ort();
        $str_p_plz = $Person->get_str_plz();
        $str_p_handy = $Person->get_str_handy();
        $str_p_tel = $Person->get_str_telefon();
        $str_p_email = $Person->get_str_email();
      }
    }
  }
}

if(isset($_POST['save'])) {
  $int_p_id = $_POST['p_id'];
  $bool_show_data = 1;
  $load = 1;
  if($int_p_id == -1) {
    $str_p_name = $_POST['p_name'];
    $str_p_name2 = $_POST['p_name2'];
    $str_p_date = $_POST['p_date'];
    $str_p_street = $_POST['p_street'];
    $str_p_nbr = $_POST['p_nbr'];
    $str_p_plz = $_POST['p_plz'];
    $str_p_place = $_POST['p_place'];
    $str_p_handy = $_POST['p_handy'];
    $str_p_tel = $_POST['p_tel'];
    $str_p_email = $_POST['p_email'];

    $Person = new Person($int_p_id, $club_id, $str_p_name, $str_p_name2,
			 $str_p_street, $str_p_nbr, $str_p_plz,
			 $str_p_place, $str_p_tel, $str_p_handy,
			 $str_p_email, $str_p_date);
    $int_p_id = DBMapper::write_Person($Person);
  }
  else {
    $Person = DBMapper::read_Person($int_p_id);
    $Person->set_str_strasse($_POST['p_street']);
    $Person->set_str_hausnummer($_POST['p_nbr']);
    $Person->set_str_plz($_POST['p_plz']);
    $Person->set_str_ort($_POST['p_place']);
    $Person->set_str_handy($_POST['p_handy']);
    $Person->set_str_telefon($_POST['p_tel']);
    $Person->set_str_email($_POST['p_email']);

    DBMapper::update_Person($Person);
    $str_p_name = $Person->get_str_vorname();
    $str_p_name2 = $Person->get_str_name();
    $str_p_date = $Person->get_date_geb_datum();
    $str_p_street = $Person->get_str_strasse();
    $str_p_nbr = $Person->get_str_hausnummer();
    $str_p_place = $Person->get_str_ort();
    $str_p_plz = $Person->get_str_plz();
    $str_p_handy = $Person->get_str_handy();
    $str_p_tel = $Person->get_str_telefon();
    $str_p_email = $Person->get_str_email();
  }
}

?>