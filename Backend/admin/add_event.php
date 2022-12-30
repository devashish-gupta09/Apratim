<?php

// Establish Connection
include("db_connection.php");

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

// Define response object
$response = new stdClass();

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// Validate admin token
if ($count == 1) {
    $row = mysqli_fetch_assoc($result);

    // Get club id from Admin Data
    $club_id = $row['admin_id'];

    // Send Event details to be added
    $event_name = $_POST['event_name'];
    $event_date_time = $_POST['event_date_time'];
    $venue = $_POST['venue'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $team_event = $_POST['team_event'];
    $team_size = $_POST['team_size'];

    // Check if Event Name already exists
    $check1 = "SELECT * FROM `events` WHERE `event_name` = '$event_name' ";
    $res1 = mysqli_query($conn, $check1);
    $count1 = mysqli_num_rows($res1);

    if ($count1 == 0) {
        // Query to add event details
        $add = "INSERT INTO `events`(`event_name`, `event_date_time`, `venue`, `description`, `image`, `club_id`, `team_event`, `team_size`) VALUES ('$event_name','$event_date_time','$venue','$description','$image','$club_id', '$team_event', '$team_size') ";
        $add_query = mysqli_query($conn, $add) or die("Error adding the Event.");

        // Fetch newly added event's details
        $fetch_new_event = "SELECT * FROM `events` WHERE `event_name` = '$event_name' AND `club_id` = '$club_id' ";
        $res = mysqli_query($conn, $fetch_new_event);
        $row1 = mysqli_fetch_assoc($res);

        //Generate New Event's ID in AP23-EXXX format
        $event_id = generateEventID($row1['event_id']);
        // Store other Event details
        $event_name = $row1['event_name'];
        $event_date_time = $row1['event_date_time'];
        $venue = $row1['venue'];
        $description = $row1['description'];
        $image = $row1['image'];
        $club_id = $row1['club_id'];
        $team_event = $row1['team_event'];
        $team_size = $row1['team_size'];

        // Get Event's Club name
        $query = "SELECT `club_name` FROM `clubs` WHERE `club_id` = '$club_id' ";
        $res2 = mysqli_query($conn, $query);
        $row2 = mysqli_fetch_assoc($res2);
        $club_name = $row2['club_name'];

        // Send Event details as response
        $response->message = "New Event Created";
        $response->event_id = $event_id;
        $response->event_name = $event_name;
        $response->event_date_time = $event_date_time;
        $response->venue = $venue;
        $response->description = $description;
        $response->image = $image;
        $response->club_name = $club_name;
        $response->team_event = $team_event;
        $response->team_size = $team_size;
        $response_JSON = json_encode($response);
        echo $response_JSON;

    }
    // Output error if Similar Event exist in the database
    else {
        $response->message = "Event with similar name already exists!!";
        $response_JSON = json_encode($response);
        echo $response_JSON;
        exit;
    }

}
// If invalid token or session
else {
    $response->message = "Invalid Token or Session!";
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}


?>