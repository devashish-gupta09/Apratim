<?php

// Establish Connection
include("db_connection.php");

// Function to generate integer event_id
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

    // Send event id to Edit
    $event_id_char = $_POST['event_id'];
    // Convert event id to integer-type
    $event_id = generateEventIDReverse($event_id_char);

    // Fetch event details from `events`
    $query = "SELECT * FROM `events` WHERE `club_id` = '$club_id' AND `event_id` = '$event_id' ";
    $result1 = mysqli_query($conn, $query);
    $count1 = mysqli_num_rows($result1);

    // If the event exists
    if ($count1 == 1) {

        $details = mysqli_fetch_assoc($result1);

        // Initialize with old event values
        $event_name = $details['event_name'];
        $event_date_time = $details['event_date_time'];
        $venue = $details['venue'];
        $description = $details['description'];
        $image = $details['image'];
        $club_id = $details['club_id'];
        $team_event = $details['team_event'];
        $team_size = $details['team_size'];


        // Update Event Name
        if (isset($_POST['event_name'])) {
            $event_name = $_POST['event_name'];

            // Check if Event Name already exists
            $check1 = "SELECT * FROM `events` WHERE `event_name` = '$event_name' ";
            $res1 = mysqli_query($conn, $check1);
            $count2 = mysqli_num_rows($res1);

            // If no such event exists - UPDATE
            if ($count2 == 0) {
                $update_event_name = "UPDATE `events` SET `event_name` = '$event_name' WHERE `event_id` = '$event_id' ";
                $update_event_name1 = mysqli_query($conn, $update_event_name) or die("Error updating the Event Name");
            }
            // Send error and exit in case of duplicate event.
            else {
                $response->message = "Can't update event name. Similar event already exists!";
                $response_JSON = json_encode($response);
                echo $response_JSON;
                exit;
            }

        }

        // Update Event Date & Time
        if (isset($_POST['event_date_time'])) {
            $event_date_time = $_POST['event_date_time'];
            $update_event_date_time = "UPDATE `events` SET `event_date_time` = '$event_date_time' WHERE `event_id` = '$event_id' ";
            $update_event_date_time1 = mysqli_query($conn, $update_event_date_time) or die("Error updating the Event Date & Time");
        }

        // Update Event Venue
        if (isset($_POST['venue'])) {
            $venue = $_POST['venue'];
            $update_venue = "UPDATE `events` SET `venue` = '$venue' WHERE `event_id` = '$event_id' ";
            $update_venue1 = mysqli_query($conn, $update_venue) or die("Error updating the Event Venue");
        }

        // Update Event Description
        if (isset($_POST['description'])) {
            $description = $_POST['description'];
            $update_description = "UPDATE `events` SET `description` = '$description' WHERE `event_id` = '$event_id' ";
            $update_description1 = mysqli_query($conn, $update_description) or die("Error updating the Event description");
        }

        // Update Event Image
        if (isset($_POST['image'])) {
            $image = $_POST['image'];
            $update_image = "UPDATE `events` SET `image` = '$image' WHERE `event_id` = '$event_id' ";
            $update_image1 = mysqli_query($conn, $update_image) or die("Error updating the Event image");
        }

        // Update Team Event or not 
        if (isset($_POST['team_event'])) {
            $team_event = $_POST['team_event'];
        }

        // Update Team Size
        if (isset($_POST['team_size'])) {
            $team_size = $_POST['team_size'];
        }

        // Check for valid team_event and team_size combination
        if (($team_event == 0 && $team_size == 0) || ($team_event == 1 && $team_size >= 1)) {
            if (isset($_POST['team_event'])) {
                $update_team_event = "UPDATE `events` SET `team_event` = '$team_event' WHERE `event_id` = '$event_id' ";
                $update_team_event1 = mysqli_query($conn, $update_team_event) or die("Error updating the Event team_event");
            }
            if (isset($_POST['team_size'])) {
                $update_team_size = "UPDATE `events` SET `team_size` = '$team_size' WHERE `event_id` = '$event_id' ";
                $update_team_size1 = mysqli_query($conn, $update_team_size) or die("Error updating the Event team_size");
            }
        }
        // Output error if team event and size combination is invalid
        else {
            $response->message = "Invalid Team Event & Size combination!";
            $response_JSON = json_encode($response);
            echo $response_JSON;
            exit;
        }


        // Get Event's Club name
        $query1 = "SELECT `club_name` FROM `clubs` WHERE `club_id` = '$club_id' ";
        $res2 = mysqli_query($conn, $query1);
        $row2 = mysqli_fetch_assoc($res2);
        $club_name = $row2['club_name'];

        // Successful updation of the required attributes
        $response->message = "Event details updated successfully";
        $response->event_id = $event_id_char;
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
    // Send error response and exit if No event with given Event ID is found
    else {
        $response->message = "No such event Exists!";
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