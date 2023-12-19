<?php



foreach ($_POST as $key=>$value) {
    if(substr($key, 0, 5) == "list1") {
        if($value == "Neuen Spieltag anlegen") {
            $bool_show_data = 1;
            $int_id = -1;
            $str_date = "";
        }
        else {
            // load from db
            $int_id = trim(substr($key,6));
            /*$Match = DBM_Ligen::read_Liga($int_id);
            if($Match) {
                $bool_show_data = 1;
                $str_l_name = $Liga->get_str_name();
                $int_id_season = $Liga->get_int_id_saison();
            }*/
        }
    }
}

if(isset($_POST['save'])) {
    $int_id = $_POST['id'];
    $str_date = $_POST['date'];
    $int_id_league = $_POST['id_league'];
    $bool_show_data = 1;
}

if($int_id_league) {
  $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id_league);
  $arr_teams[0][] = 0;
  $arr_teams[1][] = "leer";
}

?>