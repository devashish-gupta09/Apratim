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

    // Send event id to Edit
    $event_name = $_POST['event_name'];

    // Fetch event details from `events`
    $query = "SELECT * FROM `events` WHERE `club_id` = '$club_id' AND `event_name` = '$event_name' ";
    $result1 = mysqli_query($conn, $query);
    $count1 = mysqli_num_rows($result1);

    if ($count1 == 1) {

        $details = mysqli_fetch_assoc($result1);

        // Initialize with old event values
        $event_id = $details['event_id'];
        $event_date_time = $details['event_date_time'];
        $venue = $details['venue'];
        $description = $details['description'];
        $image = $details['image'];

        $response = new stdClass();
        $response->club_id = $club_id;
        $response->event_id = $event_id;

        // Update Event Name
        if (isset($_POST['new_event_name'])) {
            $event_name = $_POST['new_event_name'];
            $update_event_name = "UPDATE `events` SET `event_name` = '$event_name' WHERE `event_id` = '$event_id' ";
            $update_event_name1 = mysqli_query($conn, $update_event_name) or die("Error updating the Event Name");
            $response->event_name = $event_name;
        }

        // Update Event Date & Time
        if (isset($_POST['event_date_time'])) {
            $event_date_time = $_POST['event_date_time'];
            $update_event_date_time = "UPDATE `events` SET `event_date_time` = '$event_date_time' WHERE `event_id` = '$event_id' ";
            $update_event_date_time1 = mysqli_query($conn, $update_event_date_time) or die("Error updating the Event Date & Time");
            $response->event_date_time = $event_date_time;
        }

        // Update Event Venue
        if (isset($_POST['venue'])) {
            $venue = $_POST['venue'];
            $update_venue = "UPDATE `events` SET `venue` = '$venue' WHERE `event_id` = '$event_id' ";
            $update_venue1 = mysqli_query($conn, $update_venue) or die("Error updating the Event Venue");
            $response->venue = $venue;
        }

        // Update Event Description
        if (isset($_POST['description'])) {
            $description = $_POST['description'];
            $update_description = "UPDATE `events` SET `description` = '$description' WHERE `event_id` = '$event_id' ";
            $update_description1 = mysqli_query($conn, $update_description) or die("Error updating the Event description");
            $response->description = $description;
        }

        // Update Event Image
        if (isset($_POST['image'])) {
            $image = $_POST['image'];
            $update_image = "UPDATE `events` SET `image` = '$image' WHERE `event_id` = '$event_id' ";
            $update_image1 = mysqli_query($conn, $update_image) or die("Error updating the Event image");
            $response->image = $image;
        }

        // Successful updation of the required attributes
        $response->message = "Event details updated successfully";
        $response_JSON = json_encode($response);
        echo $response_JSON;

    } else {
        echo "No such event found!";
    }

}
// If invalid token or session
else {
    echo "Invalid Token or Session!";
}

?>