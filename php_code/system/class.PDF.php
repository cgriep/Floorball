<?php
/**
* PDF
*
* @package    SYSTEM
* @subpackage PDF2
*
* @author Michael Rohn
* @copyright Copyright (c) 2005
* @version 0.2
* @access public
*/
class PDF extends TCPDF {

  private $str_liga_name = "";

  private $Verband = NULL;
  private $str_titel = "";


  public function __construct() {
    $this->Verband = DBMapper::get_Verband_by_pfad(VERBAND);
    $this->str_titel = "Lizenzliste";
    parent::__construct();
  }

  //Page header
  public function Header() {
    // Logo links
    $this->Image('images/'.VERBAND.'/logo_70.png',10,7,45);
    // Logo rechts
    $this->Image('images/'.VERBAND.'/logo_70.png',155,7,45);
    // Arial bold 22
    $this->SetFont('helvetica','B',22);
    $this->setY(5);
    // Nach rechts schieben
    $this->Cell(45);
    // Titel setzen
    if (defined('TESTSYSTEM') && TESTSYSTEM) {
      $this->Cell(100,10,'TEST-SERVER',0,1,"C");
    } else {
      $this->Cell(100,10,$this->str_titel,0,1,"C");
    }

    // Arial regular 10
    $this->SetFont('helvetica','',10);
    // Nach rechts schieben
    $this->Cell(45);
    // Titel setzen
    $this->Cell(100,4,$this->str_liga_name,0,1,"C");


    // Arial bold 10
    $this->SetFont('helvetica','B',10);

/*    // Liga setzen
    $this->SetXY(70, 20);
    $this->Write(4,'Liga:');*/
    // Datum setzen
    $this->SetXY(80, 21);
    $this->Write(4,'Stand:');

    // Arial regular 10
    $this->SetFont('helvetica','',10);
/*
    // Liga setzen
    $this->SetXY(82, 20);
    $this->Write(4,$this->str_liga_name);
    */
    // Datum setzen
    $this->SetXY(92, 21);
    $this->Write(4,date('d.m.Y') . " / " . date('H:i') . " Uhr");

    // Line break
    $this->Ln(13);
  }

  //Page footer
  public function Footer() {
    //Position at 1.5 cm from bottom
    $this->SetY(-10);
    //Arial regular 9
    $this->SetFont('helvetica','',9);
    //Page number
    $this->SetXY(10, 285);
    $this->Write(6, $this->Verband->get_str_kuerzel() .' SaisonManager: http://'. VERBAND .'.sm-u.de');
    //Page number
    $this->SetXY(175, 285);
    $this->Write(6,'Seite '.$this->PageNo().' von {nb}');
  }


  static public function gen_lizenzliste($int_id_liga) {

    $pdf = new PDF();

    // Liganamen holen
    $Liga = DBM_Ligen::read_Liga($int_id_liga);
    $pdf->str_liga_name = $Liga->get_str_name();

    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Lizenzen ins PDF schreiben
    $pdf->write_lizenzen($int_id_liga);

    $pdf->Output();

  }

  static public function gen_Spielplan($table) {
  
  	$pdf = new PDF();
  	$pdf->str_titel = "Spielplan";
  	// Liganamen holen
  	
  	$pdf->AliasNbPages();
  	$pdf->AddPage();
  
  	// Lizenzen ins PDF schreiben
  	$pdf->write_Spielplan($table);
  
  	$pdf->Output();
  
  }  
  
  private function write_Spielplan($table) {
  		
  	$arr_leagues = DBM_Ligen::get_list_liga();
  	
  	// set id of current leagues to show
  	//if(isset($_GET['table'])) $table = $_GET['table'];
  	//else $table = $arr_leagues[0][0];
  	$count_matchdays = 0;
  	$liga_name = "Fehler - unbekannte Liga";
  	// create menu for leagues
  	for($i=0; $i<count($arr_leagues[0]); $i++) {
  		$Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
  		if($arr_leagues[0][$i] == $table) {
  			$int_id_kategorie = $Liga->get_int_id_kategorie();
  			if($int_id_kategorie == 1 || $int_id_kategorie == 4)
  				$int_num_reg = 3;
  			else $int_num_reg = 2;
  	
  			$file_name = $Liga->get_int_id();//$Liga->get_str_kurzname();
  			$liga_name = $Liga->get_str_name();
  			$arr_match_days = DBMapper::get_list_Spieltag($arr_leagues[0][$i]);
  			$count_matchdays = count($arr_match_days[0]);
  		}
  	}
  	if(!isset($int_num_reg)) $int_num_reg = 3;
  	 
  	$arr_new_games = array();
  	// spieltage umsortieren
  	for($i=0; $i<$count_matchdays; $i++) {
  		// liste der Begegnungen laden
  		$Spielort = DBMapper::read_Spielort($arr_match_days[4][$i]);
  	
  		$arr_games = DBMapper::get_list_Begegnung($arr_match_days[0][$i]);
  		for($k=0; $k<count($arr_games[0]); $k++) {
  			if($Spielort)
  				$arr_games[9][$k] = $Spielort->get_str_name();
  			else $arr_games[9][$k] = "Spielort";
  			$arr_games[10][$k] = $arr_match_days[1][$i];
  			for($l=0; $l<11; $l++)
  				$arr_new_games[$arr_match_days[5][$i]][$l][] = $arr_games[$l][$k];
  		}
  	}
  	
  	$index2 = 1;
  	$row_nr = 0;
  	$this->SetTextColor(0);
  	$this->SetLineWidth(.3);
  	$this->SetFont('helvetica','B',8);
  	//Header
  	$w = array(7,15,15,30,44,4,44,30);
  	
  	$this->Ln(20);
  	$this->SetFont('helvetica','B',14);
  	$this->Cell(array_sum($w),8,$liga_name,'',0,'C',false);
  	$this->Ln();
  	$this->SetFont('helvetica','B',8);
  	$this->Cell(7,5.2,'Nr','LTB',0,'C',false);
  	$this->Cell(15,5.2,'Datum','TB',0,'C',false);
  	$this->Cell(15,5.2,'Zeit','TB',0,'C',false);
  	$this->Cell(30,5.2,'Spielort','TB',0,'C',false);
  	$this->Cell(44,5.2,'Heim','TB',0,'C',false);
  	$this->Cell(4,5.2,'-','TB',0,'C',false);
  	$this->Cell(44,5.2,'Gast','TB',0,'C',false);
  	$this->Cell(30,5.2,'Schiedsrichter','RTB',0,'C',false);
  	
  	$this->Ln(5.5);
  	$this->SetFillColor(230,230,230);
  	
  	foreach($arr_new_games as $key => $value) {
  	// key ist spieltags_nummer
  	// value entspricht arr_games
  	
	  	while(count($value[0])) {
	  	  $index = -1;
	  	  foreach($value[0] as $key2 => $value2) {
	  	  	if($index < 0) $index = $key2;
	  	  	else if($value[5][$key2] < $value[5][$index])
	  			$index = $key2;
	  	  }
	  	
	  	$Mannschaft1 = DBMapper::read_Mannschaft($value[1][$index], $value[7][$index]);
	  	$Mannschaft2 = DBMapper::read_Mannschaft($value[2][$index], $value[8][$index]);
	  	
	  	$cont = false;
	  	if(!$Mannschaft1 || $Mannschaft1->get_bool_genehmigt() == 2) $cont = true;
	  	if(!$Mannschaft2 || $Mannschaft2->get_bool_genehmigt() == 2) $cont = true;
	  	
	  	if(!$cont) {
	  	    //Colors, line width and bold font
		    
		    
/*		  	
		    //Color and font restoration
		    $this->SetFillColor(230,230,230);
		    $this->SetTextColor(0);
		    $this->SetFont('helvetica','',9);
		    //Data
		    $fill = true;
		    if ($this->GetY() >= 270) {
	      		$this->Cell(array_sum($w),0,'','T');
	      		//return $j;
	      		//-- neue Seite 
	      	}
	      	*/
	      	$this->Cell($w[0],4.8,$value[5][$index],'L',0,'C',$fill);
	      	$this->Cell($w[1],4.8,$value[10][$index],0,0,'C',$fill);
	      	$this->Cell($w[2],4.8,$value[3][$index],0,0,'C',$fill);
	      	$this->Cell($w[3],4.8,$value[9][$index],0,0,'L',$fill);
	      	$this->SetFont('helvetica','B',6);
	      	$this->Cell($w[4],4.8,$Mannschaft1->get_str_name(),0,0,'R',$fill);
	      	$this->Cell($w[5],4.8,'-',0,0,'C',$fill);
	      	$this->Cell($w[6],4.8,$Mannschaft2->get_str_name(),0,0,'L',$fill);
	      	$this->SetFont('helvetica','B',8);
	      	 
	      	// parse schiedsrichter	      	
	      	$arr_tmp = explode("#", $value[4][$index]);
	      	$this->Cell($w[7],4.8,$arr_tmp[0],'R',0,'L',$fill);
	      	$this->Ln();
	      	$fill =! $fill;
	      	
		}
		$str_line = "";
	  	for($i=0; $i<11; $i++)
	  			unset($value[$i][$index]);
	  	}	
	  	$this->Cell(array_sum($w),0,'','T');
	  	$this->Ln(0);
  	}
 	
  	$this->Ln(20); 
  	/*
  	fancySpielplanTable()
  	
  	$ret_value = $this->FancyTable($header, $data);
  	if ($ret_value !== TRUE) {
  		$this->AddPage();
  		$this->Ln(20);
  		$this->write_verein($arr_teams[1][$i], true);
  		$data = array_slice($data, $ret_value);
  		$this->FancyTable($header, $data);
  	}
  	$this->Ln(10);
  	if ($i < (count($arr_teams[0])-1)) {
  		$this->AddPage();
  	}
  	*/
  }
  
  
  
  private function get_lizenzen_by_team($int_id_team, $id_verband, $id_verein = -1 ) {
    $arr_status = DBMapper::get_list_status_lizenz();
    $arr_player = DBM_Spieler::get_list_Spieler_of_Mannschaft($int_id_team, 0,
                                                              $id_verband);
    if ($arr_player && count($arr_player[0]) > 0) {
      for ($k=0; $k < count($arr_player[0]); $k++) {
        $tmp_player = DBM_Spieler::read_Spieler($arr_player[0][$k]);
        $id_lizenzstatus = DBM_Spieler::get_last_Spieler_Lizenzstatus($arr_player[0][$k], $int_id_team, $id_verband);
        if ($id_lizenzstatus[0] < 6) {
          $arr_licenses[$k][1] = $arr_player[1][$k].", ".$arr_player[2][$k];
          $str_status = "fehler";
          for ($n=0; $n<count($arr_status[0]); $n++) {
            if ($id_lizenzstatus[0] == $arr_status[0][$n]) {
              $str_status = $arr_status[1][$n];
              break;
            }
          }
          $arr_licenses[$k][2] = $str_status;
          $arr_licenses[$k][3] = $tmp_player->get_date_geb_datum();
          $arr_licenses[$k][4] = $tmp_player->get_str_nation();
          $arr_licenses[$k][5] = $id_lizenzstatus[1];//$arr_player[5][$k];
          $doppel = DBMapper::get_Doppel_nr($arr_player[0][$k],
                                            $int_id_team, $id_verband);
          if ($doppel)
          {
          	//$arr_licenses[$k][2] .= " (".$doppel.")";
          	// neu 4.10.2011 - Anzeige ob Heimatverein oder Zweitlizenz statt Ordnungsnummer
            	$arr_list_vereine = DBM_Spieler::get_Verein_by_Spieler($arr_player[0][$k]);
            	for ($n=0; $n<count($arr_list_vereine[0]); $n++ )
            	{
            		if ( $arr_list_vereine[0][$n] == $id_verein )
            		{
            			// Erstverein
            			if ($arr_list_vereine[1][$n])
            			{
            				$arr_licenses[$k][2] .= " (1)";			
            			}
            			else 
            			{
            				$arr_licenses[$k][2] .= " (2)";
            			}
            		}
            	}
          }
        }
      }
      return $arr_licenses;
    }
  }


  private function write_lizenzen($int_id_liga) {
    $header = array('Spielername', 'Geb.-Datum', 'Nationalität', 'bearbeitet', 'Lizenz');

    // Teams holen
    $Liga = DBM_Ligen::read_Liga($int_id_liga);
    $id_kategorie = $Liga->get_int_id_kategorie();
    if ( $id_kategorie == 3
         || $id_kategorie == 4
         || $id_kategorie == 100
         || $id_kategorie == 101) {
      $arr_teams = DBMapper::get_list_Mannschaft_of_Pokal($int_id_liga,
                                                   DBMapper::get_id_Verband());
    } else {
      $arr_teams = DBMapper::get_list_Mannschaft_of_Liga($int_id_liga, 1);
    }

    for ($i=0; $i<count($arr_teams[0]); $i++) {
      // Lizenzen für Team holen
      $arr_lizenzen = $this->get_lizenzen_by_team($arr_teams[0][$i],
                                                  $arr_teams[2][$i],
                                                  $arr_teams[3][$i]);

      $data = $arr_lizenzen;

      if ($data) {
        $this->Ln(20);
        $this->write_verein($arr_teams[1][$i]);
        $ret_value = $this->FancyTable($header, $data);
        if ($ret_value !== TRUE) {
          $this->AddPage();
          $this->Ln(20);
          $this->write_verein($arr_teams[1][$i], true);
          $data = array_slice($data, $ret_value);
          $this->FancyTable($header, $data);
        }
        $this->Ln(10);
        if ($i < (count($arr_teams[0])-1)) {
          $this->AddPage();
        }
      }
    }
  }


  private function write_verein($str_verein, $bool_cont = FALSE) {
    if ($bool_cont) {
      $this->SetFont('helvetica', 'B', 11);
      $this->Cell(20, 5.5, 'Verein: ', 0,0, 'L');
      $this->SetFont('helvetica', '', 11);
      $this->Cell(0, 5.5, $str_verein . '  -  Fortsetzung', 0, 1, 'L');
    } else {
      $this->SetFont('helvetica', 'B', 12);
      $this->Cell(20, 5.5, 'Verein: ', 0, 0, 'L');
      $this->SetFont('helvetica', '', 12);
      $this->Cell(0, 5.5, $str_verein ,0 ,1, 'L');
    }
    $this->Ln(5);
  }


  //Colored table
  private function FancyTable($header, $data) {
    //Colors, line width and bold font
    //$this->SetFillColor(255,0,0);
    $this->SetTextColor(0);
    //$this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('helvetica','B',10);
    //Header
    $w = array(65,30,32,30,32);
    for ($i=0; $i<count($header); $i++) {
      if ($i == 0) {
        $this->Cell($w[$i],5.2,$header[$i],'LTB',0,'C',false);
      } elseif ($i+1 == count($header)) {
        $this->Cell($w[$i],5.2,$header[$i],'RTB',0,'C',false);
      } else {
        $this->Cell($w[$i],5.2,$header[$i],'TB',0,'C',false);
      }
    }
    $this->Ln(5.5);
    //Color and font restoration
    $this->SetFillColor(230,230,230);
    $this->SetTextColor(0);
    $this->SetFont('helvetica','',9);
    //Data
    $fill = true;
    $j = 0;
    foreach ($data as $row) {
      if ($this->GetY() >= 270) {
        $this->Cell(array_sum($w),0,'','T');
        return $j;
      }
      $this->Cell($w[0],4.8,$row[1],'L',0,'L',$fill);
      $this->Cell($w[1],4.8,$row[3],0,0,'C',$fill);
      $this->Cell($w[2],4.8,$row[4],0,0,'C',$fill);
      $this->Cell($w[3],4.8,$row[5],0,0,'C',$fill);
      $this->Cell($w[4],4.8,$row[2],'R',0,'C',$fill);
      $this->Ln();
      $fill =! $fill;
      $j++;
    }
    $this->Cell(array_sum($w),0,'','T');
    return TRUE;
  }


}
?>