<?php
if (!defined('DB_SERVER')) {
    require_once("../assets/initialize.php");
}

$host = DB_SERVER;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$database = DB_NAME;

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo "database connection failed";
    exit;
}

$username = $_POST['email'];
$password = md5($_POST['password']);


$sql = "SELECT * FROM `super_admins` WHERE 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['email'] === $username && $row['password'] === $password) {
            $response = new stdClass();
            $response->message = "Login Successfull";
            $response->super_admin_token = $row['super_admin_token'];

            $response_JSON = json_encode($response);

            echo $response_JSON;
            exit;
        }
    }
}
$response = new stdClass();
$response->message = "Login Failed";
$response_JSON = json_encode($response);
echo $response_JSON;
