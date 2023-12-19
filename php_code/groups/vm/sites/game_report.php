<?php
/**
 * Erstellung für ...
 *
 * @package    VM
 * @subpackage Sites
 */
$istand1 = "";
$istand2 = "";
$iistand1 = "";
$iistand2 = "";
$iiistand1 = "";
$iiistand2 = "";
$vstand1 = "";
$vstand2 = "";
$etstand1 = "";
$etstand2 = "";

$load1_from_db = 0;

$Begegnung = DBMapper::read_Begegnung($int_id_match);

$Spieltag = DBMapper::read_Spieltag($Begegnung->get_int_id_spieltag());
$Spielort = DBMapper::read_Spielort($Spieltag->get_int_id_spielort());
$Liga = DBM_Ligen::read_Liga($Spieltag->get_int_id_liga());

  if(!isset($int_step)) {
    $int_step = 1;
    $load1_from_db = 1;
  }
  if(!isset($second_load)) {
    $Spielbericht = DBMapper::read_Spielbericht($int_id_match);
    if(!$Spielbericht) {
      $Spielbericht = new Spielbericht();
      $Spielbericht->set_int_id_begegnung($int_id_match);
    }
    $str_notices = $Spielbericht->get_str_kommentar();
    $int_id_bearbeitung = DBM_tmp_spielbericht::write_Spielbericht($Spielbericht);
  }
  else if($int_step != 5) {
    $Spielbericht = DBM_tmp_spielbericht::read_Spielbericht($int_id_bearbeitung);
    if($Spielbericht->get_str_kommentar() != $str_notices) {
      $Spielbericht->set_str_kommentar($str_notices);
      //DBM_tmp_spielbericht::update_Spielbericht($Spielbericht);
    }
    //var_dump($Spielbericht);
  }

$Mannschaft1 = DBMapper::read_Mannschaft($Begegnung->get_int_id_mannschaft1(),
                                         $Begegnung->get_int_id_verband_team1());
$Mannschaft2 = DBMapper::read_Mannschaft($Begegnung->get_int_id_mannschaft2(),
                                         $Begegnung->get_int_id_verband_team2());


$load_player = 0;

$arr_strafen = DBMapper::get_list_strafe();
$arr_strafen[0][] = 0;
$arr_strafen[1][] = '-';
$arr_codes = DBMapper::get_list_strafcode(TRUE);
$arr_codes[0][] = 0;
$arr_codes[1][] = '-';

if($int_step == 1) {
  $arr_tmp = DBM_Spieler::get_list_Spieler_of_Mannschaft($Begegnung->get_int_id_mannschaft1(), 0, $Begegnung->get_int_id_verband_team1());
  $load_player = 1;
  // spieler aussortieren deren lizenzen nicht angenommen sind
  $arr_player[0] = array();
  $arr_player[1] = array();
  for($i=0; $i<count($arr_tmp[0]);$i++) {
    if($arr_tmp[5][$i][0] == 1) {
      $arr_player[0][] = $arr_tmp[0][$i];
      $arr_player[1][] = $arr_tmp[1][$i].", ".$arr_tmp[2][$i];
    }
  }
}
else if($int_step == 2) {
  $arr_tmp = DBM_Spieler::get_list_Spieler_of_Mannschaft($Begegnung->get_int_id_mannschaft2(), 0, $Begegnung->get_int_id_verband_team2());
  $load_player = 1;
  // spieler aussortieren deren lizenzen nicht angenommen sind
  $arr_player[0] = array();
  $arr_player[1] = array();
  for($i=0; $i<count($arr_tmp[0]);$i++) {
    if($arr_tmp[5][$i][0] == 1) {
      $arr_player[0][] = $arr_tmp[0][$i];
      $arr_player[1][] = $arr_tmp[1][$i].", ".$arr_tmp[2][$i];
    }
  }
}
else if($int_step == 3) {
  // hier arrays laden
  $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team1();
  if($arr_Mitspieler) {
    for($i=0; $i<count($arr_Mitspieler) && $i<20; $i++) {
      if($arr_Mitspieler[$i]->get_int_trikotnr()) {
        $arr_nbrs1[0][] = $arr_Mitspieler[$i]->get_int_trikotnr();
        $arr_nbrs1[1][] = $arr_Mitspieler[$i]->get_int_trikotnr();
      }
    }
  }
  $arr_nbrs1[0][] = "1000";
  $arr_nbrs1[1][] = "ET";
  $arr_nbrs1[0][] = "2000";
  $arr_nbrs1[1][] = "NA";
  $arr_nbrs1[0][] = "-";
  $arr_nbrs1[1][] = "-";
  unset($arr_Mitspieler);
  $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team2();
  if($arr_Mitspieler) {
    for($i=0; $i<count($arr_Mitspieler) && $i<20; $i++) {
      if($arr_Mitspieler[$i]->get_int_trikotnr()) {
        $arr_nbrs2[0][] = $arr_Mitspieler[$i]->get_int_trikotnr();
        $arr_nbrs2[1][] = $arr_Mitspieler[$i]->get_int_trikotnr();
      }
    }
  }
  $arr_nbrs2[0][] = "1000";
  $arr_nbrs2[1][] = "ET";
  $arr_nbrs2[0][] = "2000";
  $arr_nbrs2[1][] = "NA";
  $arr_nbrs2[0][] = "-";
  $arr_nbrs2[1][] = "-";
  unset($arr_Mitspieler);
}

if($load_player) {
  if(!isset($arr_nbr)) $arr_nbr = array("-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-");
  if(!isset($arr_names)) $arr_names = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
}

if($load1_from_db) {
  $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team1();
  if($arr_Mitspieler) {
    for($i=0; $i<count($arr_Mitspieler) && $i<20; $i++) {
      if($arr_Mitspieler[$i]->get_bool_kapitain()) $arr_ch[$i] = 1;
      else $arr_ch[$i] = 0;
      if($arr_Mitspieler[$i]->get_bool_torwart()) $arr_th[$i] = 1;
      else $arr_th[$i] = 0;
      $arr_nbr[$i] = $arr_Mitspieler[$i]->get_int_trikotnr();
      $arr_names[$i] = $arr_Mitspieler[$i]->get_int_id_spieler();
      if($arr_names[$i] < 0) {
        $arr_add_player[] = $arr_Mitspieler[$i]->get_str_name();
        $arr_names[$i] = count($arr_add_player)*-1;
      }
    }
  }
  $Betreuer1 = $Spielbericht->get_Betreuer_team1();
  if($Betreuer1) {
    $bh1 = $Betreuer1->get_str_betreuer1();
    $bh2 = $Betreuer1->get_str_betreuer2();
    $bh3 = $Betreuer1->get_str_betreuer3();
    $bh4 = $Betreuer1->get_str_betreuer4();
    $bh5 = $Betreuer1->get_str_betreuer5();
    if($Betreuer1->get_bool_unterschrift()) $cbh1 = 1;
  }
}

if(isset($load2_from_db)) {
  $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team2();
  if($arr_Mitspieler) {
    for($i=0; $i<count($arr_Mitspieler) && $i<20; $i++) {
      if($arr_Mitspieler[$i]->get_bool_kapitain()) $arr_ch[$i] = 1;
      else $arr_ch[$i] = 0;
      if($arr_Mitspieler[$i]->get_bool_torwart()) $arr_th[$i] = 1;
      else $arr_th[$i] = 0;
      $arr_nbr[$i] = $arr_Mitspieler[$i]->get_int_trikotnr();
      $arr_names[$i] = $arr_Mitspieler[$i]->get_int_id_spieler();
      if($arr_names[$i] < 0) {
        $arr_add_player[] = $arr_Mitspieler[$i]->get_str_name();
        $arr_names[$i] = count($arr_add_player)*-1;
      }
    }
  }
  $Betreuer2 = $Spielbericht->get_Betreuer_team2();
  if($Betreuer2) {
    $bh1 = $Betreuer2->get_str_betreuer1();
    $bh2 = $Betreuer2->get_str_betreuer2();
    $bh3 = $Betreuer2->get_str_betreuer3();
    $bh4 = $Betreuer2->get_str_betreuer4();
    $bh5 = $Betreuer2->get_str_betreuer5();
    if($Betreuer2->get_bool_unterschrift()) $cbh1 = 1;
  }
}

 if($load_player) {
  if(isset($arr_add_player)) {
    for($i=0; $i<count($arr_add_player); $i++) {
      $arr_player[0][] = -1-$i;
      $arr_player[1][] = $arr_add_player[$i];
    }
  }
  if(!isset($arr_nbr)) $arr_nbr = array("-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-");
  if(!isset($arr_names)) $arr_names = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
}

  $arr_player[0][] = 0;
  $arr_player[1][] = "--";

//<input id="b1r2c1" type="text" class="itext2" name="date" maxlength="10" value="Datum" />
//<input id="b1r2c2" type="text" class="itext2" name="ort" maxlength="30" value="Ort" />
//<input id="b1r2c3" type="text" class="itext2" name="start" maxlength="5" value="Begin" />
//<input id="b1r2c4" type="text" class="itext2" name="game_nbr" maxlength="10" value="Spiel-Nr." />

function echo_options($arr_options, $int_select = 0) {
  for($i=0; $i<count($arr_options[0]); $i++) {
    $str_select = '';
    if($arr_options[0][$i] == $int_select) $str_select = ' selected="selected"';
    echo '<option class="generic-option" value="'.$arr_options[0][$i].'"'.$str_select.'>'.$arr_options[1][$i].'</option>';
  }
}
echo "<center>";
$my_message->paint();
echo "</center>";

    if($int_step == 1) {
      echo '<table id="header"><tr><td id="header_step">';
      echo "Schritt 1:";
      echo '</td><td id="header_text">';
      echo "Bitte geben sie die Daten zu der ersten Mannschaft ein. <br/> Zu den Daten gehören die Spieler mit Nummern, der Kapitän, der Torwart, die Betreuer und die Unterschrift des ersten Betreuers.";
      echo '</td></tr></table>';
    }
    if($int_step == 2) {
      echo '<table id="header"><tr><td id="header_step">';
      echo "Schritt 2:";
      echo '</td><td id="header_text">';
      echo "Bitte geben sie analog zu dem ersten Schritt die Daten zu der zweiten Mannschaft ein. <br/> Zu den Daten gehören die Spieler mit Nummern, der Kapitän, der Torwart, die Betreuer und die Unterschrift des ersten Betreuers.";
      echo '</td></tr></table>';
    }

if($int_step == 3)
  echo "<div style=\"position:relative; height:164em;\">";
else
  echo "<div style=\"position:relative; height:68em;\">";
  ?>

<form name="sv_game" action="index.php?seite=Spielberichte" method="POST">
<input type="hidden" name="nav" value="game_report"/>
<input type="hidden" name="id_matchday" value="<?php echo $int_id_matchday;?>"/>
<input type="hidden" name="id_match" value="<?php echo $int_id_match;?>"/>
<input type="hidden" name="id_bearbeitung" value="<?php echo $int_id_bearbeitung;?>"/>
<input type="hidden" name="step" value="<?php echo $int_step;?>"/>
<?php if(!isset($int_step) || $int_step < 5) { ?>
<input type="submit" class="generic-button" name="next_step" onclick="show_save();" value="zurück" style="position:absolute; top:0em; left:0em;"/>
<input type="submit" class="generic-button" name="next_step" onclick="show_save();" value="nächster Schritt" style="position:absolute; top:0em; left:6em;"/>
<?php } ?>
<div class="browser" style="position:absolute; top:3em; left:-6em; width:111.5em; height:79em;">
<div id="box1">
<div id="b1r1c1" class="browser halfp">
<div class="f12">Spielberichtsbogen </div></div>
<div id="b1r2c1" class="itext2"><?php echo $Spielbericht->get_date_datum();?></div>
<div id="b1r2c2" class="itext2">Ort: <?php echo $Spielbericht->get_str_spielort();?></div>
<div id="b1r2c3" class="itext2"><?php echo $Spielbericht->get_str_uhrzeit();?> Uhr</div>
<div id="b1r2c4" class="itext2">Spiel Nr.:<?php echo $Spielbericht->get_int_spielnummer();?></div>
<div id="b1r2c5" class="itext2">
<?php //GF/KF/MX
      echo $Liga->get_str_kategorie();
?>
</div>
<div id="b1r2c6" class="itext2">
<?php //Damen/Herren/Jugend
      echo $Liga->get_str_klasse();
?>
</div>
<div id="b1r2c7" class="itext2">
<?php //Liga
      echo $Liga->get_str_name();
?>
</div>
</div>
<?php if($int_step == 1) {
if(isset($arr_add_player)) {
  for($i=0; $i<count($arr_add_player); $i++) {
    echo '<input type="hidden" name="add_player_'.$i.'" value="'.$arr_add_player[$i].'"/>';
  }
}?>
<div id="box2" class="boxborder">
<div id="b2r1c1" class="itext2"><?php echo $Mannschaft1->get_str_name();?></div>
<div id="b2r2c1" class="itext3">T</div>
<div id="b2r2c2" class="itext3">C</div>
<div id="b2r2c3" class="itext3">Nr.</div>
<div id="b2r2c4" class="itext3">Name/Vorname</div>
   <?php for($i=0; $i<20; $i++) {
   $top = 4+$i*2;
   if((isset($arr_th[$i])) && ($arr_th[$i] == 1)) $str_th = 'checked="checked" ';
   else $str_th = '';
   if((isset($arr_ch[$i])) && ($arr_ch[$i] == 1)) $str_ch = 'checked="checked" ';
   else $str_ch = '';
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<input id="b2rnc1" type="checkbox" '.$str_th.'class="itext2" name="th_'.$i.'" value="1" />'."\n";
   echo '<input id="b2rnc2" type="checkbox" '.$str_ch.'class="itext2" name="ch_'.$i.'" value="1" />'."\n";
   echo '<input id="b2rnc3" type="text" class="itext2" name="nrh1_'.$i.'" maxlength="3" value="'.$arr_nbr[$i].'" />'."\n";
echo '<select id="b2rnc4" class="itext2 generic-select" name="nh_'.$i.'" size="1">';
echo_options($arr_player, $arr_names[$i]);
echo '</select>';
//echo '<input id="b2rnc4" type="text" class="itext2" name="nh_'.$i.'" />'."\n";
   echo '</div>';
 }?>
<input id="b2rn1c1" type="text" class="itext2" name="add_playert" maxlength="40" value="Name, Vorname" />
<input id="b2rn1c2" type="submit" class="itext2 generic-button" name="add_player" value="Spieler hinzufügen" />
<?php
if(!isset($bh1)) $bh1 = "Betreuer 1";
echo '<input id="b2rn2c1" type="text" class="itext2" name="bh1" maxlength="40" value="'.$bh1.'" />';
if(isset($cbh1)) $str_cbh = ' checked="checked"';
else $str_cbh = '';
echo '<div id="b2rn2c2"><input type="checkbox" name="cbh1" '.$str_cbh.'/> unterschrieben</div>';
if(!isset($bh2)) $bh2 = "Betreuer 2";
echo '<input id="b2rn3c1" type="text" class="itext2" name="bh2" maxlength="40" value="'.$bh2.'" />';
if(!isset($bh3)) $bh3 = "Betreuer 3";
echo '<input id="b2rn3c2" type="text" class="itext2" name="bh3" maxlength="40" value="'.$bh3.'" />';
if(!isset($bh4)) $bh4 = "Betreuer 4";
echo '<input id="b2rn4c1" type="text" class="itext2" name="bh4" maxlength="40" value="'.$bh4.'" />';
if(!isset($bh5)) $bh5 = "Betreuer 5";
echo '<input id="b2rn4c2" type="text" class="itext2" name="bh5" maxlength="40" value="'.$bh5.'" />';
echo '</div>';
} else {?>
<div id="box2" class="">
<div id="b2r1c1" class="itext2"><?php echo $Mannschaft1->get_str_name();?></div>
<div id="b2r2c1" class="itext3">T</div>
<div id="b2r2c2" class="itext3">C</div>
<div id="b2r2c3" class="itext3">Nr.</div>
<div id="b2r2c4" class="itext3">Name/Vorname</div>
<?php
   $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team1();
   $k=0;
   for($i=0; $i<20; $i++) {
     $top = 4+$i*2;
     $str_kapitain = "";
     $str_torwart = "";
     $str_nbr = "-";
     $str_name = "__";
     if(isset($arr_Mitspieler[$k])) {
        if($arr_Mitspieler[$k]->get_bool_kapitain()) $str_kapitain = 'checked="checked"';
        if($arr_Mitspieler[$k]->get_bool_torwart()) $str_torwart = 'checked="checked"';
        $str_nbr = $arr_Mitspieler[$k]->get_int_trikotnr();
        if($arr_Mitspieler[$k]->get_int_id_spieler() < 0)
          $str_name = $arr_Mitspieler[$k]->get_str_name();
        else
        $str_name = $arr_Mitspieler[$k]->get_str_name().", ".$arr_Mitspieler[$k]->get_str_vorname();
    }
    $k++;
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<input disabled="disabled" id="b2rnc1" type="checkbox" class="itext2" name="thd" value="'.$i.'" '.$str_torwart.'/>'."\n";
   echo '<input disabled="disabled" id="b2rnc2" type="checkbox" class="itext2" name="chd" value="'.$i.'" '.$str_kapitain.'/>'."\n";
   echo '<div id="b2rnc3" class="itext2" >'.$str_nbr.'</div>';
echo '<div id="b2rnc4" class="itext2">'.$str_name.'</div>';
   echo '</div>';
 }
echo '<div id="b2rn1c1" class="itext2">'.$Spielbericht->get_Betreuer_team1()->get_str_betreuer1().'</div>';
echo '<div id="b2rn1c2">';
if($Spielbericht->get_Betreuer_team1()->get_bool_unterschrift()) $str_checked = ' checked="checked"';
else $str_checked = "";
echo '<input disabled="disabled" type="checkbox" name="cbh1d" '.$str_checked.'/> unterschrieben</div>';
echo '<div id="b2rn2c1" class="itext2">'.$Spielbericht->get_Betreuer_team1()->get_str_betreuer2().'</div>';
echo '<div id="b2rn2c2" class="itext2">'.$Spielbericht->get_Betreuer_team1()->get_str_betreuer3().'</div>';
echo '<div id="b2rn3c1" class="itext2">'.$Spielbericht->get_Betreuer_team1()->get_str_betreuer4().'</div>';
echo '<div id="b2rn3c2" class="itext2">'.$Spielbericht->get_Betreuer_team1()->get_str_betreuer5().'</div>';
echo '</div>';

 }
if($int_step == 2) {
if(isset($arr_add_player)) {
  for($i=0; $i<count($arr_add_player); $i++) {
    echo '<input type="hidden" name="add_player_'.$i.'" value="'.$arr_add_player[$i].'"/>';
  }
}?>
<div id="box3" class="boxborder">
<div id="b2r1c1" class="itext2"><?php echo $Mannschaft2->get_str_name();?></div>
<div id="b2r2c1" class="itext3">T</div>
<div id="b2r2c2" class="itext3">C</div>
<div id="b2r2c3" class="itext3">Nr.</div>
<div id="b2r2c4" class="itext3">Name/Vorname</div>
   <?php for($i=0; $i<20; $i++) {
   $top = 4+$i*2;
   if((isset($arr_th[$i])) && ($arr_th[$i] == 1)) $str_th = ' checked="checked"';
   else $str_th = '';
   if((isset($arr_ch[$i])) && ($arr_ch[$i] == 1)) $str_ch = ' checked="checked"';
   else $str_ch = '';
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<input id="b2rnc1" type="checkbox" class="itext2" name="th_'.$i.'" value="1" '.$str_th.'/>'."\n";
   echo '<input id="b2rnc2" type="checkbox" class="itext2" name="ch_'.$i.'" value="1" '.$str_ch.'/>'."\n";
   echo '<input id="b2rnc3" type="text" class="itext2" name="nrh1_'.$i.'" maxlength="3" value="'.$arr_nbr[$i].'" />'."\n";
echo '<select id="b2rnc4" class="itext2 generic-select" name="nh_'.$i.'" size="1">';
echo_options($arr_player, $arr_names[$i]);
echo '</select>';
//echo '<input id="b2rnc4" type="text" class="itext2" name="nh_'.$i.'" />'."\n";
   echo '</div>';
 }?>
<input id="b2rn1c1" type="text" class="itext2" name="add_playert" maxlength="40" value="Name, Vorname" />
<input id="b2rn1c2" type="submit" class="itext2 generic-button" name="add_player" value="Spieler hinzufügen" />
<?php
if(!isset($bh1)) $bh1 = "Betreuer 1";
echo '<input id="b2rn2c1" type="text" class="itext2" name="bh1" maxlength="40" value="'.$bh1.'" />';
if(isset($cbh1)) $str_cbh = ' checked="checked"';
else $str_cbh = '';
echo '<div id="b2rn2c2"><input type="checkbox" name="cbh1" '.$str_cbh.'/> unterschrieben</div>';
if(!isset($bh2)) $bh2 = "Betreuer 2";
echo '<input id="b2rn3c1" type="text" class="itext2" name="bh2" maxlength="40" value="'.$bh2.'" />';
if(!isset($bh3)) $bh3 = "Betreuer 3";
echo '<input id="b2rn3c2" type="text" class="itext2" name="bh3" maxlength="40" value="'.$bh3.'" />';
if(!isset($bh4)) $bh4 = "Betreuer 4";
echo '<input id="b2rn4c1" type="text" class="itext2" name="bh4" maxlength="40" value="'.$bh4.'" />';
if(!isset($bh5)) $bh5 = "Betreuer 5";
echo '<input id="b2rn4c2" type="text" class="itext2" name="bh5" maxlength="40" value="'.$bh5.'" />';
echo '</div>';
} else {?>
<div id="box3">
<div id="b2r1c1" class="itext2"><?php echo $Mannschaft2->get_str_name();?></div>
<div id="b2r2c1" class="itext3">T</div>
<div id="b2r2c2" class="itext3">C</div>
<div id="b2r2c3" class="itext3">Nr.</div>
<div id="b2r2c4" class="itext3">Name/Vorname</div>
<?php
   $arr_Mitspieler = $Spielbericht->get_arr_Mitspieler_team2();
   $k=0;
   for($i=0; $i<20; $i++) {
     $top = 4+$i*2;
     $str_kapitain = "";
     $str_torwart = "";
     $str_nbr = "-";
     $str_name = "__";
     if(isset($arr_Mitspieler[$k])) {
        if($arr_Mitspieler[$k]->get_bool_kapitain()) $str_kapitain = 'checked="checked"';
        if($arr_Mitspieler[$k]->get_bool_torwart()) $str_torwart = 'checked="checked"';
        $str_nbr = $arr_Mitspieler[$k]->get_int_trikotnr();
        if($arr_Mitspieler[$k]->get_int_id_spieler() < 0)
          $str_name = $arr_Mitspieler[$k]->get_str_name();
        else
        $str_name = $arr_Mitspieler[$k]->get_str_name().", ".$arr_Mitspieler[$k]->get_str_vorname();
    }
    $k++;
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<input disabled="disabled" id="b2rnc1" type="checkbox" class="itext2" name="thd2" value="'.$i.'" '.$str_torwart.'/>'."\n";
   echo '<input disabled="disabled" id="b2rnc2" type="checkbox" class="itext2" name="chd2" value="'.$i.'" '.$str_kapitain.'/>'."\n";
   echo '<div id="b2rnc3" class="itext2" >'.$str_nbr.'</div>';
echo '<div id="b2rnc4" class="itext2">'.$str_name.'</div>';
   echo '</div>';
   }

echo '<div id="b2rn1c1" class="itext2">'.$Spielbericht->get_Betreuer_team2()->get_str_betreuer1().'</div>';
echo '<div id="b2rn1c2">';
if($Spielbericht->get_Betreuer_team2()->get_bool_unterschrift()) $str_checked = ' checked="checked"';
else $str_checked = "";
echo '<input disabled="disabled" type="checkbox" name="cbh1d2" '.$str_checked.'/> unterschrieben</div>';
echo '<div id="b2rn2c1" class="itext2">'.$Spielbericht->get_Betreuer_team2()->get_str_betreuer2().'</div>';
echo '<div id="b2rn2c2" class="itext2">'.$Spielbericht->get_Betreuer_team2()->get_str_betreuer3().'</div>';
echo '<div id="b2rn3c1" class="itext2">'.$Spielbericht->get_Betreuer_team2()->get_str_betreuer4().'</div>';
echo '<div id="b2rn3c2" class="itext2">'.$Spielbericht->get_Betreuer_team2()->get_str_betreuer5().'</div>';
echo '</div>';


}

if($int_step == 3) {?>
<div id="box4" class="boxborder">
<div id="b4r1c1"><div style="font-size:12px; font-style:italic;"></div></div>
<div id="b4r2c1"><div class="f8">Team 1</div></div>
<div id="b4r2c2"><div class="f8">Team 2</div></div>
<div id="b4r3c1" class="itext3">Nr.</div>
<div id="b4r3c2" class="itext3">Ass.</div>
<div id="b4r3c3" class="itext3">Per.</div>
<div id="b4r3c4" class="itext3">Zeit</div>
<div id="b4r3c5" class="itext3">Stand</div>
<div id="b4r3c6" class="itext3">Strafe</div>
<div id="b4r3c7" class="itext3">Code</div>
<div id="b4r3c8" class="itext3">Nr.</div>
<div id="b4r3c9" class="itext3">Ass.</div>
  <?php
  if(!$arr_Ereignisse = $Spielbericht->get_arr_Ereignis()) $arr_Ereignisse = array();
  for($i=0; $i<100; $i++) {
   $top = 4.65+$i*2.2;
   $enb1 = 0;
   $eass1 = 0;
   $eper = 1;
   $etime = "";
   $estand = "";
   $estrafe = 0;
   $ecode = 0;
   $enb2 = 0;
   $eass2 = 0;
   for($k=0; $k<count($arr_Ereignisse); $k++) {
     if($arr_Ereignisse[$k]->get_int_zeile() == $i+1) {
       $enb1 = $arr_Ereignisse[$k]->get_int_nr_team1();
       //if($enb1 == 1000) $enb1 = "E";
       $eass1 = $arr_Ereignisse[$k]->get_int_ass_team1();
       $eper = $arr_Ereignisse[$k]->get_int_periode();
       $etime = $arr_Ereignisse[$k]->get_str_zeit();
       $estand = $arr_Ereignisse[$k]->get_int_tore_team1().":".$arr_Ereignisse[$k]->get_int_tore_team2();
       $estrafe = $arr_Ereignisse[$k]->get_int_id_strafe();
       $ecode = $arr_Ereignisse[$k]->get_int_id_strafcode();
       $enb2 = $arr_Ereignisse[$k]->get_int_nr_team2();
       //if($enb2 == 1000) $enb2 = "E";
       $eass2 = $arr_Ereignisse[$k]->get_int_ass_team2();
       break;
     }
   }
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<select id="b4rnc1" class="itext2 generic-select" name="nr2h_'.$i.'" size="1">';
   echo_options($arr_nbrs1, $enb1);
   echo '</select>';
   echo '<select id="b4rnc2" class="itext2 generic-select" name="assh_'.$i.'" size="1">';
   echo_options($arr_nbrs1, $eass1);
   echo '</select>';
   echo '<select id="b4rnc3" class="itext2 generic-select" name="per_'.$i.'" onchange="set_periode('.$i.',this.value)" size="1">';
   echo '<option class="generic-option" value="1"';
   if($eper == 1) echo ' selected="selected"';
   echo '>I</option>';
   echo '<option class="generic-option" value="2"';
   if($eper == 2) echo ' selected="selected"';
   echo '>II</option>';
   echo '<option class="generic-option" value="3"';
   if($eper == 3) echo ' selected="selected"';
   echo '>III</option>';
   echo '<option class="generic-option" value="4"';
   if($eper == 4) echo ' selected="selected"';
   echo '>V</option>';
   echo '</select>';

  // echo '<input id="b4rnc1" type="text" class="itext2" name="nr2h_'.$i.'" maxlength="3" value="" />'."\n";
//   echo '<input id="b4rnc2" type="text" class="itext2" name="assh_'.$i.'" maxlength="3" value="" />'."\n";
   //echo '<input id="b4rnc3" type="text" class="itext2" name="per_'.$i.'" maxlength="3" value="" />'."\n";
   echo '<input id="b4rnc4" type="text" class="itext2" name="time_'.$i.'" maxlength="5" value="'.$etime.'" />'."\n";
   echo '<input id="b4rnc5" type="text" class="itext2" name="stand_'.$i.'" maxlength="7" value="'.$estand.'" />'."\n";
   echo '<select id="b4rnc6" class="itext2 generic-select" name="strafe_'.$i.'" size="1">';
   echo_options($arr_strafen, $estrafe);
   echo '</select>';
   echo '<select id="b4rnc7" class="itext2 generic-select" name="code_'.$i.'" size="1">';
   echo_options($arr_codes, $ecode);
   echo '</select>';

//   echo '<input id="b4rnc6" type="text" class="itext2" name="strafe_'.$i.'" maxlength="3" value="" />'."\n";
   //echo '<input id="b4rnc7" type="text" class="itext2" name="code_'.$i.'" maxlength="3" value="" />'."\n";
   echo '<select id="b4rnc8" class="itext2 generic-select" name="nr2g_'.$i.'" size="1">';
   echo_options($arr_nbrs2, $enb2);
   echo '</select>';
   echo '<select id="b4rnc9" class="itext2 generic-select" name="assg_'.$i.'" size="1">';
   echo_options($arr_nbrs2, $eass2);
   echo '</select>';

   //echo '<input id="b4rnc8" type="text" class="itext2" name="nr2g_'.$i.'" maxlength="3" value="" />'."\n";
   //echo '<input id="b4rnc9" type="text" class="itext2" name="assg_'.$i.'" maxlength="3" value="" />'."\n";
   echo '</div>';
 }?>
</div>
<?php } else { ?>
<div id="box4">
<div id="b4r1c1"><div style="font-size:12px; font-style:italic;">www.unihockey-niedersachsen.de</div></div>
<div id="b4r2c1"><div class="f8">Team 1</div></div>
<div id="b4r2c2"><div class="f8">Team 2</div></div>
<div id="b4r3c1" class="itext3">Nr.</div>
<div id="b4r3c2" class="itext3">Ass.</div>
<div id="b4r3c3" class="itext3">Per.</div>
<div id="b4r3c4" class="itext3">Zeit</div>
<div id="b4r3c5" class="itext3">Stand</div>
<div id="b4r3c6" class="itext3">Strafe</div>
<div id="b4r3c7" class="itext3">Code</div>
<div id="b4r3c8" class="itext3">Nr.</div>
<div id="b4r3c9" class="itext3">Ass.</div>
   <?php
  if(!$arr_Ereignisse = $Spielbericht->get_arr_Ereignis()) $arr_Ereignisse = array();
   for($i=0; $i<34; $i++) {
   $top = 4.65+$i*2;
   $enb1 = "-";
   $eass1 = "-";
   $eper = "-";
   $etime = "-";
   $estand = "-";
   $estrafe = "-";
   $ecode = "-";
   $enb2 = "-";
   $eass2 = "-";
   for($k=0; $k<count($arr_Ereignisse); $k++) {
     if($arr_Ereignisse[$k]->get_int_zeile() == $i+1) {
       $enb1 = $arr_Ereignisse[$k]->get_int_nr_team1();
       if($enb1 == 1000) $enb1 = "E";
       $eass1 = $arr_Ereignisse[$k]->get_int_ass_team1();
       $eper = $arr_Ereignisse[$k]->get_int_periode();
       if($eper == 1) $eper = "I";
       else if($eper == 2) $eper = "II";
       else if($eper == 3) $eper = "III";
       else if($eper == 4) $eper = "V";
       $etime = $arr_Ereignisse[$k]->get_str_zeit();
       $etstand1 = $arr_Ereignisse[$k]->get_int_tore_team1();
       $etstand2 = $arr_Ereignisse[$k]->get_int_tore_team2();
       $estand = $etstand1.":".$etstand2;
       $estrafe = $arr_Ereignisse[$k]->get_int_id_strafe();
       for($j=0; $j<count($arr_strafen[0]); $j++) {
         if($arr_strafen[0][$j] == $estrafe) {
           $estrafe = $arr_strafen[1][$j];
           break;
         }
       }
       $ecode = $arr_Ereignisse[$k]->get_int_id_strafcode();
       for($j=0; $j<count($arr_codes[0]); $j++) {
         if($arr_codes[0][$j] == $ecode) {
           $ecode = $arr_codes[1][$j];
           break;
         }
       }
       $enb2 = $arr_Ereignisse[$k]->get_int_nr_team2();
       if($enb2 == 1000) $enb2 = "E";
       $eass2 = $arr_Ereignisse[$k]->get_int_ass_team2();
      break;
     }
   }
   if($eper != "-") {
     if($eper == "I" && $istand1 <= $etstand1 && $istand2 <= $etstand2) {
       $istand1 = $etstand1;
       $istand2 = $etstand2;
     }
     if($eper == "II" && $iistand1 <= $etstand1 && $iistand2 <= $etstand2) {
       $iistand1 = $etstand1-$istand1;
       $iistand2 = $etstand2-$istand2;
     }
     if($eper == "III" && $iiistand1 <= $etstand1 && $iiistand2 <= $etstand2) {
       $iiistand1 = $etstand1-$istand1-$iistand1;
       $iiistand2 = $etstand2-$istand2-$iistand2;
     }
     if($eper == "V" && $vstand1 <= $etstand1 && $vstand2 <= $etstand2) {
       $vstand1 = $etstand1-$istand1-$iistand1-$iiistand1;
       $vstand2 = $etstand2-$istand2-$iistand2-$iiistand2;
     }
   }
   echo '<div style="position:absolute; top:'.$top.'em; left:0em;">';
   echo '<div id="b4rnc1" class="itext2">'.$enb1.'</div>';
   echo '<div id="b4rnc2" class="itext2">'.$eass1.'</div>';
   echo '<div id="b4rnc3" class="itext2">'.$eper.'</div>';
   echo '<div id="b4rnc4" class="itext2">'.$etime.'</div>';
   echo '<div id="b4rnc5" class="itext2">'.$estand.'</div>';
   echo '<div id="b4rnc6" class="itext2">'.$estrafe.'</div>';
   echo '<div id="b4rnc7" class="itext2">'.$ecode.'</div>';
   echo '<div id="b4rnc8" class="itext2">'.$enb2.'</div>';
   echo '<div id="b4rnc9" class="itext2">'.$eass2.'</div>';
   echo '</div>';
 }?>
</div>
<?php }?>
<img id="bstrafen" src="images/strafen.png" />
<div id="box5" >
<?php
  if($etstand1 < $etstand2) $str_winner = $Mannschaft2->get_str_name();
  else if($etstand1 > $etstand2)$str_winner = $Mannschaft1->get_str_name();
  else $str_winner = "unentschieden";
echo "<div id=\"b5r1c1\" class=\"itext2\" style=\"font-size:1.5em;\">$str_winner</div>";
echo "<div id=\"b5r2c1\" class=\"itext2\" style=\"font-size:1.5em;\">$etstand1-$etstand2($istand1-$istand2,$iistand1-$iistand2,$iiistand1-$iiistand2,$vstand1-$vstand2) </div>";

if($int_step == 4) {?>
  <div id="b5r3c1" class="itext2 boxborder" style="font-size:1.5em;"><input type="checkbox" name="unterschrift1" <?php if($Spielbericht->get_bool_unterschrift_kaptain1()) echo 'checked="checked"';?>/> Unterschrift Kapitän 1</div>
  </div>
<?php } else {?>
<div id="b5r3c1" class="itext2" style="font-size:1.5em;"><input type="checkbox" name="unterschrift1" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_kaptain1()) echo 'checked="checked"';?>/> Unterschrift Kapitän 1</div>
</div>
<?php } if($int_step == 4) {?>
<div id="box6" class="boxborder">
<input id="b6r1c1" type="text" class="itext2" name="timeh" maxlength="22" value="Time-Out Team 1" />
<input id="b6r1c2" type="text" class="itext2" name="timeg" maxlength="22" value="Time-Out Team 2" />
<div id="b6r2c1"><input type="checkbox" name="verl" <?php if($Spielbericht->get_bool_verlaengerung()) echo 'checked="checked"';?> /> Verlängerung</div>
<div id="b6r2c2">Matchstrafe 1 <input type="checkbox" name="match1" <?php if($Spielbericht->get_bool_matchstrafe1()) echo 'checked="checked"';?> /></div>
<div id="b6r3c1"><input type="checkbox" name="match2" <?php if($Spielbericht->get_bool_matchstrafe2()) echo 'checked="checked"';?> /> Matchstrafe 2</div>
<div id="b6r3c2">Matchstrafe 3 <input type="checkbox" name="match3" <?php if($Spielbericht->get_bool_matchstrafe3()) echo 'checked="checked"';?> /></div>
<div id="b6r4c1"><input type="checkbox" name="berg" <?php if($Spielbericht->get_bool_besonderes_ereignis()) echo 'checked="checked"';?> /> Bes. Ereignis</div>
<div id="b6r4c2">Protest <input type="checkbox" name="prot" <?php if($Spielbericht->get_bool_protest()) echo 'checked="checked"';?> /></div>
<input id="b6r5c1" type="text" class="itext2" name="schrift" maxlength="40" value="<?php if($Spielbericht->get_str_schiedsgericht1() != NULL) echo $Spielbericht->get_str_schiedsgericht1(); else echo "Schriftführer"?>"/>
<div id="b6r5c2"><input type="checkbox" name="schriftb" <?php if($Spielbericht->get_bool_unterschrift_schiedsgericht1()) echo 'checked="checked"';?> /> unterschrieben</div>
<input id="b6r6c1" type="text" class="itext2" name="zeitnehmer" maxlength="40" value="<?php if($Spielbericht->get_str_schiedsgericht2() != NULL) echo $Spielbericht->get_str_schiedsgericht2(); else echo "Zeitnehmer"?>"/>
<div id="b6r6c2"><input type="checkbox" name="zeitnehmerb" <?php if($Spielbericht->get_bool_unterschrift_schiedsgericht2()) echo 'checked="checked"';?> /> unterschrieben</div>
<input id="b6r7c1" type="text" class="itext2" name="schiri1" maxlength="40" value="<?php if($Spielbericht->get_str_schiri1_name() != NULL) echo $Spielbericht->get_str_schiri1_name(); else echo "Schiedsrichter 1"?>"/>
<input id="b6r8c1" type="text" class="itext2" name="schiri2" maxlength="40" value="<?php if($Spielbericht->get_str_schiri2_name() != NULL) echo $Spielbericht->get_str_schiri2_name(); else echo "Schiedsrichter 2"?>"/>
<div id="b6r7c2"><input type="checkbox" name="schiri1b" <?php if($Spielbericht->get_bool_unterschrift_schiri1()) echo 'checked="checked"';?> /> Unt. Schiedsrichter 1</div>
<div id="b6r8c2"><input type="checkbox" name="schiri2b" <?php if($Spielbericht->get_bool_unterschrift_schiri2()) echo 'checked="checked"';?> /> Unt. Schiedsrichter 2</div>
<div id="b6r9c1" class="itext2" style="font-size:1.5em;"><input type="checkbox" name="unterschrift2" <?php if($Spielbericht->get_bool_unterschrift_kaptain2()) echo 'checked="checked"';?>/> Unterschrift Kapitän 2</div>
</div>
<?php } else {?>
<div id="box6">
<div id="b6r1c1" class="itext2"><?php if($Spielbericht->get_str_timeout1() != NULL) echo $Spielbericht->get_str_timeout1(); else echo "Time-Out Team 1"?></div>
<div id="b6r1c2" class="itext2"><?php if($Spielbericht->get_str_timeout2() != NULL) echo $Spielbericht->get_str_timeout2(); else echo "Time-Out Team 2"?></div>
<div id="b6r2c1"><input type="checkbox" name="verl" disabled="disabled" <?php if($Spielbericht->get_bool_verlaengerung()) echo 'checked="checked"';?>/> Verlängerung</div>
<div id="b6r2c2">Matchstrafe 1<input type="checkbox" name="match1" disabled="disabled" <?php if($Spielbericht->get_bool_matchstrafe1()) echo 'checked="checked"';?>/></div>
<div id="b6r3c1"><input type="checkbox" name="match2" disabled="disabled" <?php if($Spielbericht->get_bool_matchstrafe2()) echo 'checked="checked"';?>/> Matchstrafe 2</div>
<div id="b6r3c2">Matchstrafe 3<input type="checkbox" name="match3" disabled="disabled" <?php if($Spielbericht->get_bool_matchstrafe3()) echo 'checked="checked"';?>/></div>
<div id="b6r4c1"><input type="checkbox" name="berg" disabled="disabled" <?php if($Spielbericht->get_bool_besonderes_ereignis()) echo 'checked="checked"';?>/> Bes. Ereignis</div>
<div id="b6r4c2">Protest<input type="checkbox" name="prot" disabled="disabled" <?php if($Spielbericht->get_bool_protest()) echo 'checked="checked"';?>/></div>
<div id="b6r5c1" class="itext2">
<?php if($Spielbericht->get_str_schiedsgericht1() != NULL) echo $Spielbericht->get_str_schiedsgericht1(); else echo "Schriftführer"?></div>
<div id="b6r5c2"><input type="checkbox" name="schriftb" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_schiedsgericht1()) echo 'checked="checked"';?>/> unterschrieben</div>
<div id="b6r6c1" class="itext2"><?php if($Spielbericht->get_str_schiedsgericht2() != NULL) echo $Spielbericht->get_str_schiedsgericht2(); else echo "Zeitnehmer"?></div>
<div id="b6r6c2"><input type="checkbox" name="zeitnehmerb" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_schiedsgericht2()) echo 'checked="checked"';?>/> unterschrieben</div>
<div id="b6r7c1" class="itext2"><?php if($Spielbericht->get_str_schiri1_name() != NULL) echo $Spielbericht->get_str_schiri1_name(); else echo "Schiedsrichter 1"?></div>
<div id="b6r8c1" class="itext2"><?php if($Spielbericht->get_str_schiri2_name() != NULL) echo $Spielbericht->get_str_schiri2_name(); else echo "Schiedsrichter 2"?></div>
<div id="b6r7c2"><input type="checkbox" name="schiri1b" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_schiri1()) echo 'checked="checked"';?>/> Unt. Schiedsrichter 1</div>
<div id="b6r8c2"><input type="checkbox" name="schiri2b" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_schiri2()) echo 'checked="checked"';?>/> Unt. Schiedsrichter 2</div>
<div id="b6r9c1" class="itext2" style="font-size:1.5em;"><input type="checkbox" name="unterschrift2" disabled="disabled" <?php if($Spielbericht->get_bool_unterschrift_kaptain2()) echo 'checked="checked"';?>/> Unterschrift Kapitän 2</div>
</div>
<?php } ?>
</div><div id="box7"><div style="font-size:0.8em;">Bemerkungen:
<textarea cols="80" rows="8" name="notices">
<?php if(isset($str_notices)) echo $str_notices;?>
</textarea>
</div></div>
</form>
</div>
