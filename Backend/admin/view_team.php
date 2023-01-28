<?php

// Establish Connection
if (!defined('DB_SERVER')) {
    require_once("../assets/initialize.php");
}

$host = DB_SERVER;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$database = DB_NAME;

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed!" . mysqli_connect_error());
}

function generateTeamID($team_id)
{
    if ($team_id < 10) {
        $final_id = 'AP23-T00' . $team_id;
    } elseif ($team_id < 100) {
        $final_id = 'AP23-T0' . $team_id;
    } else {
        $final_id = 'AP23-T' . $team_id;
    }
    return $final_id;
}

function generateEventID($event_id)
{
    if ($event_id < 10) {
        $final_id = 'AP23-E00' . $event_id;
    } elseif ($event_id < 100) {
        $final_id = 'AP23-E0' . $event_id;
    } else {
        $final_id = 'AP23-E' . $event_id;
    }
    return $final_id;
}

function generateUserID($user_id)
{
    if ($user_id < 10) {
        $final_id = 'AP23-000' . $user_id;
    } elseif ($user_id < 100) {
        $final_id = 'AP23-00' . $user_id;
    } elseif ($user_id < 1000) {
        $final_id = 'AP23-0' . $user_id;
    } else {
        $final_id = 'AP23-' . $user_id;
    }
    return $final_id;
}

function generateTeamIDReverse($final_id)
{
    $prefix = 'AP23-T';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }

    $team_id = (int) $final_id;
    return $team_id;
}

// Define response object
$response = new stdClass();
// Define User object
$users = array();

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// Validate admin token
if ($count == 1) {
    // Input team_id
    $team_id = $_POST['team_id'];
    $team_id = generateTeamIDReverse($team_id);

    // Get team details
    $query = "SELECT * FROM `teams` WHERE `team_id` = '$team_id' ";
    $res = mysqli_query($conn, $query);
    $rows = mysqli_fetch_assoc($res);
    $response->team_id = generateTeamID($rows['team_id']);
    $response->team_name = $rows['team_name'];

    // Get event associated with the team
    $query1 = "SELECT * FROM `events` WHERE `event_id` = '$rows[event_id]' ";
    $res1 = mysqli_query($conn, $query1);
    $rows1 = mysqli_fetch_assoc($res1);
    $response->event_id = generateEventID($rows['event_id']);
    $response->event_name = $rows1['event_name'];

    // Get users registered with the team
    $query2 = "SELECT * FROM `event_registration` WHERE `team_id` = '$team_id' ";
    $res2 = mysqli_query($conn, $query2);
    $count2 = mysqli_num_rows($res2);
    $i = 0;
    while ($count2) {
        $rows2 = mysqli_fetch_assoc($res2);
        $query3 = "SELECT * FROM `users` WHERE `user_id` = '$rows2[user_id]' ";
        $res3 = mysqli_query($conn, $query3);
        $rows3 = mysqli_fetch_assoc($res3);
        $users[$i]['user_id'] = generateUserID($rows3['user_id']);
        $users[$i]['name'] = $rows3['name'];
        $count2--;
        $i++;
    }

    // Send users array as response
    $response->users = $users;

    $response_JSON = json_encode($response);
    echo $response_JSON;
}
// If invalid token or session
else {
    $response->message = "Invalid Token or Session!";
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}


?>