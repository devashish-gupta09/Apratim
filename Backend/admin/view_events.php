<?php

// Establish Connection
include("db_connection.php");

function generateEventID($event_id)
{
    if ($event_id < 10) {
        $final_id = 'AP23-E00' . $event_id;
    } elseif ($event_id < 100) {
        $final_id = 'AP23-E0' . $event_id;
    } else {
        $final_id = 'AP23-E' . $event_id;
    }
    return $final_id;
}

function generateClubID($club_id)
{
    if ($club_id < 10) {
        $final_id = 'AP23-C0' . $club_id;
    } else {
        $final_id = 'AP23-C' . $club_id;
    }
    return $final_id;
}

// Define response object
$response = array();

// Send admin token to access Club Admin
$admin_token = $_POST['admin_token'];

// Query to validate admin token
$check = "SELECT * FROM `admins` WHERE `admin_token` = '$admin_token' ";
$result = mysqli_query($conn, $check);
$count = mysqli_num_rows($result);

// Validate admin token
if ($count == 1) {
    $row = mysqli_fetch_assoc($result);

    // Get club id from Admin Data 
    $club_id = $row['admin_id'];

    // Get all events list
    $query = "SELECT * FROM `events` WHERE `club_id` = '$club_id' ";
    $res1 = mysqli_query($conn, $query);
    $count = mysqli_num_rows($res1);

    // Check if any events exist with the club
    if ($count) {

        // Iterate over all the events and store in response array
        $i = 0;
        while ($count) {
            $rows = mysqli_fetch_assoc($res1);

            $rows['event_id'] = generateEventID($rows['event_id']);
            $response[$i]['event_id'] = $rows['event_id'];
            $response[$i]['event_name'] = $rows['event_name'];
            $response[$i]['event_date_time'] = $rows['event_date_time'];
            $response[$i]['venue'] = $rows['venue'];
            $response[$i]['description'] = $rows['description'];
            $response[$i]['image'] = $rows['image'];
            $rows['club_id'] = generateClubID($rows['club_id']);
            $response[$i]['club_id'] = $rows['club_id'];
            $response[$i]['team_event'] = $rows['team_event'];
            $response[$i]['team_size'] = $rows['team_size'];

            $count--;
            $i++;
        }

    } else {
        $response['message'] = "No events exist with the club";
    }

    $response_JSON = json_encode($response);
    echo $response_JSON;
}
// If invalid token or session
else {
    $response['message'] = "Invalid Token or Session!";
    $response_JSON = json_encode($response);
    echo $response_JSON;
    exit;
}


?>