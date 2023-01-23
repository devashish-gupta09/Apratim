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