<?php
// Change location if needed. 
include '../database_pdo.php';
include '../error_message.php';

if (isset($_SERVER['QUERY_STRING'])) {
    $query = $_SERVER['QUERY_STRING'];
}

$body = file_get_contents('php://input');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        parse_str($query, $params);
        $response = getEvents($params);        
        break;
    case 'POST':
        $params = json_decode($body, true);
        $response = addEvent($params);
        break;
    case 'PUT':
        // Array of allowed fields to be updated. Change if needed.
        $fields = array('start', 'end', 'subject', 'description');
        $params = json_decode($body, true);
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

if (isset($response['error']) && is_numeric($response['error'])) {
    $response['error'] = errorMessage($response['error']);
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response) . "\n";
