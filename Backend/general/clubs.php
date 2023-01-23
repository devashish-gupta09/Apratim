<?php 
    require_once('./db_connection.php');
    header('Content-type:application/json;charset=utf-8');

    class club { 
        public $club_id;
        public $club_name;
        public $head_name;
        public $contact;
        public $head_image;
        public $club_image;
        public $about_club;
    }

    function generateEventID($clubid)
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

    $clubsArray = array();
    
    $sql = "SELECT * FROM `clubs` WHERE 1;";
    $result = mysqli_query($connection, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $obj = new club();
            $obj->club_id = generateEventID($row['club_id']);
            $obj->club_name = $row['club_name'];
            $obj->head_name = $row['head_name'];
            $obj->contact = $row['contact'];
            $obj->head_image = $row['head_image'];
            $obj->club_image = $row['club_image'];
            $obj->about_club = $row['about_club'];
            array_push($clubsArray, $obj);
        }
    }

    echo json_encode($clubsArray, JSON_PRETTY_PRINT);

?>

