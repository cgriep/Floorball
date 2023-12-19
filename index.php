<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

<head>
  <title>SManager-Floorball</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="StyleSheet" href="fvn/styles/design.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="fvn/styles/form.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="fvn/styles/classes.css" type="text/css" media="all" />
  <link rel="StyleSheet" href="fvn/styles/statistik2.css" type="text/css" media="all" />
</head>

<body>

  <!-- Positionierung auf der Seite -->
  <div id="container">

  <!-- Anfang Kopfzeile -->
    <div id="kopfzeile" style="height: 80px;">
      <img id="sm_logo" src="fvn/images/sm_logo3.png" alt="SaisonManager Logo" />
    </div>
  <!-- Ende Kopfzeile -->

  <!-- Anfang Menue -->
    <div id="menue_rahmen">
      <img src="fvn/images/menue_bg_auslauf.gif" style="position:absolute; top:0px; right:0px;" alt="" />
    </div>
  <!-- Ende Menue -->

  <!-- Anfang Mittelteil -->
    <div id="inhalt">

      <br />Willkommen bei dem SaisonManager-Floorball.<br />
      <br />
      Hier finden Sie eine Liste der Spielbetriebe, die den SaisonManager - Floorball einsetzen:<br />
<?php
  if (FALSE) {
    $url = "test.sm-u.de";
  } else {
    $url = "sm-u.de";
  }
?>
      <ul style="margin: 20px 200px; text-align: left;">
        <li style="margin: 3px;">
          <a href="http://fvn.<?php echo $url; ?>">Floorball Niedersachsen - (ehemals NUB)</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://fvd.<?php echo $url; ?>">Floorball Deutschland - (ehemals DUB)</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://flv-sh.<?php echo $url; ?>">Floorball Verband Schleswig-Holstein</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://fvb.<?php echo $url; ?>">Floorball Verband Bayern - (ehemals BUV)</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://fvbw.<?php echo $url; ?>">Floorball Verband Baden-Württemberg - (ehemals BWUV)</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://fvbb.<?php echo $url; ?>">Berlin-Brandenburgischer Unihockey Verband</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://fvh.<?php echo $url; ?>">Floorball Verband Hessen - (ehemals HUV)</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://nwuv.<?php echo $url; ?>">Nordrhein-Westfälischer Floorball Verband</a>
        </li>
        <li style="margin: 3px;">
          <a href="http://sbkost.<?php echo $url; ?>">Spielbetriebskomission OST</a>
        </li>
      </ul>
      <br />
      <br />
      Die Daten der &auml;lteren Saisons sind unter folgendem Link zu finden:<br />
      <a href="http://saison2010-11.floorball-verband.de">Saison 2010/11</a><br />
      <a href="http://saison2011-12.floorball-verband.de">Saison 2011/12</a><br />
      <a href="http://saison2012-13.floorball-verband.de">Saison 2012/13</a><br />
    </div>
  <!-- Ende Mittelteil -->

  <!-- Anfang Fusszeile -->
    <div id="fusszeile">
      <img src="fvn/images/fusszeile_bg_auslauf.gif" style="position: absolute; top: 0px; left: 0px;" alt="" />
      Copyright by Malte R&ouml;mmermann &amp; Michael Rohn
      <span style="position:absolute; right:10px;">

        <a href="fvn/index.php?seite=impressum">Impressum</a>
      </span>
    </div>
  <!-- Ende Fusszeile -->

  </div>
  <!-- Ende der Positionierung -->


</body>

</html>

