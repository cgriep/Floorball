<?php
/**
 * Zeigt die Schiris und ihre Einsätze an
 *
 * @package    RSK
 * @subpackage Sites
 */

$tabelSchiri = RSK::showTableSchiri();

HTMLGen::printToEcho($tabelSchiri);

?>
