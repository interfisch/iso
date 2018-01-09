<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2016/10/31
 * Time: 21:23
 */
include("dbConnect.php");
$tableName = $_POST['dbName'];
$subTableName = $_POST['dbSubName'];
$Field = array();
$sqlDb = "SELECT DISTINCT Field FROM `TableX Metadata` WHERE `TableX` = '$tableName' AND `Subtable` = '$subTableName' AND `Visible` = 'yes'";
$resultDb = $conn->query($sqlDb);
if ($resultDb->num_rows > 0) {
    // output data of each row
    while($row = $resultDb->fetch_assoc()) {
        if($row['Field'] != ""){
            array_push($Field, $row['Field']);
        }
    }
    echo json_encode($Field);
} else {
    echo "0 results";
}
$conn->close();