<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_name = $_POST['station_name'];
    $owner_email = $_POST['owner_email'];
    $phone_number = $_POST['phone_number'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $slots = $_POST['slots'];

    $query = "INSERT INTO charging_stations (station_name, owner_email, phone_number, location, city, slots) 
              VALUES ('$station_name', '$owner_email', '$phone_number', '$location', '$city','$slots')";

    if (mysqli_query($conn, $query)) {
        $redirect = 'index.html';
    }
}
?>
