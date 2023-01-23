<?php
if (!defined('DB_SERVER')) {
    require_once("../assets/initialize.php");
}

$host = DB_SERVER;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$database = DB_NAME;


function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo "database connection failed";
    die();
}

$token = $_POST['token'];
$admin_name = $_POST['admin_name'];
$club_name = $_POST['club_name'];
$admin_email = $_POST['admin_email'];
$admin_password = md5("1234");

$sql = "SELECT * FROM `super_admins`";
$flag = false;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (($row['super_admin_token'] === $token)) {
            $super_admin_id = $row['super_admin_id'];
            $flag = true;
        }
    }
}
if ($flag == false) {
    $response = new stdClass();
    $response->message = "Not a Valid token";

    $response_JSON = json_encode($response);

    echo $response_JSON;
    die();
}

$check = false;

while ($check === false) {
    $admin_token = generateRandomString();
    $sql = "SELECT * FROM `admins` WHERE 1";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!($row['admin_token'] === $admin_token)) {
                $check = true;
            }
        }
    } else {
        $check = true;
    }
}



$sql = "INSERT INTO `admins`(`name`, `email`, `password`, `admin_token`, `super_admin_id`) 
VALUES ('$admin_name','$admin_email','$admin_password','$admin_token','$super_admin_id')";

$result1 = $conn->query($sql);


$sql = "INSERT INTO `clubs`( `club_name`, `head_name`) 
VALUES ('$club_name','$admin_name')";

$result2 = $conn->query($sql);


if ($result1 && $result2) {

    $response = new stdClass();
    $response->message = "Club/Admin Added Succeffully";

    $response_JSON = json_encode($response);

    echo $response_JSON;
    die();
}
