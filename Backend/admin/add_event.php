<?php

// Establish Connection
include("db_connection.php");

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

    // Query to add event details
    $add = "INSERT INTO `events`(`event_name`, `event_date_time`, `venue`, `description`, `image`, `club_id`) VALUES ('$event_name','$event_date_time','$venue','$description','$image','$club_id')";

    $add_query = mysqli_query($conn, $add) or die("Error adding the Event.");

    $response = new stdClass();
    $response->message = "New Event Created";
    $response->club_id = $club_id;
    $response->event_name = $event_name;
    $response->event_date_time = $event_date_time;
    $response->venue = $venue;
    $response->description = $description;
    $response->image = $image;
    $response_JSON = json_encode($response);
    echo $response_JSON;

}
// If invalid token or session
else {
    echo "Invalid Token or Session!";
}


?>