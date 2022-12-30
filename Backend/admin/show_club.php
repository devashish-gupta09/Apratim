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

    // Get admin id and name from Admin Data
    $admin_id = $row['admin_id'];
    $name = $row['name'];

    // Query to fetch club data from Clubs
    $check1 = "SELECT * FROM `clubs` WHERE `club_id` = '$admin_id' AND `club_name` = '$name' ";
    $result1 = mysqli_query($conn, $check1);
    $count1 = mysqli_num_rows($result1);

    // Check if a valid club exists
    if ($count1 == 1) {
        // Store club details
        $row1 = mysqli_fetch_assoc($result1);

        $head_name = $row1['head_name'];
        $contact = $row1['contact'];
        $head_image = $row1['head_image'];
        $club_image = $row1['club_image'];
        $about_club = $row1['about_club'];

        // Send response 
        $response = new stdClass();
        $response->message = "Club Details";
        $response->club_id = $admin_id;
        $response->club_name = $name;
        $response->head_name = $head_name;
        $response->contact = $contact;
        $response->head_image = $head_image;
        $response->club_image = $club_image;
        $response->about_club = $about_club;
        $response_JSON = json_encode($response);
        echo $response_JSON;

    }
    // If a valid club does not exist
    else {
        $response = new stdClass();
        $response->message = "No such club exists!";
        $response_JSON = json_encode($response);
        echo $response_JSON;
        exit;
    }
}
// If invalid token or session
else {
    echo "Invalid Admin Token";
}



?>