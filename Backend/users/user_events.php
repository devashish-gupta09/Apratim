<?php

$con = mysqli_connect("localhost", "root", "", "Apratim");


if (isset($_POST['user_token']) && !empty($_POST['user_token'])) {
    $user_token = $_POST['user_token'];
} else {
    $res = array("message" => "user_token is empty");
    echo json_encode($res);
    exit();
}



$event_ids = array();
$all_events = array();
$response_events = array();

// function to generate event id
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

// function to generate club id
function generateClubID($club_id)
{
    if ($club_id < 10) {
        $final_id = 'AP23-C0' . $club_id;
    } else {
        $final_id = 'AP23-C' . $club_id;
    }
    return $final_id;
}

function swap($i,$j)
{
    $temp = $i;
    $i = $j;
   $j = $temp;

}


if ($con) {
    //Get user_id from users table using user_token
    date_default_timezone_set('Asia/Calcutta');
    $date = date('m/d/Y h:i:s a', time());
    $get_user_id = "SELECT `user_id` FROM `users` WHERE `user_token`= '$user_token' ";

    $result = mysqli_query($con, $get_user_id);
    $num = mysqli_num_rows($result);



    if ($num == 0) {

        $res = array("message" => "wrong user_token");
        echo json_encode($res);

    } else {

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
            $res = array("message" => "no event registered by the user");
            echo json_encode($res);
        } else {
            // storing result rows in $all_events associative array
            while ($all_events[] = mysqli_fetch_assoc($result)) {
            }

            //-------//
            // Code to match the ids of user's registered events and all events 
            $j = 0; //pointer to iterate through $event_ids array

            $count = 0;
            while ($j < $event_num) {
                $i = 0; //pointer to iterate through $all_events array 
                $evenid = $event_ids[$j]['event_id'];

                while ($i < $num) {
                    $eventid = $all_events[$i]['event_id'];
                    if ($eventid == $evenid) {
                        
                         $response_events[$count]['event_id'] = generateEventID($all_events[$i]['event_id']);
                         $response_events[$count]['event_name'] = $all_events[$i]['event_name'];
                         $response_events[$count]['event_date_time'] = $all_events[$i]['event_date_time'];
                         $response_events[$count]['venue'] = $all_events[$i]['venue'];
                         $response_events[$count]['description'] = $all_events[$i]['description'];
                         $response_events[$count]['image'] = $all_events[$i]['image'];
                         $response_events[$count]['club_id'] = generateClubID($all_events[$i]['club_id']);
                         $response_events[$count]['team_event'] = $all_events[$i]['team_event'];
                         $response_events[$count]['team_size'] = $all_events[$i]['team_size'];

                    
                        $count++;

                    }
                    $i++;
                }
                $j++;
            }
            echo json_encode($response_events, JSON_PRETTY_PRINT);
        }

    }
} else {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_close($con);
?>