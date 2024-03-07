<?php
/* 
  Helper function used with validateRequest. Validates datetime. Accepts as an 
  argument a datetime string. Returns true if the string matches the given 
  format and false if it does not. Based on the example (1) found on: 
  https://tecadmin.net/validate-date-string-in-php/
*/
function isDateTime($datetime) {
    $format = 'Y-m-d H:i:s';
    $isValid = DateTime::createFromFormat($format, $datetime);
    return $isValid && $isValid->format($format) == $datetime;
}

/*
  This function validates the content of an array. Used to validate the 
  request body of a POST or PUT request. Accepts as an argument the request 
  body as an array (json_decode). Throws an exception if the request does not 
  match the given specifications.
*/ 
function validateRequest($request) {    
    if (array_key_exists('start', $request) && !isDateTime($request['start'])) {
        throw new Exception("Invalid datetime", 666);
    }
    
    if (array_key_exists('end', $request) && !isDateTime($request['end'])) {
        throw new Exception("Invalid dateTime", 666);
    }
}
