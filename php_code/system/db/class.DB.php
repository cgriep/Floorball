<?php
/**
 * DB
 *
 * Diese Klasse stellt eine Verbindung zu einer MySQL Datenbank her.
 *
 * @package DB
 *
 * @author Michael Rohn <mrohn@gmx.de>
 * @copyright 2009 MR2
 * @version 0.5
 * @access public
 *
 * @todo Fehlermeldungen überarbeiten
 * @todo Den Query sicher machen
 * @todo Den Query testen
 *
 */
class DB {

  private $dbLink = FALSE;   // Offene Datenbankverbindung
  private $host;     // Datenbankhost
  private $port;     // Datenbankport
  private $user;     // Benutzer der Datenbank
  private $passwd;   // Passwort zur Datenbank
  private $dbName;   // Name der DB
  private $sqlError; // Die letzte SQL Fehlermeldung
  private $sqlErrno; // Die letzte SQL Fehlernummer
  private $errno;    // Nummer des letzten Fehlers
  private $last_id;  // Letze erstellte id speichern

  private $logDBLink = NULL; // Offene Verbindung zur Log-DB

  const ERR_NO_HOST    = 100;
  const ERR_DB_CLOSE   = 102;
  const ERR_DB_QUERY   = 103;
  const ERR_DB_SELECT  = 104;
  const ERR_NO_LINK    = 105;


  private $arr_error_msg =
          array(self::ERR_NO_HOST    => "Es konnte keine Verbindung mit dem Datenbank-Server hergestellt werden.",
                self::ERR_DB_CLOSE   => "Es ist ein Fehler beim Schliessen der Datenbank aufgetreten.",
                self::ERR_DB_QUERY   => "Die Datenbank-Anfrage konnte nicht ausgef&uuml;hrt werden.",
                self::ERR_DB_SELECT  => "Die DB konnte nicht ausgew&auml;hlt werden.",
                self::ERR_NO_LINK    => "Es besteht keine Verbindung zur Datenbank.");


  public function __construct($dbhost, $dbport, $dbuser, $dbpasswd, $dbname) {
    $this->host   = $dbhost;
    $this->port   = $dbport;
    $this->user   = $dbuser;
    $this->passwd = $dbpasswd;
    $this->dbName = $dbname;
    // hier der Part fürs Logging
    $this->connectToLogDB();
  }

  private function connectToLogDB() {
    if (defined('SQL_LOG')
        && SQL_LOG
        && $this->dbName != DB_USERS_NAME
        && $this->logDBLink == NULL) {

      if ( $tmp_db = @mysql_connect(DB_LOG_HOST . DB_LOG_PORT, DB_LOG_USER, DB_LOG_PASSWD) ) {
        if (@mysql_select_db(DB_LOG_NAME, $tmp_db)) {
          $this->logDBLink = $tmp_db;
        } else {
          @mysql_close($tmp_db);
        }
      }

    }
  }


  private function log_sql($sql_query_to_log, $erfolgreich) {
    if ( $this->logDBLink != NULL ) {
      if ( SQL_LOG_TYPE == 1
           || $erfolgreich == 0 ) {
        if ( defined('TESTSYSTEM') && TESTSYSTEM ) {
          $sql_query = "INSERT INTO test_" . VERBAND . "
                        SET sql_query = \"$sql_query_to_log\",
                        erfolgreich = $erfolgreich";
        } else {
          $sql_query = "INSERT INTO " . VERBAND . "
                        SET sql_query = \"$sql_query_to_log\",
                        erfolgreich = $erfolgreich";
        }
        mysql_query($sql_query, $this->logDBLink);
      }
    }
  }


  public function connect() {
    // Prüfen ob schon eine Verbindung besteht
    if ($this->dbLink) {
      return TRUE;
    }

    // DB-Verbindung herstellen
    if (!$dbLink = mysql_connect($this->host . $this->port, $this->user, $this->passwd)) {
      $this->dbLink = FALSE;
      $this->setError(self::ERR_NO_HOST);
      return FALSE;
    }
    $this->dbLink = $dbLink;

    // DB auswählen
    if (!$this->selectDB()) {
       $this->close();
       return FALSE;
    }
    mysql_query("SET NAMES 'utf8'", $this->dbLink);
    return TRUE;
  }


  private function selectDB() {
    // Prüfen ob eine Verbindung besteht
    if (!$this->dbLink) {
      $this->setError(self::ERR_NO_LINK);
      return FALSE;
    }

    // DB auswählen
    if (!mysql_select_db($this->dbName, $this->dbLink)) {
      $this->setError(self::ERR_DB_SELECT);
      $this->dbName = FALSE;
      return FALSE;
    }
    return TRUE;
  }


  public function query($sql_query) {
    // Verbindung erstellen
    if (!$this->connect()) {
      // Eine Fehlernummer wird schon in connect angelegt.
      return FALSE;
    }

    // Anfrage durchführen
    if ($sql_result = mysql_query($sql_query, $this->dbLink)) {
      $this->last_id = mysql_insert_id($this->dbLink);
      $this->log_sql($sql_query, 1);
      // Prüfen ob da was im sql_result drin ist
      if ( ($sql_result !== TRUE)
            && !mysql_num_rows($sql_result) ) {
        return NULL;
      }
      return $sql_result;
    } else {
      $this->setError(self::ERR_DB_QUERY);
      $this->log_sql($sql_query, 0);
      return FALSE;
    }
  }


  public function query_insert($sql_query) {

    // Anfrage durchführen
    if ($this->query($sql_query)) {
      return $this->last_id;
    } else {
      // Fehlermeldung generieren
      print mysql_error();
      return FALSE;
    }

    // letzte ID wiedergeben
    return $this->last_id;
  }


  public function close() {

    // Prüfen ob eine Verbindung besteht
    if (!$this->dbLink) {
      $this->setError(self::ERR_NO_LINK);
      return FALSE;
    }

    // DB-Verbindung schliessen
    if (!mysql_close($this->dbLink)) {
      $this->setError(self::ERR_DB_CLOSE);
      return FALSE;
    }
    $this->dbLink = FALSE;
    return TRUE;
  }


  private function setError($int_errorno){
    $this->errno  = $int_errorno;
    $this->sqlError = mysql_error();
    $this->sqlErrno = mysql_errno();
  }


  public function getSqlError() {
    return $this->sqlError;
  }


  public function getSqlErrno() {
    return $this->sqlErrno;
  }


  public function getErrno() {
    return $this->errno;
  }


  public function getErrmsg() {
    return $this->arr_error_msg[$this->errno];
  }


  public function get_dblink() {
    return $this->dbLink;
  }


  public function __destruct() {
    if ($this->logDBLink != NULL) {
      mysql_close($this->logDBLink);
      $rhis->logDBLink = NULL;
    }

    $this->close();
  }


} // class

?>