<?php
require('../private/initialize.php');

// Get the information from the json sent from the html code
$data = json_decode(file_get_contents('php://input'), true);
$sqlInsert = "INSERT INTO patterns (difficulty, char1, char2, char3, char4, char5, char6) ";
//Add an assign difficulty to a pattern
$difficulty = passcode_difficulty($data);
$sqlInsert .= "VALUES ('". $difficulty . "'";

// Add each of the charactes to the end of the SQL statement
foreach ($data['filenames'] as $key => $value) {
    $sqlInsert .= ', \'' . $value . '\'';
}

// Add null values to the end of the sql statment as needed
$count = count($data['filenames']);
if ($count != 6){
    for ($i = 0; $i < (6 - $count); $i++){
        $sqlInsert .= ', null';
    }
}
$sqlInsert .= ")";

// Insert the pattern into the database and then store that result for later
$insertResult = mysqli_query($db, $sqlInsert);

// Set the ID of the pattern created to be connected to the user
$patternID = mysqli_insert_id($db);
$updateUserSQL = "UPDATE users SET user_pattern = '" . $patternID . "' WHERE user_id = " . USER_ID;

$updateResult = mysqli_query($db, $updateUserSQL);

// Make sure that the value got added correctly to the database and updated properly
if ($insertResult && $updateResult) {
    echo json_encode(['status' => 'success', 'data' => ('Insert Result: ' . $insertResult . '  Update Result: ' . $updateResult)]);
} else{
    echo json_encode(['status' => 'error', 'message' => mysqli_error($db)]);
}
?>
