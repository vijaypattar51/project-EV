<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_id = $_POST['station_id'];
    $user_id = 1; // Replace with actual user ID from login
    $booking_date = date('Y-m-d H:i:s');

    $query = "INSERT INTO bookings (station_id, user_id, booking_date, status) 
              VALUES ('$station_id', '$user_id', '$booking_date', 'Booked')";

    if (mysqli_query($conn, $query)) {
        echo "Booking successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
