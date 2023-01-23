<?php
$name = "";
$college = "";
$number = "";
$state = "";
$city = "";
$year = "";
$branch = "";
$gender = "";
extract($_POST);
include("db_connection.php");
include("functions.php");
$sql = mysqli_query($conn, "SELECT * FROM users where user_token='$user_token'") or die("Could Not Perform the Query");
if (mysqli_num_rows($sql) == 0) {
    $response = new stdClass();
    $response->message = "User Token don't exists or User not logged in";
    $response->updated_details = null;
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
} else {
    $db_details  = mysqli_fetch_array($sql);
    $post_details = array("name" => $name, "college" => $college, "number" => $number, "state" => $state, "city" => $city, "year" => $year, "branch" => $branch, "gender" => $gender);
    $row1 = updateDetails($db_details, $post_details);
    if (is_array($row1)) {
        $name = $row1['name'];
        $college = $row1['college'];
        $number = $row1['number'];
        $state = $row1['state'];
        $city = $row1['city'];
        $year = $row1['year'];
        $branch = $row1['branch'];
        $gender = $row1['gender'];
        $count = $row1['count'];
    }
    $response = new stdClass();
    if ($count == 0) {
        $response->message = "Updated Details";
        $response->updated_details = "0";
        $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
        echo $response_JSON;
        exit;
    } else {
        $sql = mysqli_query($conn, "UPDATE users SET name='$name', college='$college', number='$number', state='$state', city='$city', year='$year', branch='$branch', gender='$gender' WHERE user_token='$user_token'") or die("Could Not Perform the Query");
        $response->message = "Updated Details";
        $response->updated_details = $count;
        $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
        echo $response_JSON;
        exit;
    }
}
