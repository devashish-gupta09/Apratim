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

    // Get club id and name from Admin Data
    $club_id = $row['admin_id'];
    $club_name = $row['name'];

    // Fetch Club Details from `club`
    $query = "SELECT * FROM `clubs` WHERE `club_id` = '$club_id' ";
    $result1 = mysqli_query($conn, $query);
    $details = mysqli_fetch_assoc($result1);

    // Initialize with old values of Club
    $head_name = $details['head_name'];
    $contact = $details['contact'];
    $head_image = $details['head_image'];
    $club_image = $details['club_image'];
    $about_club = $details['about_club'];

    $response = new stdClass();
    $response->club_id = $club_id;
    $response->club_name = $club_name;

    // Update Head Name
    if (isset($_POST['head_name'])) {
        $head_name = $_POST['head_name'];
        $update_head_name = "UPDATE `clubs` SET `head_name` = '$head_name' WHERE `club_id` = '$club_id' ";
        $update_head_name1 = mysqli_query($conn, $update_head_name) or die("Error updating the Head Name");
        $response->head_name = $head_name;
    }

    // Update Contact 
    if (isset($_POST['contact'])) {
        $contact = $_POST['contact'];
        $update_contact = "UPDATE `clubs` SET `contact` = '$contact' WHERE `club_id` = '$club_id' ";
        $update_contact1 = mysqli_query($conn, $update_contact) or die("Error updating the Club Contact");
        $response->contact = $contact;
    }

    // Update Head Image
    if (isset($_POST['head_image'])) {
        $head_image = $_POST['head_image'];
        $update_head_image = "UPDATE `clubs` SET `head_image` = '$head_image' WHERE `club_id` = '$club_id' ";
        $update_head_image1 = mysqli_query($conn, $update_head_image) or die("Error updating the Head Image");
        $response->head_image = $head_image;

    }

    // Update Club Image
    if (isset($_POST['club_image'])) {
        $club_image = $_POST['club_image'];
        $update_club_image = "UPDATE `clubs` SET `club_image` = '$club_image' WHERE `club_id` = '$club_id' ";
        $update_club_image1 = mysqli_query($conn, $update_club_image) or die("Error updating the Club Image");
        $response->club_image = $club_image;
    }

    // Update About Club
    if (isset($_POST['about_club'])) {
        $about_club = $_POST['about_club'];
        $update_about_club = "UPDATE `clubs` SET `about_club` = '$about_club' WHERE `club_id` = '$club_id' ";
        $update_about_club1 = mysqli_query($conn, $update_about_club) or die("Error updating the Club Details");
        $response->about_club = $about_club;
    }

    // Successful details of the required attributes
    $response->message = "Club details updated successfully";
    $response_JSON = json_encode($response);
    echo $response_JSON;

}
// If invalid token or session
else {
    echo "Invalid Token or Session!";
}



?>