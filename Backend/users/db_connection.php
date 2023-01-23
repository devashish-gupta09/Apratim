<?php
# Server: Localhost, Database: apratim, User: root, Password:
if (!defined('DB_SERVER')) {
  require_once("../assets/initialize.php");
}
// Default Timezone set to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
  // Database connection failed
  die("Database connection failed with error: " .
    mysqli_connect_error() .
    " (" . mysqli_connect_errno() . ")");
} 
// else {
//   // Database connection successful
//   $date = date('Y-m-d h:i:s');
//   echo "Database connection successful at $date";
// }
