<?php

error_reporting(E_ALL);

/**
 * Filterklasse um Uservariablen zu verifizieren.
 *
 * @author Michael Rohn & Malte Ršmmerann
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085D-includes begin
// section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085D-includes end

/* user defined constants */
// section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085D-constants begin
// section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085D-constants end

/**
 * Filterklasse um Uservariablen zu verifizieren.
 *
 * @access public
 * @author Michael Rohn & Malte Ršmmerann
 */
class InputFilter
{
    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * †bernimmt die Variable bis zum ersten nicht numerischem Zeichen. Bei
     * Variable wird -1 zurŸckgegeben.
     *
     * @access public
     * @author Michael Rohn & Malte Ršmmermann
     * @param void
     * @return int
     */
    public function filter_int( void $param)
    {
        $returnValue = (int) 0;

        // section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085F begin
        // section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000085F end

        return (int) $returnValue;
    }

    /**
     * Filtert alle nicht erlaubten Zeichen aus einer Texteingabe.
     *
     * @access public
     * @author Michael Rohn & Malte Ršmmermann
     * @param string
     * @return string
     */
    public function filter_string($str_text)
    {
        $returnValue = (string) '';

        // section -64--88-0-104-7ca514c:1145451edbf:-8000:0000000000000862 begin
        // section -64--88-0-104-7ca514c:1145451edbf:-8000:0000000000000862 end

        return (string) $returnValue;
    }

    /**
     * Short description of method filter_email
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param string
     * @return boolean
     */
    public function filter_email($str_email)
    {
        $returnValue = (bool) false;

        // section -64--88-0-104-7ca514c:1145451edbf:-8000:0000000000000868 begin
        // section -64--88-0-104-7ca514c:1145451edbf:-8000:0000000000000868 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method filter_datum
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param string
     * @return boolean
     */
    public function filter_datum($str_datum)
    {
        $returnValue = (bool) false;

        // section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000086C begin
        // section -64--88-0-104-7ca514c:1145451edbf:-8000:000000000000086C end

        return (bool) $returnValue;
    }

} /* end of class InputFilter */

?>