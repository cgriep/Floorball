<?php

$x1 = 25;
$x2 = 36.7;

$form_id = $my_form->create_form("SG", "SG", -1, -1, 60, 30, 'enctype="multipart/form-data"');
$my_form->print_text($form_id, "Spielgemeinschaften", 3, 1);
$my_form->input_list($form_id, "list1", $arr_sg[1], $arr_sg[0], 2, 3, 20, 20, 19, 2);
if($bool_show_data) {
  $off = -2;
  $my_form->input_hidden($form_id, "id", $int_id);
  $my_form->input_hidden($form_id, "nav", "league");
  $my_form->print_text($form_id, "Spielgemeinschaft:", $x1, 5+$off);
  $my_form->print_text($form_id, $str_name, $x2, 5+$off);
  for($i=0; $i<count($arr_sg_clubs[0]); $i++) {
    $my_form->print_text($form_id, $arr_sg_clubs[1][$i], $x1, 9+$off+$i*2);
    if($Team->get_int_id_verein() != $arr_sg_clubs[0][$i])
      $my_form->input_button($form_id, "rem_".$arr_sg_clubs[0][$i], "Verein aus SG entfehrnen", $x2+10, 9.3+$off+$i*2.2);
  }
  $arr_tmp = DBMapper::get_list_Verein();
  $arr_clubs[0][] = 0;
  $arr_clubs[1][] = "---";
  for($k=0; $k<count($arr_tmp[0]); $k++) {
    $found = 0;
    for($l=0; $l<count($arr_sg_clubs[0]); $l++) {
      if($arr_tmp[0][$k] == $arr_sg_clubs[0][$l]) {
        $found = 1;
      }
    }
    if(!$found) {
      $arr_clubs[0][] = $arr_tmp[0][$k];
      $arr_clubs[1][] = $arr_tmp[1][$k];
    }
  }
  $my_form->input_select($form_id, "add_club_id", $arr_clubs[1],
                         $arr_clubs[0],0, 1, 0,
                         $x1, 11+$off+$i*2, 18);
  $my_form->input_button($form_id, "add_club", "Verein zu SG hinzufügen",
                         $x2+10, 11.6+$off+$i*2.2);

}
echo "\n<br/><center>\n";
$my_message->paint();
$my_form->echo_form($form_id);
echo "\n</center>";

?>