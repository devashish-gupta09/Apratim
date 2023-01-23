<?php

// Establish Connection
include("../assets/initialize.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed!" . mysqli_connect_error());
}

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