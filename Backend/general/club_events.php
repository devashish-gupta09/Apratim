<?php 
    require_once('./db_connection.php');
    header('Content-type:application/json;charset=utf-8');

    function generateClubID($clubid)
{
    if ($clubid < 10) {
        $final_id = 'AP23-C00' . $clubid;
    } elseif ($clubid < 100) {
        $final_id = 'AP23-C0' . $clubid;
    } else {
        $final_id = 'AP23-C' . $clubid;
    }
    return $final_id;
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

function generateClubIDReverse($final_id)
{
    $prefix = 'AP23-C';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }

    return (int) $final_id;
}

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

    class club { 
        public $club_id;
        public $club_name;
        public $about_club;
    }

    $id = $_POST['club_id'];
    
    $id = generateClubIDReverse($id);

    $clubsArray = array();

    $sql = "SELECT * FROM `clubs` WHERE club_id = '$id';";
    $result = mysqli_query($connection, $sql);
    if($result){
            $row = mysqli_fetch_assoc($result);
            $obj = new club();
            $obj->club_id = generateClubID($row['club_id']);
            $obj->club_name = $row['club_name'];
            $obj->about_club = $row['about_club'];
            array_push($clubsArray, $obj);
    }

    echo json_encode($clubsArray, JSON_PRETTY_PRINT);

    $eventsArray = array();

    $sql = "SELECT * FROM `events` WHERE club_id = '$id';";
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

