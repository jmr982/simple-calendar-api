# Simple PHP and MySQL calendar API

The aim of the project is to create a simple calendar API to store events. The 
project is inspired by calendars such as Google Calendar. 

The current version uses the PDO extension to communicate with a MySQL 
database. Authentication is done using the 'Authorization' request header. 
Currently, only 'Basic' authentication is supported.

#### Disclaimer
Please note that the project is in development and does not represent a 
working solution that can be put into production. If you find bugs or issues, 
please report them. If you are interested in working on the project, please do 
so. All help is welcome.

## Test the API
A test version of the API is live on https://simple-calendar-api.jmr982.serv00.net.
To test it, use the following credentials: 
* username = testuser
* password = eMNeM5t4vyTkHe5MYaxc2sy5&423~$

## Request - Implemented HTTP request methods 
### GET
List events by either id or start and end datetime.

Query string
* id = uuid    
* start = datetime
* end = datetime

#### Example 1
```
/?id=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
```
#### Example 2
```
/?start=YYYY-MM-DD%20HH:mm:ss&end=YYYY-MM-DD%20HH:mm:ss
```

### POST 
Add an event to the calendar.

Request body
* Required
    * start = datetime
    * end = datetime        
* Opional
    * subject = string
    * description = string

#### Example
```
{
  start: 'YYYY-MM-DD HH:mm:ss',
  end: 'YYYY-MM-DD HH:mm:ss',
  subject: 'This is an example subject',
  description: 'This is an example description'
}
```

### PUT 
Update an event using id.

Request body
* Required
    * id = uuid
* Optional
    * start = datetime
    * end = datetime
    * subject = string
    * description = string

#### Example
```
{
  id: 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
  description: 'This is an example update'
}
```

### DELETE
Delete an event using id.

Query string
* id = uuid

#### Example 
```
/?id=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
```

## Response - Returned data
All returned data is formatted as JSON. 

### Success
A successful request returns an object containing, depending on the used 
method, an array of events or a success message.

#### Examples
```
[
  {
    id: 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
    start: 'YYYY-MM-DD HH:mm:ss',
    end: 'YYYY-MM-DD HH:mm:ss',
    subject: 'This is an example subject',
    description: 'This is an example description'
    added: 'YYYY-MM-DD HH:mm:ss',
    modified: 'YYYY-MM-DD HH:mm:ss'
  }
]
```
### Error
An error message is returned when an error or exception occours. An error can 
be for example a missing or invalid username or password. Modify 
error_message.php to add your own custom message. 

#### Example
```
{
  error: 'This is an example error message'
}
```
