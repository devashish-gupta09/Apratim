<?php

$connection = mysqli_connect("localhost", "root", "", "apratim");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// function to generate team id
function generateTeamID($team_id)
{
    if ($team_id < 10) {
        $final_id = 'AP23-T00' . $team_id;
    } elseif ($team_id < 100) {
        $final_id = 'AP23-T0' . $team_id;
    } else {
        $final_id = 'AP23-T' . $team_id;
    }
    return $final_id;
}

// function to decode the team id
function generateTeamIDReverse($final_id)
{
    $prefix = 'AP23-T';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }
    $team_id = (int) $final_id;
    return $team_id;
}

// function to decode event id
function generateEventIDReverse($final_id)
{
    $prefix = 'AP23-E';

    if (substr($final_id, 0, strlen($prefix)) == $prefix) {
        $final_id = substr($final_id, strlen($prefix));
    }

    $event_id = (int) $final_id;
    return $event_id;
}

// check if the user is logged in
$user_token = $_POST['user_token'];
$user_id = "";

$query = "SELECT * FROM users WHERE user_token = '$user_token'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
    }
} else {
    $response = array(
        "message" => "You are not logged in!"
    );
    echo json_encode($response);
    exit();
}

// check if the event is team event or not
$event_id = generateEventIDReverse($_POST['event_id']);
$team_event = 0;
$team_id = "";
$event_name = "";
$team_size = 0;

$query = "SELECT * FROM events WHERE event_id = '$event_id'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $team_event = $row['team_event'];
        $event_name = $row['event_name'];
        $team_size = $row['team_size'];
    }
} else {
    $response = array(
        "message" => "This event does not exist!"
    );
    echo json_encode($response);
    exit();
}

// if team event, check if the user is already registered with a team
if ($team_event == 1) {
    $query = "SELECT * FROM event_registration WHERE user_id = '$user_id' AND event_id = '$event_id'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $team_id = $row['team_id'];
        }
        $response = array(
            "message" => "You are already registered with a team for the event `$event_name`!",
        );
        echo json_encode($response);
        exit();
    }

    // check if the team is full
    $query = "SELECT * FROM event_registration WHERE event_id = '$event_id' AND team_id = '$team_id'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) >= $team_size) {
        $response = array(
            "message" => "This team is already full!"
        );
        echo json_encode($response);
        exit();
    } else {
        // if not registered with a team, check if the user has provided a team name
        if (isset($_POST['team_name'])) {
            // if provided a team name, check if the team name is already taken
            $team_name = $_POST['team_name'];
            $query = "SELECT * FROM teams WHERE team_name = '$team_name' AND event_id = '$event_id'";
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                $response = array(
                    "message" => "The team name `$team_name` is already taken!"
                );
                echo json_encode($response);
                exit();
            } else {
                // if not taken, create a new team and register the user with the team and generate a team id then add team to teams table and register the user to that event
                $team_id = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM teams")) + 1;
                $addToTeams = "INSERT INTO teams (team_name, event_id) VALUES ('$team_name', '$event_id')";
                $result = mysqli_query($connection, $addToTeams);
                if ($result) {
                    $addToEventRegistration = "INSERT INTO event_registration (event_id, user_id, registration_time, team_id) VALUES ('$event_id', '$user_id', NOW(), '$team_id')";
                    $result = mysqli_query($connection, $addToEventRegistration);
                    if ($result) {
                        $response = array(
                            "message" => "You have successfully registered with team `$team_name` for the event `$event_name`!",
                            "team_id" => generateTeamID($team_id)
                        );
                        echo json_encode($response);
                        exit();
                    } else {
                        $response = array(
                            "message" => "There was an error registering your team for this event!"
                        );
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $response = array(
                        "message" => "There was an error creating your team!"
                    );
                    echo json_encode($response);
                    exit();
                }
            }
        } else if (isset($_POST['team_id'])) {
            // if not provided a team name, but a team id, check if the team id is valid
            $team_id = generateTeamIDReverse($_POST['team_id']);
            // get team name from team id
            $team_name = mysqli_fetch_assoc(mysqli_query($connection, "SELECT team_name FROM teams WHERE team_id = '$team_id'"))['team_name'];

            $query = "SELECT * FROM event_registration WHERE team_id = '$team_id'";
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0) {
                // if valid, check if this team is registered with the event stated from event_id in teams table
                $query = "SELECT * FROM teams WHERE team_id = '$team_id' AND event_id = '$event_id'";
                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0) {
                    // if team exists for that team, check if the user is already registered with that team
                    $query = "SELECT * FROM event_registration WHERE team_id = '$team_id' AND user_id = '$user_id'";
                    $result = mysqli_query($connection, $query);
                    if (mysqli_num_rows($result) > 0) {
                        // if registered, return error
                        $response = array(
                            "message" => "You are already registered with this team!"
                        );
                        echo json_encode($response);
                        exit();
                    } else {
                        // if not registered, register the user with the team and add the user to the event
                        $query = "INSERT INTO event_registration (event_id, user_id, registration_time, team_id) VALUES ('$event_id', '$user_id', NOW(), '$team_id')";
                        if (mysqli_query($connection, $query)) {
                            $response = array(
                                "message" => "You have been registered with team `$team_name` for event `$event_name`!"
                            );
                            echo json_encode($response);
                            exit();
                        } else {
                            $response = array(
                                "message" => "Error registering you with this team!"
                            );
                            echo json_encode($response);
                            exit();
                        }
                    }
                } else {
                    // if team does not exist for that event, return error
                    $response = array(
                        "message" => "This team does not exist for this event!"
                    );
                    echo json_encode($response);
                    exit();
                }
            } else {
                // if not valid, return error
                $response = array(
                    "message" => "This team does not exist!"
                );
                echo json_encode($response);
                exit();
            }
        } else {
            // if not provided a team name or team id, return error
            $response = array(
                "message" => "You must provide a team name or team id to register with this event!"
            );
            echo json_encode($response);
            exit();
        }
    }
} else {
    // if not team event, check if the user is already registered with the event
    $query = "SELECT * FROM event_registration WHERE user_id = '$user_id' AND event_id = '$event_id'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        // if registered, return error
        $response = array(
            "message" => "You are already registered with this event!"
        );
        echo json_encode($response);
        exit();
    } else {
        // if not registered, register the user with the event
        $query = "INSERT INTO event_registration (event_id, user_id, registration_time) VALUES ('$event_id', '$user_id', NOW())";
        if (mysqli_query($connection, $query)) {
            $response = array(
                "message" => "You have been registered for the event `$event_name` successfully!"
            );
            echo json_encode($response);
            exit();
        } else {
            $response = array(
                "message" => "Error registering you with this event!"
            );
            echo json_encode($response);
            exit();
        }
    }
}

mysqli_close($connection);
