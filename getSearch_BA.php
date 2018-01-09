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
$searchTable = $_POST['searchTable'];
if(isset($_POST["group"]) && $_POST["group"] != "none"){
    $groupStr = " ORDER BY `".$_POST['group']."`";
}else {
    $groupStr = ""; //if there's no page number, set it to 1
}
$searchResult = "";
$searchResultAll = array();
$sqlDb = $searchText;
$resultDb = $conn->query($sqlDb);

$item_per_page = 100;
if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
    $page_number = 1; //if there's no page number, set it to 1
}

if ($resultDb->num_rows > 0) {
    // output data of each row
    $totalRow = $resultDb->num_rows;
    $total_pages = ceil($totalRow/$item_per_page);

    //get starting position to fetch the records
    $page_position = (($page_number-1) * $item_per_page);

    //new select with pagination
    $sqlDbNew = $sqlDb.$groupStr." LIMIT $page_position, $item_per_page";

    //Limit our results within a specified range.
    $results = $conn->query($sqlDbNew);

    function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
    {
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
            $pagination .= '<ul class="pagination">';

            $right_links    = $current_page + 3;
            $previous       = $current_page - 3; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link

            if($current_page > 1){
                $previous_link = ($previous==0)? 1: $previous;
                $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
                $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }

            if($first_link){ //if current active page is first link
                $pagination .= '<li class="first active">'.$current_page.'</li>';
            }elseif($current_page == $total_pages){ //if it's the last active link
                $pagination .= '<li class="last active">'.$current_page.'</li>';
            }else{ //regular current link
                $pagination .= '<li class="active">'.$current_page.'</li>';
            }

            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
                }
            }
            if($current_page < $total_pages){
                $next_link = ($i > $total_pages) ? $total_pages : $i;
                $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
            }

            $pagination .= '</ul>';
        }
        return $pagination; //return pagination links
    }

    while($row = $results->fetch_assoc()) {
        $searchResult .= "<tr>";
        $searchResult .= "<td>".$searchTable."</td>";
        $rowFormat = array_map('utf8_encode', $row);
        for($j=0; $j<count($searchTextArr); $j++) {
            $searchResult .= "<td>".$rowFormat[$searchTextArr[$j]]."</td>";
        }
        $searchResult .= "</tr>";
    }
    $searchResultAll[0] = $searchResult;
    $searchResultAll[1] = paginate_function($item_per_page, $page_number, $totalRow, $total_pages);

    $rangeFrom = ($page_number-1)*100+1;
    $rangeTo = $page_number*100;
    if( $rangeTo >= $totalRow){
        $rangeTo = $totalRow;
    }
    $searchResultAll[2] = "<div class='current-number'>Showing entries from ".$rangeFrom." to ".$rangeTo." of ".$totalRow."</div>";


    header('Content-Type: application/json; charset=UTF8');
    echo json_encode($searchResultAll);
} else {
    echo "0 results";
}
$conn->close();


