
<?php
$int_id = $_POST['id'];
$str_date = $_POST['date'];
$int_id_league = $_POST['id_league'];
$int_id_place = $_POST['id_place'];
$str_spieltag_nr = $_POST['spieltag_nr'];

if(isset($_POST['save'])) {
  $Spielort = new Spielort(-1, $_POST['id_pl_club'], $_POST['pl_name'],
                           $_POST['pl_street'], $_POST['pl_nbr'],
                           $_POST['pl_plz'], $_POST['pl_place'],
                           $_POST['pl_anfahrt_pkw'],
                           $_POST['pl_anfahrt_oepnv'], $_POST['pl_zuschauer']);
    $int_insert = DBMapper::write_Spielort($Spielort);
    // Objekt erstellen und an db übergeben
    $nav = "setup_matches";
    $int_id_place = $int_insert;
    $bool_show_data = 1;
}

if(isset($_POST['back'])) {
  $nav = "setup_matches";
  $bool_show_data = 1;
}
?>
