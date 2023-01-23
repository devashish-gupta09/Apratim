<?php 
    require_once('./db_connection.php');
    header('Content-type:application/json;charset=utf-8');

    class sponsor { 
        public $sponsor_id;
        public $sponsor_name;
        public $sponsor_website;
        public $sponsor_type;
        public $sponsor_image;
    }

    function generateEventID($id)
{
    if ($id < 10) {
        $final_id = 'AP23-S00' . $id;
    } elseif ($id < 100) {
        $final_id = 'AP23-S0' . $id;
    } else {
        $final_id = 'AP23-S' . $id;
    }
    return $final_id;
}

    $sponsorsArray = array();

    $sql = "SELECT * FROM `sponsors` WHERE 1;";
    $result = mysqli_query($connection, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $obj = new sponsor();
            $obj->sponsor_id = generateEventID($row['sponsor_id']);
            $obj->sponsor_name = $row['sponsor_name'];
            $obj->sponsor_website = $row['sponsor_website'];
            $obj->sponsor_type = $row['sponsor_type'];
            $obj->sponsor_image = $row['sponsor_image'];
            array_push($sponsorsArray, $obj);
        }
    }
   
    echo json_encode($sponsorsArray, JSON_PRETTY_PRINT);
?>
