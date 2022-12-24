<?php

// Establish Connection
include("db_connection.php");

// Send email and password
$email = $_POST['email'];
$password = $_POST['password'];

// Query if email and password combination exists
$login_auth = "SELECT * FROM `admins` WHERE `email` = '$email' AND `password` = '$password' ";
$result = mysqli_query($conn, $login_auth);
$count = mysqli_num_rows($result);

// Query for user's login status
$login_check = "SELECT * FROM `admins` WHERE `email` = '$email' AND `password` = '$password' AND `login_status` = '0' ";
$res1 = mysqli_query($conn, $login_check);
$count1 = mysqli_num_rows($res1);

// Check for valid email and password combination
if ($count == 0) {

    $response = new stdClass();
    $response->message = "Invalid Email or Password.";
    $response->email = $email;
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}
// Check for user's login status
else if ($count1 == 0) {

    $response = new stdClass();
    $response->message = "User already Logged In.";
    $response->email = $email;
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}
// Successful login
else {
    session_start();
    date_default_timezone_set('Asia/Kolkata');

    $row = mysqli_fetch_array($res1);

    $admin_token = $row['admin_token'];
    $last_login_time = date('Y-m-d H:i:s');
    $login_status = 1;

    // Update last login time, login_staus
    $query = "UPDATE `admins` SET `login_status` = '$login_status', `last_login_time` = '$last_login_time' WHERE  `admin_token` = '$admin_token' ";
    $res2 = mysqli_query($conn, $query) or die("Unable to perform the operation");

    $response = new stdClass();
    $response->message = "Logged in.";
    $response->admin_token = $admin_token;
    $response->time = $last_login_time;
    $response->login_status = $login_status;
    $response_JSON = json_encode($response);
    echo $response_JSON;
}

?>