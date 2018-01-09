<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2016/10/31
 * Time: 15:43
 */
include("dbConnect.php");
$tableName = $_POST['name'];
$subTableName = array();
$sqlDb = "SELECT DISTINCT Subtable FROM `TableX Metadata` WHERE `TableX` = '$tableName' AND `Visible` = 'yes'";
$resultDb = $conn->query($sqlDb);
if ($resultDb->num_rows > 0) {
    // output data of each row
    while($row = $resultDb->fetch_assoc()) {
        if($row['Subtable'] != ""){
            array_push($subTableName, $row['Subtable']);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($subTableName);
} else {
    echo "0 results";
}
$conn->close();