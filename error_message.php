<?php
/*
    This function accepts as an argument an MySQL error code. Returns a custom 
    error message.
*/
function errorMessage($code) {
    $errorMessages = array(
        '0' => 'invalid credentials',
        '22007' => 'invalid datetime'
    );
    
    if (!array_key_exists($code, $errorMessages)) {
        return 'invalid request';
    }
    
    return $errorMessages[$code];
}
