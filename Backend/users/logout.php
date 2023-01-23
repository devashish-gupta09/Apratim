<?php
extract($_POST);
include("db_connection.php");
$sql = mysqli_query($conn, "SELECT * FROM users where user_token='$user_token'") or die("Could Not Perform the Query");
if (mysqli_num_rows($sql) == 0) {
    $response = new stdClass();
    $response->message = "User Token don't exists";
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
} else {
    $response = new stdClass();
    $response->message = "Logout Successful";
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
}
