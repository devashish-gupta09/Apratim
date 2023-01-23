<?php 
    require_once('./db_connection.php');
    header('Content-type:application/json;charset=utf-8');

    class event { 
        public $event_id;
        public $event_name;
        public $event_date_time;
        public $venue;
        public $description;
        public $image;
        public $club_id;
        public $team_event;
        public $team_size;
    }

    function generateEventID($eventid)
{
    if ($eventid < 10) {
        $final_id = 'AP23-E00' . $eventid;
    } elseif ($eventid < 100) {
        $final_id = 'AP23-E0' . $eventid;
    } else {
        $final_id = 'AP23-E' . $eventid;
    }
    return $final_id;
}

    
    $eventsArray = array();

    $sql = "SELECT * FROM `events` WHERE 1;";
    $result = mysqli_query($connection, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $obj = new event();
            $obj->event_id = generateEventID($row['event_id']);
            $obj->event_name = $row['event_name'];
            $obj->event_date_time = $row['event_date_time'];
            $obj->venue = $row['venue'];
            $obj->description = $row['description'];
            $obj->image = $row['image'];
            $obj->club_id = $row['club_id'];
            $obj->team_event = $row['team_event'];
            $obj->team_size = $row['team_size'];
            array_push($eventsArray, $obj);
        }
    }

    echo json_encode($eventsArray, JSON_PRETTY_PRINT);

?>

