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


function generateEventIDReverse($final_id)
{
    $prefix = 'AP23-E';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }

    $event_id = (int) $final_id;
    return $event_id;
}

// Define response object
$response = array();
$final_response = array();

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// initate total count variables
$total_users = 0;
$total_teams = 0;

// Validate admin token
if ($count == 1) {
    $row = mysqli_fetch_assoc($result);

    // Get club id from Admin Data 
    $club_id = $row['admin_id'];

    // Send event_id to find event registration details
    $event_id = $_POST['event_id'];
    $event_id = generateEventIDReverse($event_id);

    // Check if it is a valid club event or not
    $query = "SELECT * FROM `events` WHERE `club_id` = '$club_id' AND `event_id` = '$event_id' ";
    $res1 = mysqli_query($conn, $query);
    $count = mysqli_num_rows($res1);
    $rows = mysqli_fetch_assoc($res1);

    // If it is a valid club event
    if ($count) {
        $event_name = $rows['event_name'];
        $team_event = $rows['team_event'];
        $query1 = "SELECT * FROM `event_registration` WHERE `event_id` = '$event_id' ";
        $res2 = mysqli_query($conn, $query1);
        $count2 = mysqli_num_rows($res2);

        $i = 0;
        while ($count2) {
            $rows2 = mysqli_fetch_assoc($res2);
            $rows2['event_id'] = generateEventID($rows2['event_id']);
            $response[$i]['event_id'] = $rows2['event_id'];
            $response[$i]['event_name'] = $event_name;
            $query2 = "SELECT * FROM `users` WHERE `user_id` = '$rows2[user_id]'";
            $res3 = mysqli_query($conn, $query2);
            $rows3 = mysqli_fetch_assoc($res3);
            $response[$i]['user_name'] = $rows3['name'];
            $rows2['user_id'] = generateUserID($rows2['user_id']);
            $response[$i]['user_id'] = $rows2['user_id'];
            $response[$i]['registration_time'] = $rows2['registration_time'];

            // For team events
            if ($team_event) {
                $query3 = "SELECT * FROM `teams` WHERE `team_id` = '$rows2[team_id]'";
                $res4 = mysqli_query($conn, $query3);
                $rows4 = mysqli_fetch_assoc($res4);
                $response[$i]['team_name'] = $rows4['team_name'];
                $rows2['team_id'] = generateTeamID($rows2['team_id']);
                $response[$i]['team_id'] = $rows2['team_id'];
            }
            // For non-team events
            else {
                $response[$i]['team_id'] = $rows2['team_id'];
                $total_users++;
            }
            $count2--;
            $i++;

        }
    } else {
        $final_response['message'] = "Invalid Event ID with the Club";
    }

    // Count total teams registered
    if ($team_event) {
        $query4 = "SELECT DISTINCT `team_id` FROM `event_registration` WHERE `team_id`!= '0' ";
        $res5 = mysqli_query($conn, $query4);
        $total_teams = mysqli_num_rows($res5);
        $final_response['total_teams'] = $total_teams;
    }
    // Count total users registered
    else {
        $final_response['total_users'] = $total_users;
    }

    // Send response json
    $final_response['registration'] = $response;
    $final_response_JSON = json_encode($final_response);
    echo $final_response_JSON;
}
// If invalid token or session
else {
    $final_response['message'] = "Invalid Token or Session!";
    echo $final_response_JSON;
    exit;
}


?>