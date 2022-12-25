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

    // Get admin_id and admin_token from Admin Data
    $admin_id = $row['admin_id'];
    $admin_token = $row['admin_token'];
    $name = $row['name'];
    $login_status = $row['login_status'];

    // Query to logout
    $logout = "UPDATE `admins` SET `login_status` = '0' WHERE `admin_token` = '$admin_token' AND `admin_id` = '$admin_id'  ";
    $result1 = mysqli_query($conn, $logout) or die("Error logging out.");

    $response = new stdClass();
    $response->message = "$name . ' Admin Logged Out' ";
    $response_JSON = json_encode($response);
    echo $response_JSON;

}
// If invalid token or session
else {
    echo "Invalid Token or Session!";
}



?>