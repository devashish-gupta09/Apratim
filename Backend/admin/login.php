<?php

// Establish Connection
include("../assets/initialize.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed!" . mysqli_connect_error());
}

// Send email and password
$email = $_POST['email'];
$password = $_POST['password'];
// Change password in MD5 encrypted format
$hashed_password = md5($password);

// Query to check if admin's email exists
$check = "SELECT * FROM `admins` WHERE `email` = '$email' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// Query if email and password combination exists
$login_auth = "SELECT * FROM `admins` WHERE `email` = '$email' AND `password` = '$hashed_password' ";
$result1 = mysqli_query($conn, $login_auth);
$count1 = mysqli_num_rows($result1);

// Check for valid email address
if ($count == 0) {

    $response = new stdClass();
    $response->message = "Invalid Email ID";
    $response->email = $email;
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}
// Check for valid email and password combination
else if ($count1 == 0) {

    $response = new stdClass();
    $response->message = "Invalid Email and Password Combination";
    $response->email = $email;
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}
// Successful login
else {
    session_start();
    date_default_timezone_set('Asia/Kolkata');

    $row = mysqli_fetch_array($result1);

    $admin_token = $row['admin_token'];
    $last_login_time = date('Y-m-d H:i:s');

    // Update admin's last login time
    $query = "UPDATE `admins` SET `last_login_time` = '$last_login_time' WHERE  `admin_token` = '$admin_token' ";
    $res2 = mysqli_query($conn, $query) or die("Unable to perform the operation");

    $response = new stdClass();
    $response->message = "Admin Logged in successfully.";
    $response->admin_token = $admin_token;
    $response->login_time = $last_login_time;
    $response_JSON = json_encode($response);
    echo $response_JSON;
}

?>