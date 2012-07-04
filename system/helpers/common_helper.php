<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( !function_exists('set_search_date')) 
{
    function set_search_date($mode) 
    {
        $iTime = time();
        return array(
            'sStartDate' => date('Y-m-d', strtotime($mode)),
            'sEndDate' => date('Y-m-d', $iTime)
        );
    }
}