<?php
$bool_show_data = 0;
$load = 0;

foreach ($_POST as $key=>$value) {
  if(substr($key, 0, 5) == "list1") {
    if($value == "Neuen Schiedsrichter anlegen") {
      $bool_show_data = 1;
      $int_id = -1;
      $int_id_lizenztyp = 0;
      $int_id_club = 0;
      $int_lizenznummer = 0;
      $str_name = "";
      $str_name2 = "";
      $str_street = "";
      $str_nbr = "";
      $str_plz = "";
      $str_place = "";
      $str_handy = "";
      $str_email = "";
      $str_tel = "";
      $str_date = "";
      $str_kommentar = "";
    }
    else {
      // load from db
      $int_id = trim(substr($key,6));
      $Schiri = DBMapper::read_Schiedsrichter($int_id);
      if($Schiri) {
        $load = 1;
        $bool_show_data = 1;
        $int_id_lizenztyp = $Schiri->get_int_id_lizenztyp();
        $int_id_club = $Schiri->get_int_id_verein();
        $int_lizenznummer = $Schiri->get_int_lizenznummer();
        $str_name = $Schiri->get_str_vorname();
        $str_name2 = $Schiri->get_str_name();
        $str_street = $Schiri->get_str_strasse();
        $str_nbr = $Schiri->get_str_hausnummer();
        $str_plz = $Schiri->get_str_plz();
        $str_place = $Schiri->get_str_ort();
        $str_tel = $Schiri->get_str_telefon();
        $str_handy = $Schiri->get_str_handy();
        $str_email = $Schiri->get_str_email();
        $str_date = $Schiri->get_date_geb_datum();
        $str_kommentar = $Schiri->get_str_kommentar();
      }
    }
  }
}

if(isset($_POST['save'])) {
  $bool_show_data = 1;
  $load = 1;
  $int_id = $_POST['id'];
  $int_id_lizenztyp = $_POST['lizenztyp'];
  $int_id_club = $_POST['id_club'];
  $int_lizenznummer = $_POST['lizenznummer'];
  $str_name = $_POST['name'];
  $str_name2 = $_POST['name2'];
  $str_street = $_POST['street'];
  $str_nbr = $_POST['nbr'];
  $str_plz = $_POST['plz'];
  $str_place = $_POST['place'];
  $str_tel = $_POST['tel'];
  $str_handy = $_POST['handy'];
  $str_email = $_POST['email'];
  $str_date = $_POST['date'];
  $str_kommentar = $_POST['kommentar'];
  $Schiri = new Schiedsrichter($int_id, $int_id_lizenztyp, $int_id_club,
                               $int_lizenznummer, $str_name, $str_name2,
                               $str_street, $str_nbr, $str_plz,
                               $str_place, $str_tel, $str_handy,
                               $str_email, $str_date, $str_kommentar);
  if($int_id == -1) {
    $int_id = DBMapper::write_Schiedsrichter($Schiri);
    if($int_id > 0)
      $my_message->add_message("Der Schiedsrichter \"$str_name $str_name2\" wurde erfolgreich angelegt.");
    else $my_message->add_error("Datenbankfehler");
  }
  else {
    if(DBMapper::update_Schiedsrichter($Schiri))
      $my_message->add_message("Der Schiedsrichter \"$str_name $str_name2\" wurde erfolgreich aktualisiert.");
    else $my_message->add_error("Datenbankfehler");
  }
}

?>
