<?php
// Oturum başlatma kontrolü
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//--to display PHP errors--
ini_set('display_errors', '1'); // 1 is on, 0 is off
ini_set('display_startup_errors', '1'); // 1 is on, 0 is off
error_reporting(E_ALL);

//--query function--
function berkhoca_query_parser($sql='') {
    //--to connect database--
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "berkhoca_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (empty($sql)) {
        return 'sql statement is empty';
    }
    $query_result = $conn->query($sql);
    if ($query_result === TRUE || $query_result === FALSE) {
        return $query_result;
    }
    $array_result = [];
    while ($row = $query_result->fetch_assoc()) {
        $array_result[] = $row;
    }
    return $array_result;
}
?>
