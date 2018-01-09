<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2017/4/18
 * Time: 0:09
 */
include("dbConnect.php");
$searchText = $_POST['name'];
$searchTextArr = $_POST['searchTextArr'];
$searchTextTyp = $_POST['searchTextTyp'];
$searchTable = $_POST['searchTable'];

$searchResult = "";
$filterResult = "";
$searchResultAll = array();
$sqlDbs = $searchText;

$count = 0;
foreach ($sqlDbs as $sqlDb) {
    $resultDb = $conn->query($sqlDb);
    if ($resultDb->num_rows > 0) {
        $results = $resultDb;
        while($row = $results->fetch_assoc()) {
            $rowFormat = array_map('utf8_encode', $row);
            $rowFormat['Table'] = $searchTable[$count];
            $searchResult .= "<tr>";
            for($j=0; $j<count($searchTextArr); $j++) {
                if(array_key_exists($searchTextArr[$j],$rowFormat)){
                    $searchResult .= "<td>".$rowFormat[$searchTextArr[$j]]."</td>";
                }else{
                    $searchResult .= "<td>-</td>";
                }
            }
            $searchResult .= "</tr>";
        }
    }else {
        echo "0 results";
    }
    $count++;
}
$filterResult .= "<tr>";
for($j=0; $j<count($searchTextArr); $j++) {
    $filterResult .= "<th>";
    $filterResult .= "<input type='text' placeholder='Search' />";
    $filterResult .= "</th>";
}
$filterResult .= "</tr>";
$searchResultAll[0] = $searchResult;
$searchResultAll[1] = $filterResult;
header('Content-Type: application/json; charset=UTF8');
echo json_encode($searchResultAll);
$conn->close();


