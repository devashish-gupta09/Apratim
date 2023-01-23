<?php

$connection = mysqli_connect("localhost", "root", "", "apratim");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// function to generate event id
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

// function to decode the team id
function generateTeamIDReverse($final_id)
{
    $prefix = 'AP23-T';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }
    $team_id = (int) $final_id;
    return $team_id;
}

// get details of all team members
$team_id = generateTeamIDReverse($_POST['team_id']);
$user_token = $_POST['user_token'];

// check if the user is logged in
$cur_user_id = "";

$query = "SELECT * FROM users WHERE user_token = '$user_token'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    $response = array(
        "message" => "You are not logged in!"
    );
    echo json_encode($response);
    exit();
} else {
    $row = mysqli_fetch_assoc($result);
    $cur_user_id = $row['user_id'];
}

// check if the team exists
$team_name = "";
$event_id = "";

$query = "SELECT * FROM teams WHERE team_id = '$team_id'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    $response = array(
        "message" => "Team does not exist!"
    );
    echo json_encode($response);
    exit();
} else {
    $row = mysqli_fetch_assoc($result);
    $team_name = $row['team_name'];
    $event_id = $row['event_id'];
}

// get the event name
$event_name = "";

$query = "SELECT * FROM events WHERE event_id = '$event_id'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    $response = array(
        "message" => "Event does not exist!"
    );
    echo json_encode($response);
    exit();
} else {
    $row = mysqli_fetch_assoc($result);
    $event_name = $row['event_name'];
}

// check if the user is a member of the team
$query = "SELECT * FROM event_registration WHERE team_id = '$team_id' AND user_id = '$cur_user_id'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    $response = array(
        "message" => "You are not a member of this team!"
    );
    echo json_encode($response);
    exit();
}

// get all team members user_id
$team_members_id = array();

$query = "SELECT * FROM event_registration WHERE team_id = '$team_id'";
$result = mysqli_query($connection, $query);
// if no team members found, then return the current user only
if (mysqli_num_rows($result) == 0) {
    $team_members_id[] = $cur_user_id;
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $team_members_id[] = $row['user_id'];
    }
}

// get details of all team members
$team_members = array();

foreach ($team_members_id as $member_id) {
    $query = "SELECT * FROM users WHERE user_id = '$member_id'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    // return all columns except password, registration_time, last_login_time, user_token
    unset($row['password']);
    unset($row['registration_time']);
    unset($row['last_login_time']);
    unset($row['user_token']);

    $team_members[] = $row;
}

$response = array(
    "message" => "Team members fetched successfully!",
    "team_name" => $team_name,
    "event_id" => generateEventID($event_id),
    "event_name" => $event_name,
    "team_members" => $team_members
);

echo json_encode($response);
