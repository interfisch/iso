<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2016/10/31
 * Time: 11:02
 */
include("dbConnect.php");
$tableName = array();
$subTableName = array();
$sqlDb = "SELECT DISTINCT TableX FROM `TableX Metadata` ";
$resultDb = $conn->query($sqlDb);
if ($resultDb->num_rows > 0) {
    // output data of each row
    while($row = $resultDb->fetch_assoc()) {
        $rowTable = $row['TableX'];
        array_push($tableName, $rowTable);
/*        $sqlSubDb = "SELECT DISTINCT Subtable FROM `tablex metadata` WHERE `TableX` = '$rowTable' AND `Visible` = 'yes'";
        $resultSubDb = $conn->query($sqlSubDb);
        if ($resultSubDb->num_rows > 0) {
            // output data of each row
            while($rowSub = $resultSubDb->fetch_assoc()) {
                if($rowSub['Subtable'] != ""){
                    array_push($subTableName, array( $rowTable => $rowSub['Subtable']));
                }
            }
        } else {
            echo "0 results";
        }   */
    }
} else {
    echo "0 results";
}
$conn->close();