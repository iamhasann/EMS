<?php
session_start();
require_once('../../config/database.php');

$sql = "SELECT id, first_name, last_name, phone, latitude, longitude 
        FROM users_tb 
        WHERE latitude IS NOT NULL AND longitude IS NOT NULL";

$result = mysqli_query($link, $sql);
$locations = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $locations[] = $row;
    }
}

echo json_encode($locations);
?>
