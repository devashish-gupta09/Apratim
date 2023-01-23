<?php
extract($_POST);
include("db_connection.php");
$sql = mysqli_query($conn, "SELECT * FROM users where email='$email_id'");
if (mysqli_num_rows($sql) > 0) {
    $response = new stdClass();
    $response->message = "User Already Exists!!!";
    $response->email_id = $email_id;
    $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
    echo $response_JSON;
    exit;
} else {
    if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        $response = new stdClass();
        $response->message = "Invalid email format!!!";
        $response->email_id = $email_id;
        $response->otp = null;
        $response->valid_till = null;
        $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
        echo $response_JSON;
        exit;
    } else {
        // $otp = rand(100000, 999999);
        $otp = 111111;
        $hashed_otp = md5($otp);
        $date = date('Y-m-d h:i:s');
        $date = date('Y-m-d h:i:s', strtotime($date . ' + 5 minutes'));
        $sql2 = mysqli_query($conn, "SELECT * FROM authentication where email='$email_id'") or die("Could Not Perform the Query");
        if (mysqli_num_rows($sql2) > 0) {
            $query2 = "DELETE FROM authentication where email='$email_id'";
            $sql3 = mysqli_query($conn, $query2) or die("Could Not Perform the Query");
        }
        $query = "INSERT INTO authentication(email, otp, timestamp) VALUES ('$email_id', '$hashed_otp',CURRENT_TIMESTAMP + INTERVAL 5 MINUTE)";
        $sql1 = mysqli_query($conn, $query) or die("Could Not Perform the Query");
        $response = new stdClass();
        $response->message = "Verify for OTP";
        $response->email_id = $email_id;
        // $response->otp = $otp;
        // $response->valid_till = $date;
        $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
        echo $response_JSON;
        exit;
    }
}
