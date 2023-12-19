<?php
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
$old_nav = 0;

if(isset($_POST['old_nav'])) {
  $old_nav = 1;
  $arr_player[0] = array();
  $arr_player[1] = array();
  foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 6) == "p_club") {
      $arr_player[0][] = substr($key, 7);
      $arr_player[1][] = $value;
    }
    $int_t_id = $_POST['t_id'];
    $str_t_name = $_POST['t_name'];
    $int_t_liga = $_POST['id_liga'];
    $int_t_id_leiter = $_POST['t_id_leiter'];
    $bool_t_sg = $_POST['t_sg'];
  }
}

if(isset($_POST['save'])) {
  $person = new Person(-1, $int_id, $_POST['p_name'],
		       $_POST['p_n_name'], $_POST['p_street'],
		       $_POST['p_nbr'], $_POST['p_plz'],
		       $_POST['p_place'], $_POST['p_tel'], $_POST['p_handy'],
		       $_POST['p_mail'], $_POST['p_date']);
    $int_insert = DBMapper::write_Person($person);
    // Objekt erstellen und an db übergeben
    if($old_nav) {
      $nav = "teams";
      $int_t_id_leiter = $int_insert;
      $bool_show_player = 0;
    }
    else {
      $nav = "clubs";
      $int_id_leiter = $int_insert;
    }
    $bool_show_data = 1;
}

if(isset($_POST['back'])) {
  if($old_nav) {
    $nav = "teams";
    $bool_show_player = 0;
  }
  else $nav = "clubs";
    $bool_show_data = 1;
}
?>