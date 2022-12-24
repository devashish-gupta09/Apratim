<?php
# Server: Localhost, Database: apratim, User: root, Password:
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "apratim");

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
  die("Connection failed!" . mysqli_connect_error());
}