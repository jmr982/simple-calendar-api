<?php
/*  
  This function uses 'Basic' authorization credentials from the Authorization 
  header to authenticate to the database. Database host and name are read from 
  environmental variables. Returns a connection object or throws an exception. 
*/
function dbConnect() {
    try {
        $connString = 
            "mysql:host={$_SERVER['DB_HOST']};dbname={$_SERVER['DB_NAME']}";
        $conn = new PDO(
            $connString, 
            $_SERVER['PHP_AUTH_USER'], 
            $_SERVER['PHP_AUTH_PW']
        );      
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        throw new Exception($e->getCode());
    }
}

/*  
  The function accepts as parameters the SQL statement and parameters used 
  with the prepare and execute methods. Returns a dictionary containing either 
  a success or error message.
*/
function sqlStatement($statement, $params = array()) {
    try {
        $conn = dbConnect();
        $prepared = 
            $conn->prepare(
                $statement, 
                [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]
            );
        $prepared->execute($params);
        $result = $prepared->fetchAll(PDO::FETCH_CLASS);
    } catch (Exception $e) {
        $result['error'] = $e->getCode();
    } finally {
        $conn = null;
        return $result;
    }
}
  
/*  
  Returns a single event using id or all events for between the given start 
  and end datetimes. Accepts as arguments the start and end datetime.
*/
function getEvents($params) {
    $select = 
        "SELECT BIN_TO_UUID(id) AS id, start, end, subject, description, added, modified 
        FROM Calendar ";

    if (array_key_exists('id', $params)) {
        $select .= "WHERE id=UUID_TO_BIN(:id)";
    } else {
        $select .= "WHERE start BETWEEN :start AND :end";      
    }
  
    $result = sqlStatement($select, $params);
    return $result;
}

/*
  Adds (inserts) an event to the calendar. Accepts as an argument a dictionary 
  containing the start and end datetimes, subject, and the description string.
*/
function addEvent($params, $fields) {
    $select = "SELECT UUID() AS id";
    $id = (array)sqlStatement($select)[0];
    $params['id'] = $id['id'];

    // Set missing $params to null.
    foreach ($fields as $key) {
        if (!array_key_exists($key, $params)) {
            $params[$key] = null;
        }
    }

    $insert = "INSERT INTO Calendar (id, start, end, subject, description)
    VALUES(UUID_TO_BIN(:id), :start, :end, :subject, :description)";
    $result = sqlStatement($insert, $params);

    if (!isset($result['error'])) {
        $result = getEvents($id);
    }

    return $result;
}

/*
  Updates an event with the given id. Accepts as arguments a dictionary 
  containing the parameters to be updated and an array containing the allowed 
  fields. Enables dynamically changing the statement to match the incoming 
  data.
*/
function updateEvent($params, $fields) {
    //Creates the statement.
    $update = "UPDATE Calendar SET ";
    
    // Dynamically add field(s) to update.
    foreach ($fields as $key) {
        if (array_key_exists($key, $params)) {
            $update .= "$key=:$key ";
        }
    }
    
    $update .= "WHERE id=UUID_TO_BIN(:id)";
    $result = sqlStatement($update, $params);

    if (!isset($result['error'])) {
        $result = getEvents(array('id'=>$params['id']));
    }

    return $result;
}

// Accepts as an argument the id of the event to be deleted.
function deleteEvent($params) {
    $delete = "DELETE FROM Calendar WHERE id=UUID_TO_BIN(:id)";
    $result = sqlStatement($delete, $params);

    if (!isset($result['error'])) {
        $result = array('success'=>'event deleted');
    }

    return $result;
}
