<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2016/10/31
 * Time: 13:51
 */
$tableName = strtolower($_GET['tableName']);
$tableNameNew = str_replace(" ", "_", $tableName);
$tableName = $_GET['tableName'];

include("dbConnect.php");
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$tableNameNew.'.csv');
$output = fopen('php://output', 'w');

$fieldName = "";
$fieldNameDetail = "";
if($_GET['subFieldName'] != null){
    $subFieldName = json_decode(stripslashes($_GET['subFieldName']));
}

if($_GET['subName'] != "no"){
    $subTableName = json_decode(stripslashes($_GET['subName']));
    foreach($subTableName as $s){
        $fieldName .= "`Subtable` LIKE '".$s."' OR ";
    }
    $fieldName = substr($fieldName,0,-4);
    $sqlDb = "SELECT `Field` FROM `TableX Metadata` WHERE `TableX` LIKE '".$tableName."' AND (".$fieldName.")";
    $resultDb = $conn->query($sqlDb);
    if ($resultDb->num_rows > 0) {
        // output data of each row
        while($row = $resultDb->fetch_assoc()) {
            $fieldNameDetail .= "`".implode("','",array_values($row))."`, ";
        }

        if($subFieldName){
            foreach($subFieldName as $s){
                $fieldNameDetail .= "`".$s."`, ";
            }
        }
        $fieldNameDetail = substr($fieldNameDetail,0,-2);
        $sqlDb2 = "SELECT ".$fieldNameDetail." FROM `$tableName`";
        $resultDb2 = $conn->query($sqlDb2);
        if ($resultDb2->num_rows > 0) {
            // output data of each row
            $i=0;
            while($row = $resultDb2->fetch_assoc()) {
                if($i<=0)
                {
                    fputcsv($output, array_keys($row));
                }
                fputcsv($output, $row);
                $i++;
            }
            fclose($output);
        } else {
            echo json_encode("0 results");
        }
    } else {
        echo json_encode("0 results");
    }
}else{
    if($subFieldName){
        foreach($subFieldName as $s){
            $fieldNameDetail .= "`".$s."`, ";
        }
    }
    $fieldNameDetail = substr($fieldNameDetail,0,-2);
    $sqlDb2 = "SELECT ".$fieldNameDetail." FROM `$tableName`";
    $resultDb2 = $conn->query($sqlDb2);
    if ($resultDb2->num_rows > 0) {
        // output data of each row
        $i=0;
        while($row = $resultDb2->fetch_assoc()) {
            if($i<=0)
            {
                fputcsv($output, array_keys($row));
            }
            fputcsv($output, $row);
            $i++;
        }
        fclose($output);
    } else {
        echo json_encode("0 results");
    }
}

/*header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$tableNameNew.'.csv');
$output = fopen('php://output', 'w');

$sqlDb = "SELECT * FROM `$tableName` ";
$resultDb = $conn->query($sqlDb);
if ($resultDb->num_rows > 0) {
    // output data of each row
    $i=0;
    while($row = $resultDb->fetch_assoc()) {
        if($i<=0)
        {
            fputcsv($output, array_keys($row));
        }
        fputcsv($output, $row);
        $i++;
    }
    fclose($output);
} else {
    echo json_encode("0 results");
}*/
$conn->close();
