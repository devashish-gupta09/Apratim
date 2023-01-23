<?php
if (!defined('DB_SERVER')) {
    require_once("../assets/initialize.php");
}
$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


$user_token = $_GET['user_token'];
$event_ids = array();
$all_events = array();
$response_events = array();


if ($con) {

    //Get user_id from users table using user_token

    $get_user_id = "SELECT `user_id` FROM `users` WHERE `user_token`= $user_token ;";
    $result = mysqli_query($con, $get_user_id);
    $id = mysqli_fetch_assoc($result);
    $user_id = $id['user_id'];




    //get event_id from event_registration table using user_id
    $get_event_id = "SELECT `event_id` FROM `event_registration` WHERE `user_id`= $user_id ; ";
    $result = mysqli_query($con, $get_event_id);
    $event_num = mysqli_num_rows($result);

    // storing result rows to assocaitive array $event_ids
    while ($event_ids[] = mysqli_fetch_assoc($result)) {
    }



    // get event_details from events table using event_id


    $events_details = "SELECT * FROM `events`;";

    $result = mysqli_query($con, $events_details);

    $num = mysqli_num_rows($result);


    if ($event_num == 0) {
        echo "no event registered by this user_id";
    } else {

        // storing result rows in $all_events associative array
        while ($all_events[] = mysqli_fetch_assoc($result)) {
        }

        //-------//


        // Code to match the ids of user's registered events and all events 

        $j = 0; //pointer to iterate through $event_ids array

        while ($j < $event_num) {
            $i = 0; //pointer to iterate through $all_events array 
            $evenid = $event_ids[$j]['event_id'];
            while ($i < $num) {
                $eventid = $all_events[$i]['event_id'];
                if ($eventid == $evenid) {
                    $response_events[$j]['event_id'] = $all_events[$i]['event_id'];
                    $response_events[$j]['event_name'] = $all_events[$i]['event_name'];
                    $response_events[$j]['event_date_time'] = $all_events[$i]['event_date_time'];
                    $response_events[$j]['venue'] = $all_events[$i]['venue'];
                    $response_events[$j]['description'] = $all_events[$i]['description'];
                    $response_events[$j]['image'] = $all_events[$i]['image'];
                    $response_events[$j]['club_id'] = $all_events[$i]['club_id'];
                    $response_events[$j]['team_event'] = $all_events[$i]['team_event'];
                    $response_events[$j]['team_size'] = $all_events[$i]['team_size'];
                }

                $i++;
            }

            $j++;
        }

        echo json_encode($response_events, JSON_PRETTY_PRINT);
    }
} else {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_close($con);
