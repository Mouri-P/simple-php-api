<?php
include_once '../database/DatabaseConfig.php';
include_once '../database/models/employees.php';

header('Content-Type: application/json');

$dbConfig = new DatabaseConfig();
$db = $dbConfig->connect();

$method = $_SERVER['REQUEST_METHOD'];

$employees = new Employees($db);
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        get();
        break;
    case 'POST':
        post();
        break;
    case 'PUT':
        put();
        break;
    case 'DELETE':
        delete();
        break;
    default:
        notFound();
}

function get()
{
    global $employees;
    $queries = array();
    parse_str(array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : '', $queries);

    $queries['perpage'] = array_key_exists('perpage', $queries) ? $queries['perpage'] : 15;
    $queries['from'] = array_key_exists('page', $queries) ? $queries['page'] * $queries['perpage'] : 0;
    $queries['search'] = array_key_exists('search', $queries) ? '%' . $queries['search'] . '%' : '%';

    $allEmployees = $employees->read($queries);

    $result = array('page' => $queries['from'] / $queries['perpage'], 'perpage' => $queries['perpage'], 'data' => $data = array());

    while ($row = $allEmployees->fetch(PDO::FETCH_OBJ)) {
        array_push($result['data'], $row);
    }

    echo json_encode($result);
}

function post()
{
    global $employees, $data;

    $input = checkAndGetInput($data);

    sendResult($employees->create($input));
}

function put()
{
    global $employees, $data;

    $input = checkAndGetInput($data);

    sendResult($employees->update($input));
}
function delete()
{
    global $employees, $data;

    $input = checkAndGetInput($data);

    sendResult($employees->delete($input));
}

function sendResult($result)
{
    echo $result ? json_encode(array('result' => 'success')) : json_encode(array('result' => 'failed'));
}

function checkAndGetInput($data)
{
    if (!$data) {
        echo json_encode(array('result' => 'failed'));
        return false;
    } else {
        return array(
            'id' => property_exists($data, 'id') ? $data->id : null,
            'name' => property_exists($data, 'name') ? $data->name : null,
            'department' => property_exists($data, 'department') ? $data->department : null,
            'roll' => property_exists($data, 'roll') ? $data->roll : null,
            'join_date' => property_exists($data, 'join_date') ? $data->join_date : null,
        );
    }
}

function notFound()
{
    echo json_encode(array('result' => 'Not found'));
}
