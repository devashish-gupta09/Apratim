<?php

// Establish Connection
include("db_connection.php");

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' AND `login_status` = '1' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// Validate admin token and login status
if ($count == 1) {
    $row = mysqli_fetch_assoc($result);

    // Get club id and name from Admin Data
    $club_id = $row['admin_id'];
    $club_name = $row['name'];

    // Send additional Club details to be added
    $head_name = $_POST['head_name'];
    $contact = $_POST['contact'];
    $head_image = $_POST['head_image'];
    $club_image = $_POST['club_image'];
    $about_club = $_POST['about_club'];

    // Query to add club details
    $add = "INSERT INTO `clubs`(`club_id`, `club_name`, `head_name`, `contact`, `head_image`, `club_image`, `about_club`) VALUES ('$club_id', '$club_name','$head_name','$contact','$head_image','$club_image','$about_club')";

    $add_query = mysqli_query($conn, $add) or die("Error adding the details of the Club.");

    $response = new stdClass();
    $response->message = "Club details added successfully";
    $response->club_id = $club_id;
    $response->club_name = $club_name;
    $response->head_name = $head_name;
    $response->contact = $contact;
    $response->head_image = $head_image;
    $response->club_image = $club_image;
    $response->about_club = $about_club;
    $response_JSON = json_encode($response);
    echo $response_JSON;

}
// If invalid token or session
else {
    echo "Invalid Token or Session!";
}


?>