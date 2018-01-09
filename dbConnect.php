<?php
/**
 * Created by PhpStorm.
 * User: Feng Feng
 * Date: 2016/10/31
 * Time: 15:47
 */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isomemo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}