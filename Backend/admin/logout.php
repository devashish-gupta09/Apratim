<?php

// Establish Connection
include("db_connection.php");

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Define response object
$response = new stdClass();

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);


// Validate admin token and login status
if ($count == 1) {

    $admin_token = 0;

    if ($admin_token == 0) {
        $response->message = "Admin Logged out successfully.";
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