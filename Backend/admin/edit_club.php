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

function generateClubID($club_id)
{
    if ($club_id < 10) {
        $final_id = 'AP23-C0' . $club_id;
    } else {
        $final_id = 'AP23-C' . $club_id;
    }
    return $final_id;
}

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
    $head_name = $row['name'];

    // Fetch Club Details from `club`
    $query = "SELECT * FROM `clubs` WHERE `club_id` = '$club_id' AND `head_name` = '$head_name' ";
    $result1 = mysqli_query($conn, $query);
    $details = mysqli_fetch_assoc($result1);

    // Initialize with old values of Club
    $club_name = $details['club_name'];
    $contact = $details['contact'];
    $head_image = $details['head_image'];
    $club_image = $details['club_image'];
    $about_club = $details['about_club'];


    // Update Contact 
    if (isset($_POST['contact'])) {
        $contact = $_POST['contact'];
        $update_contact = "UPDATE `clubs` SET `contact` = '$contact' WHERE `club_id` = '$club_id' ";
        $update_contact1 = mysqli_query($conn, $update_contact) or die("Error updating the Club Contact");
    }

    // Update Head Image
    if (isset($_POST['head_image'])) {
        $head_image = $_POST['head_image'];
        $update_head_image = "UPDATE `clubs` SET `head_image` = '$head_image' WHERE `club_id` = '$club_id' ";
        $update_head_image1 = mysqli_query($conn, $update_head_image) or die("Error updating the Head Image");
    }

    // Update Club Image
    if (isset($_POST['club_image'])) {
        $club_image = $_POST['club_image'];
        $update_club_image = "UPDATE `clubs` SET `club_image` = '$club_image' WHERE `club_id` = '$club_id' ";
        $update_club_image1 = mysqli_query($conn, $update_club_image) or die("Error updating the Club Image");
    }

    // Update About Club
    if (isset($_POST['about_club'])) {
        $about_club = $_POST['about_club'];
        $update_about_club = "UPDATE `clubs` SET `about_club` = '$about_club' WHERE `club_id` = '$club_id' ";
        $update_about_club1 = mysqli_query($conn, $update_about_club) or die("Error updating the Club Details");
    }

    //Generate Club's ID in AP23-CXX format
    $club_id_str = generateClubID($details['club_id']);

    // Send updated club details as response
    $response = new stdClass();
    $response->message = "Updated Club details";
    $response->club_id = $club_id_str;
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