<?php
function otp_checker($otp, $valid_otp)
{
    if (md5($otp) === $valid_otp) {
        return true;
    }
    return false;
}

function generateRandomString($length = 3)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateEventIDReverse($final_id)
{
    $prefix = 'AP23-';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }

    $event_id = (int) $final_id;
    return $event_id;
}

function generateUserID($user_id)
{
    if ($user_id < 10) {
        $final_id = 'AP23-000' . $user_id;
    } elseif ($user_id < 100) {
        $final_id = 'AP23-00' . $user_id;
    } elseif ($user_id < 1000) {
        $final_id = 'AP23-0' . $user_id;
    } else {
        $final_id = 'AP23-' . $user_id;
    }
    return $final_id;
}


function updateDetails($array1, $array2)
{
    if (is_array($array1) && is_array($array2)) {
        $name_db = $array1['name'];
        $college_db = $array1['college'];
        $number_db = $array1['number'];
        $state_db = $array1['state'];
        $city_db = $array1['city'];
        $year_db = $array1['year'];
        $branch_db = $array1['branch'];
        $gender_db = $array1['gender'];
        $name = $array2['name'];
        $college = $array2['college'];
        $number = $array2['number'];
        $state = $array2['state'];
        $city = $array2['city'];
        $year = $array2['year'];
        $branch = $array2['branch'];
        $gender = $array2['gender'];
    }
    $count = 8;
    if (empty($name) || $name == $name_db) {
        $count = $count - 1;
        $name = $name_db;
    }
    if (empty($college) || $college == $college_db) {
        $count = $count - 1;
        $college = $college_db;
    }
    if (empty($number) || $number == $number_db) {
        $count = $count - 1;
        $number = $number_db;
    }
    if (empty($state) || $state == $state_db) {
        $count = $count - 1;
        $state = $state_db;
    }
    if (empty($city) || $city == $city_db) {
        $count = $count - 1;
        $city = $city_db;
    }
    if (empty($year) || $year == $year_db) {
        $count = $count - 1;
        $year = $year_db;
    }
    if (empty($branch) || $branch == $branch_db) {
        $count = $count - 1;
        $branch = $branch_db;
    }
    if (empty($gender) || $gender == $gender_db) {
        $count = $count - 1;
        $gender = $gender_db;
    }
    $final_details = array("name" => $name, "college" => $college, "number" => $number, "state" => $state, "city" => $city, "year" => $year, "branch" => $branch, "gender" => $gender, "count" => $count);
    return $final_details;
}
