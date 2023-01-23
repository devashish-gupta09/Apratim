<?php 
    require_once('./db_connection.php');
    header('Content-type:application/json;charset=utf-8');

    class member { 
        public $member_id;
        public $member_name;
        public $member_contact;
        public $member_email;
        public $social_media_link1;
        public $social_media_link2;
        public $member_image;
        public $member_position;
        public $team_name;
    }

    function generateEventID($id)
{
    if ($id < 10) {
        $final_id = 'AP23-M00' . $id;
    } elseif ($id < 100) {
        $final_id = 'AP23-M0' . $id;
    } else {
        $final_id = 'AP23-M' . $id;
    }
    return $final_id;
}

    $apratimteamArray = array();
    
    $sql = "SELECT * FROM `apratim_team` WHERE 1;";
    $result = mysqli_query($connection, $sql);
    if($result){
        while($row= mysqli_fetch_assoc($result)){
            $obj = new member();
            $obj->member_id = generateEventId($row['member_id']);
            $obj->member_name = $row['member_name'];
            $obj->member_contact = $row['member_contact'];
            $obj->member_email = $row['member_email'];
            $obj->social_media_link1 = $row['social_media_link1'];
            $obj->social_media_link2 = $row['social_media_link2'];
            $obj->member_image = $row['member_image'];
            $obj->member_position = $row['member_position'];
            $obj->team_name = $row['team_name'];
            array_push($apratimteamArray, $obj);
        }
    }
    
    echo json_encode($apratimteamArray, JSON_PRETTY_PRINT);
?>
