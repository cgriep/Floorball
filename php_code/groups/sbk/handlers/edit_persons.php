<?php
$bool_show_data = 0;
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
      $int_id_club = 0;
      $club_str = "";
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
        $int_id_club = $Person->get_int_id_verein();
        $club_str = "";
        if($int_id_club) {
          $club_str = $Person->get_str_verein_name();
        }
      }
    }
  }
}

if(isset($_POST['save'])) {
  $int_p_id = $_POST['p_id'];
  $bool_show_data = 1;
  $load = 1;
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
  $int_id_club = $_POST['p_id_club'];
  $Person = new Person($int_p_id, $int_id_club, $str_p_name, $str_p_name2,
		       $str_p_street, $str_p_nbr, $str_p_plz,
		       $str_p_place, $str_p_tel, $str_p_handy,
		       $str_p_email, $str_p_date);
  $club_str = "";
  if($int_id_club) $club_str = $Person->get_str_verein_name();
  if($int_p_id == -1) {
    $int_p_id = DBMapper::write_Person($Person);
  }
  else {
    DBMapper::update_Person($Person);
  }
}

?>
