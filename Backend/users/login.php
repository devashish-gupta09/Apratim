<?php
// session_start();
extract($_POST);
include("db_connection.php");
$sql = mysqli_query($conn, "SELECT * FROM users where email='$email_id'") or die("Could Not Perform the Query");
if (mysqli_num_rows($sql) == 0) {
    $response = new stdClass();
    $response->message = "User With email id don't exists";
    $response->user_token = null;
    $response->time = null;
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
} else {
    $row  = mysqli_fetch_array($sql);
    if (is_array($row)) {
        if (md5($password) === $row['password']) {
            $user_token = $row['user_token'];
            $last_login_time = date('Y-m-d h:i:s');
            $query = "UPDATE users SET last_login_time='" . $last_login_time . "' WHERE user_token='$user_token'";
            $sql1 = mysqli_query($conn, $query) or die("Could Not Perform the Query");
            $response = new stdClass();
            $response->message = "Logged in.";
            $response->user_token = $user_token;
            $response->time = $last_login_time;
            $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
            echo $response_JSON;
            exit;
        } else {
            $response->message = "Incorrect Password.";
            $response->user_token = null;
            $response->time = null;
            $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
            echo $response_JSON;
            exit;
        }
    }
}
