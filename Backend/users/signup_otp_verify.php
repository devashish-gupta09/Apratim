<?php
extract($_POST);
include("db_connection.php");
include("functions.php");
$sql = mysqli_query($conn, "SELECT * FROM users where email='$email_id'") or die("Could Not Perform the Query");
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
        $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
        echo $response_JSON;
        exit;
    } else {
        $sql2 = mysqli_query($conn, "SELECT * FROM authentication where email='$email_id'") or die("Could Not Perform the Query");
        $row1  = mysqli_fetch_array($sql2);
        if (is_array($row1)) {
            $valid_otp = $row1['otp'];
        }
        if (otp_checker($otp, $valid_otp)) {
            $query4 = "DELETE FROM authentication where email='$email_id'";
            $sql4 = mysqli_query($conn, $query4) or die("Could Not Perform the Query");
            $hashed_pass = md5($password);
            $register_date = date('Y-m-d h:i:s \G\M\T');
            $user_token = strval(generateRandomString()) . strval(uniqid(false)) . strval(generateRandomString());
            $query = "INSERT INTO users(name, college, number, email, password,state,city,year,branch,gender,registration_time,user_token) VALUES ('$name', '$college', '$number', '$email_id', '$hashed_pass','$state','$city','$year','$branch','$gender','$register_date','$user_token')";
            $sql1 = mysqli_query($conn, $query) or die("Could Not Perform the Query");
            $sql3 = mysqli_query($conn, "SELECT * FROM users where email='$email_id'") or die("Could Not Perform the Query");
            if (mysqli_num_rows($sql3) > 0) {
                $response = new stdClass();
                $response->message = "Correct OTP! Registered Account.";
                $response->email_id = $email_id;
                $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
                echo $response_JSON;
                exit;
            } else {
                $response = new stdClass();
                $response->message = "Something went wrong!";
                $response->email_id = $email_id;
                $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
                echo $response_JSON;
                exit;
            }
        } else {
            $response = new stdClass();
            $response->message = "Incorrect OTP or Timeout!!!";
            $response->email_id = $email_id;
            $response_JSON = json_encode($response, JSON_PRETTY_PRINT);
            echo $response_JSON;
            exit;
        }
    }
}
