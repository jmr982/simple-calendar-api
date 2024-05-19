<?php
// Change location if needed. 
include '../database_pdo.php';
include '../error_message.php';
include '../validate_request.php';

// Change index if needed.
$calendar = explode('/', $_SERVER['REQUEST_URI'])[2];

$table = getCalendar($calendar);

if (isset($_SERVER['QUERY_STRING'])) {
    $query = $_SERVER['QUERY_STRING'];
}

print_r($_SERVER['REQUEST_URI']);
echo "$calendar $query";

$body = file_get_contents('php://input');

// Array of allowed fields. 'start' and 'end' are required for POST.
$fields = array('start', 'end', 'subject', 'description');
/*
try {
    $table = getCalendar($calendar);
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            parse_str($query, $params);
            $response = getEvents($params);        
            break;
        case 'POST':
            $params = json_decode($body, true);
            validateRequest($params);
            $response = addEvent($params, $fields);
            break;
        case 'PUT':
            $params = json_decode($body, true);
            validateRequest($params);
            $response = updateEvent($params, $fields);
            break;
        case 'DELETE':
            parse_str($query, $params);
            $response = deleteEvent($params);
            break;
        default:
            header('HTTP/1.1 403 Forbidden');
            $response = array('error'=>'403 forbidden');
    }
} catch (Exception $e) {
    $response['error'] = $e->getCode();
} finally {
    /* 
      This hides all errors except for '403 forbidden' and those specified in 
      error_message.php. Comment out for testing.
    */
/*
    if (isset($response['error']) && $response['error'] != '403 forbidden') {
        $response['error'] = errorMessage($response['error']);
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response) . "\n";
}
*/