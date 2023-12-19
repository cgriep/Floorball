<?php

/****************************************************************************
 *                                menue.php                                 *
 *                          ---------------------                           *
 *  Hier ist das Menue enthalten.                                           *
 *                                                                          *
 *                                                                          *
 ****************************************************************************/


if ( !defined('IN_SMU') )
{
	die("Dies ist nicht die Einstiegseite");
}

$menuBar = new MenuBar();

if (Login::isloggedin()) {
  if(Login::isInGroup("ADMIN")) {

    $menuBar->addMenu(new Menu("Home", "index.php"));
    $menuBar->addMenu(new Menu("Verein", "index.php?seite=Vereine"));
    $menuBar->addMenu(new Menu("Ligen", "index.php?seite=Ligen"));
    $menuBar->addMenu(new Menu("Spieltage", "index.php?seite=Spieltage"));
    $menuBar->addMenu(new Menu("Accounts", "index.php?seite=Accounts"));
    $menuBar->addMenu(new Menu("Personen", "index.php?seite=Personen"));
    $menuBar->addMenu(new Menu("Lizenzen", "index.php?seite=Lizenzen"));
    $menuBar->addMenu(new Menu("Lizenzlisten", "index.php?seite=Print"));
    $menuBar->addMenu(new Menu("Spielberichte", "index.php?seite=Spielberichte"));
    $menuBar->addMenu(new Menu("Schiri-Liste", "index.php?seite=Schiri"));
    $menuBar->addMenu(new Menu("Schiri-Statistik", "index.php?seite=Schiriliste"));
    $menuBar->addMenu(new Menu("SG", "index.php?seite=SG"));

    $menuBar->addMenu(new Menu("Einstellungen", "index.php?seite=Einstellungen"), MenuBar::RIGHT);

  } else if(Login::isInGroup("SBK")) {

    $menuBar->addMenu(new Menu("Home", "index.php"));
    $menuBar->addMenu(new Menu("Verein", "index.php?seite=Vereine"));
    $menuBar->addMenu(new Menu("Ligen", "index.php?seite=Ligen"));
    $menuBar->addMenu(new Menu("Spieltage", "index.php?seite=Spieltage"));
    $menuBar->addMenu(new Menu("Accounts", "index.php?seite=Accounts"));
    $menuBar->addMenu(new Menu("Personen", "index.php?seite=Personen"));
    $menuBar->addMenu(new Menu("Lizenzen", "index.php?seite=Lizenzen"));
    $menuBar->addMenu(new Menu("Lizenzlisten", "index.php?seite=Print"));
    $menuBar->addMenu(new Menu("Schiri-Liste", "index.php?seite=Schiri"));
    $menuBar->addMenu(new Menu("Schiri-Statistik", "index.php?seite=Schiriliste"));
    $menuBar->addMenu(new Menu("Spielberichte", "index.php?seite=Spielberichte"));
    $menuBar->addMenu(new Menu("SG", "index.php?seite=SG"));
    $menuBar->addMenu(new Menu("DM", "index.php?seite=DM"));

    $menuBar->addMenu(new Menu("Einstellungen", "index.php?seite=Einstellungen"), MenuBar::RIGHT);

  } else if(Login::isInGroup("SK")) {
    $menuBar->addMenu(new Menu("Home", "index.php"));
    $menuBar->addMenu(new Menu("Schiri-Liste", "index.php?seite=Schiri"));
    $menuBar->addMenu(new Menu("Schiri-Statistik", "index.php?seite=Schiriliste"));
    $menuBar->addMenu(new Menu("Einstellungen", "index.php?seite=Einstellungen"), MenuBar::RIGHT);

  } else if(Login::isInGroup("VM")) {

    $menuBar->addMenu(new Menu("Home", "index.php"));
    $menuBar->addMenu(new Menu("Lizenzen", "index.php?seite=VereinsManager"));
    $menuBar->addMenu(new Menu("Spieler", "index.php?seite=Spieler"));
    //$menuBar->addMenu(new Menu("Betreuer", "index.php?seite=Personen"));
    $menuBar->addMenu(new Menu("Accounts", "index.php?seite=Accounts"));
    $menuBar->addMenu(new Menu("Lizenzlisten", "index.php?seite=Print"));

    $menuBar->addMenu(new Menu("Spielberichte", "index.php?seite=Spielberichte"));
    $menuBar->addMenu(new Menu("Einstellungen", "index.php?seite=Einstellungen"), MenuBar::RIGHT);

  } else if(Login::isInGroup("TM")) {

    $menuBar->addMenu(new Menu("Home", "index.php"));
    $menuBar->addMenu(new Menu("Lizenzen", "index.php?seite=VereinsManager"));
    $menuBar->addMenu(new Menu("Spieler", "index.php?seite=Spieler"));
    $menuBar->addMenu(new Menu("Lizenzlisten", "index.php?seite=Print"));
    //$menuBar->addMenu(new Menu("Betreuer", "index.php?seite=Personen"));

    $menuBar->addMenu(new Menu("Spielberichte", "index.php?seite=Spielberichte"));
    $menuBar->addMenu(new Menu("Einstellungen", "index.php?seite=Einstellungen"), MenuBar::RIGHT);

  }

} else { // nicht eingeloggt

  // Menü für die Spielpläne
  $menuSpielplan = new Menu("Spielpläne");
  // get id's of leagues
  $arr_leagues = DBM_Ligen::get_list_liga();
  // create menu for leagues
  for ($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuSpielplan->addSubEntry($Liga->get_str_name(), "index.php?seite=spielplan&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuSpielplan);

  // Menü für die Tabellen
  $menuTabellen = new Menu("Tabellen");

  // get id's of leagues
  $arr_tmp = DBM_Ligen::get_list_liga();
  $arr_leagues[0] = array();
  $arr_leagues[1] = array();
  $arr_leagues[2] = array();
  for($i=0; $i<count($arr_tmp[0]); $i++) {
    if($arr_tmp[3][$i] != -3 && $arr_tmp[3][$i] != -4) {
      $arr_leagues[0][] = $arr_tmp[0][$i];
      $arr_leagues[1][] = $arr_tmp[1][$i];
      $arr_leagues[2][] = $arr_tmp[2][$i];
    }
  }

  // create menu for leagues
  for($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuTabellen->addSubEntry($Liga->get_str_name(), "index.php?seite=table&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuTabellen);

  // Menü für die Scorer
  $menuScorer = new Menu("Scorer");
  $arr_leagues = DBM_Ligen::get_list_liga();
  // create menu for leagues
  for($i=0; $i<count($arr_leagues[0]); $i++) {
    $Liga = DBM_Ligen::read_Liga($arr_leagues[0][$i]);
    $menuScorer->addSubEntry($Liga->get_str_name(), "index.php?seite=scorer&amp;table=".$arr_leagues[0][$i]);
  }
  $menuBar->addMenu($menuScorer);

}

$HTML->setMenuBar($menuBar);

?>