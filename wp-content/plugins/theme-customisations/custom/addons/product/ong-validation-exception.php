<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */


/**
 * Class Ong_Validation_Exception
 */
class Ong_Validation_Exception extends Exception
{
    private $tests = [];

    function __construct($message, $test_results = [])
    {
        parent::__construct($message);
        $this->tests = $test_results;
    }

    function getTestResults()
    {
        return $this->tests;
    }
}
