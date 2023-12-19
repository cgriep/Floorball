<?php
    $int_id_matchday = $_POST['id_matchday'];

    $Spieltag = DBMapper::read_Spieltag($int_id_matchday);
    $int_id_match = $_POST['id_match'];
    $int_id_bearbeitung = $_POST['id_bearbeitung'];
    $int_step = $_POST['step'];
    $second_load = 1;
    $str_notices = $_POST['notices'];
    if(($int_step == 1) || ($int_step == 2)) {
      //if(isset($_POST['th'])) $th = $_POST['th'];
      //if(isset($_POST['ch']))$ch = $_POST['ch'];
      $bh1 = $_POST['bh1'];
      $bh2 = $_POST['bh2'];
      $bh3 = $_POST['bh3'];
      $bh4 = $_POST['bh4'];
      $bh5 = $_POST['bh5'];
      if(isset($_POST['cbh1'])) $cbh1 = $_POST['cbh1'];
      $arr_nbr = array();
      $arr_names = array();
      $arr_add_player = array();

      for($i=0; $i<20; $i++) {
        if(isset($_POST['th_'.$i])) $arr_th[] = 1;
         else $arr_th[] = 0;
        if(isset($_POST['ch_'.$i])) $arr_ch[] = 1;
         else $arr_ch[] = 0;
        $arr_nbr[] = $_POST['nrh1_'.$i];
        $arr_names[] = $_POST['nh_'.$i];
      }
      foreach ($_POST as $key=>$value) {
        if(substr($key, 0, 11) == "add_player_") {
          $int_num = trim(substr($key, 11));
          $arr_add_player[$int_num] = $value;
        }
      }

      if(isset($_POST['next_step'])) {
        // Daten schreiben
        if($int_step == 1 || $int_step == 2) {
          unset($arr_th);
          unset($arr_ch);
          // Spieler schreiben
          DBM_tmp_spielbericht::del_Mitspieler($int_id_bearbeitung, $int_step);
          $kapitain_exists = 0;
          $torwart_exists = 0;
          for($i=0; $i<20; $i++) {
            if($arr_names[$i] > 0) {
              for($k=0; $k<$i; $k++) {
                if($arr_names[$k] == $arr_names[$i]) {
                  // spieler doppelt fehler generieren
                  //echo "<br/>Spieler ist mehrfach angelegt";
                  $my_message->add_error("Spieler ist mehrfach angelegt!");
                }
                if($arr_nbr[$k] == $arr_nbr[$i]) {
                  // trikotnummer ist doppelt vergeben
                  //echo "<br/>Trikotnummer ist doppelt vergeben";
                  $my_message->add_error("Trikotnummer ist doppelt vergeben!");

                }
              }
              if($arr_nbr[$i] == "-") {
                // keine trikotnummer angegeben
                //echo "<br/>Zu dem Spieler aus Zeile ".($i+1)." wurde keine Trikotnummer angegeben.";
                $my_message->add_error("Zu dem Spieler aus Zeile ".($i+1)." wurde keine Trikotnummer angegeben.");
              }
              if(isset($_POST['th_'.$i])) {
                $torwart = 1;
                $torwart_exists = 1;
              }
              else $torwart = 0;
              if(isset($_POST['ch_'.$i])) {
                $kapitain = 1;
                $kapitain_exists = 1;
              }
              else $kapitain = 0;
              $Mitspieler = new Mitspieler(NULL,$int_id_match, $arr_names[$i], $arr_nbr[$i], $torwart, $kapitain, $int_step);
              $Mitspieler->set_int_bearbeitungsnr($int_id_bearbeitung);
              DBM_tmp_spielbericht::write_Mitspieler($Mitspieler);
            }
            else if($arr_names[$i] < 0) {
              // falls der spieler nicht im system ist
              if(isset($_POST['th_'.$i])) {
                $torwart = 1;
                $torwart_exists = 1;
              }
              else $torwart = 0;
              if(isset($_POST['ch_'.$i])) {
                $kapitain = 1;
                $kapitain_exists = 1;
              }
              else $kapitain = 0;
              $Mitspieler = new Mitspieler(NULL,$int_id_match, $arr_names[$i], $arr_nbr[$i], $torwart, $kapitain, $int_step, $arr_add_player[-1*(1+$arr_names[$i])]);
              $Mitspieler->set_int_bearbeitungsnr($int_id_bearbeitung);
              DBM_tmp_spielbericht::write_Mitspieler($Mitspieler);
            }
          }
          if(!$kapitain_exists) //echo "<br/>Es wurde kein Kapit&auml;n ausgew&auml;hlt!";
            $my_message->add_error("Es wurde kein Kapit&auml;n ausgew&auml;hlt!");
          if(!$torwart_exists) //echo "<br/>Es wurde kein Torwart ausgew&auml;hlt!";
            $my_message->add_error("Es wurde kein Torwart ausgew&auml;hlt!");
          // Betreuer und Unterschrift schreiben
          if(isset($cbh1)) $bool_cbh1 = 1;
          else {
            $bool_cbh1 = 0;
            //echo "<br/>Best&auml;tigung f&uuml;r Unterschrift von Betreuer 1 fehlt!";
            $my_message->add_error("Best&auml;tigung f&uuml;r Unterschrift von Betreuer 1 fehlt!");
          }
          // Latte-Machiatto
          DBM_tmp_spielbericht::del_Betreuer($int_id_bearbeitung, $int_step);
          $Betreuer = new Betreuer(NULL, $int_id_match, $bh1, $bh2, $bh3, $bh4, $bh5, $bool_cbh1, $int_step);
          $Betreuer->set_int_bearbeitungsnr($int_id_bearbeitung);
          DBM_tmp_spielbericht::write_Betreuer($Betreuer);
        }
        // temporäre Spieler wieder löschen
        unset($arr_add_player);
        $arr_add_palyer = array();
        // Formulardaten leeren
        unset($arr_nbr);
        unset($arr_names);
        unset($ch);
        unset($th);
        unset($bh1);
        unset($bh2);
        unset($bh3);
        unset($bh4);
        unset($bh5);
        unset($cbh1);
        // nächster Schritt
        if($_POST['next_step'] == "zurück") {
          unset($int_step);
        }
        else {
          $int_step += 1;
          $load2_from_db = 1;
        }
      }
      else if(isset($_POST['add_player'])) {
        if($_POST['add_playert'] == "Name, Vorname")
         $my_message->add_warning("Zum Hinzuf&uuml;gen eines Spielers muss zuerst ein Name und Vorname in dem Feld links neben dem Button angegeben werden.");
        else
         $arr_add_player[] = $_POST['add_playert'];
      }
      else {
        // undefiniert
        //echo "Sie sind in einen undefinierten Zustand gekommen!";
        $my_message->add_error("Sie sind in einen undefinierten Zustand gekommen!");
      }
    }
    else if($int_step == 3) {
      DBM_tmp_spielbericht::del_Ereignis($int_id_bearbeitung);
      $last_goal1 = 0;
      $last_goal2 = 0;
      for($i=0; $i<100; $i++) {
        if(!isset($_POST['time_'.$i])) break;
        else if($_POST['time_'.$i] != "") {
            $arr_tore = explode(":", $_POST['stand_'.$i]);
            if(count($arr_tore) == 2) {
                $tore1 = $arr_tore[0];
                $tore2 = $arr_tore[1];
                if($tore1 - $last_goal1 + $tore2 - $last_goal2 > 1)
                  $my_message->add_error("Ein oder mehrere Tore wurden nicht eingegeben!");
                $last_goal1 = $tore1;
                $last_goal2 = $tore2;
            }
            else {
                $tore1 = NULL;
                $tore2 = NULL;
            }
            $Ereignis = new Ereignis(NULL, $int_id_match, $i+1, $_POST['nr2h_'.$i], $_POST['assh_'.$i], $_POST['per_'.$i], $_POST['time_'.$i], $tore1, $tore2, $_POST['strafe_'.$i], $_POST['code_'.$i], $_POST['nr2g_'.$i], $_POST['assg_'.$i]);
            $Ereignis->set_int_bearbeitungsnr($int_id_bearbeitung);
            DBM_tmp_spielbericht::write_Ereignis($Ereignis);
        }
      }
      if($_POST['next_step'] == "zurück") {
        $int_step = 2;
        $load2_from_db = 1;
      }
      else $int_step += 1;
    }
    else if($int_step == 4) {
      if($_POST['next_step'] == "zurück") $int_step = 3;
      else {
        $int_step = 5;
        //var_dump($_POST);
        $Spielbericht = DBM_tmp_spielbericht::read_Spielbericht($int_id_bearbeitung);
        $Spielbericht->set_int_id_begegnung($int_id_match);
        $Spielbericht->set_bool_unterschrift_schiri1(isset($_POST['schiri1b']));
        $Spielbericht->set_bool_unterschrift_schiri2(isset($_POST['schiri2b']));
        $Spielbericht->set_bool_besonderes_ereignis(isset($_POST['berg']));
        $Spielbericht->set_str_timeout1($_POST['timeh']);
        $Spielbericht->set_str_timeout2($_POST['timeg']);
        $Spielbericht->set_bool_matchstrafe1(isset($_POST['match1']));
        $Spielbericht->set_bool_matchstrafe2(isset($_POST['match2']));
        $Spielbericht->set_bool_matchstrafe3(isset($_POST['match3']));
        $Spielbericht->set_bool_protest(isset($_POST['prot']));
        $Spielbericht->set_bool_unterschrift_kaptain1(isset($_POST['unterschrift1']));
        $Spielbericht->set_bool_unterschrift_kaptain2(isset($_POST['unterschrift2']));
        $Spielbericht->set_str_schiedsgericht1($_POST['schrift']);
        $Spielbericht->set_str_schiedsgericht2($_POST['zeitnehmer']);
        $Spielbericht->set_str_schiri1_name($_POST['schiri1']);
        $Spielbericht->set_str_schiri2_name($_POST['schiri2']);
        $Spielbericht->set_bool_unterschrift_schiedsgericht1(isset($_POST['schriftb']));
        $Spielbericht->set_bool_unterschrift_schiedsgericht2(isset($_POST['zeitnehmerb']));
        $Spielbericht->set_bool_verlaengerung(isset($_POST['verl']));
        $Spielbericht->set_str_kommentar($_POST['notices']);
        DBMapper::del_Spielbericht($int_id_match);
        DBMapper::write_Spielbericht($Spielbericht);
        if($Spieltag) {
          Statistik::create_tables($Spieltag->get_int_id_liga());
        }
      }
    }

?>