<?php
extract($_POST);
include("db_connection.php");
include("functions.php");
$sql = mysqli_query($conn, "SELECT * FROM users where user_token='$user_token'") or die("Could Not Perform the Query");
if (mysqli_num_rows($sql) == 0) {
    $response = new stdClass();
    $response->message = "User Token don't exists";
    $response->user_id = null;
    $response->email_id = null;
    $response->name = null;
    $response->college = null;
    $response->number = null;
    $response->state = null;
    $response->city = null;
    $response->year = null;
    $response->branch = null;
    $response->gender = null;
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
} else {
    $row1  = mysqli_fetch_array($sql);
    if (is_array($row1)) {
        $user_id = generateUserID($row1['user_id']);
        $email_id = $row1['email'];
        $name = $row1['name'];
        $college = $row1['college'];
        $number = $row1['number'];
        $state = $row1['state'];
        $city = $row1['city'];
        $year = $row1['year'];
        $branch = $row1['branch'];
        $gender = $row1['gender'];
    }
    $response = new stdClass();
    $response->message = "User Details";
    $response->user_id = $user_id;
    $response->email_id = $email_id;
    $response->name = $name;
    $response->college = $college;
    $response->number = $number;
    $response->state = $state;
    $response->city = $city;
    $response->year = $year;
    $response->branch = $branch;
    $response->gender = $gender;
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
}
