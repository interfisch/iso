<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2017/3/17
 * Time: 0:53
 */
include("dbConnect.php");
$tableName = $_POST['name'];
$Fields = array();
$sqlDb = "SELECT `Field`, `Subtable`, `Field code`, `Filter`, `Type`, `Coordinates` FROM `TableX Metadata` WHERE `TableX` = '$tableName'";
$resultDb = $conn->query($sqlDb);
if ($resultDb->num_rows > 0) {
    // output data of each row
    while($row = $resultDb->fetch_assoc()) {
        if($row['Field'] != ""){
            array_push($Fields, $row);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($Fields);
} else {
    echo "0 results";
}
$conn->close();