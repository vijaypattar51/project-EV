<?php
include('dbsearch.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $station_name = $_POST['station_name'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $slots = $_POST['slots']; // Slots to cancel

    // Check the booked slots first
    $sql_check_slots = "SELECT slots FROM booked_slots WHERE station_name = ? AND location = ? AND city = ? LIMIT 1";
    $stmt_check_slots = $conn->prepare($sql_check_slots);
    $stmt_check_slots->bind_param("sss", $station_name, $location, $city);
    $stmt_check_slots->execute();
    $result = $stmt_check_slots->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $slots_to_cancel = $row['slots'];

        // Remove booking from booked_slots table
        $sql_delete = "DELETE FROM booked_slots WHERE station_name = ? AND location = ? AND city = ? LIMIT 1";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("sss", $station_name, $location, $city);
        $stmt_delete->execute();

        // Update available slots in charging_stations
        $sql_update = "UPDATE charging_stations SET slots = slots + ? WHERE station_name = ? AND location = ? AND city = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("isss", $slots_to_cancel, $station_name, $location, $city);
        $stmt_update->execute();

        echo "<script>alert('Booking cancelled successfully');</script>";
        echo "<script>window.location.href = 'search.php';</script>";
    } else {
        echo "<script>alert('No bookings found to cancel');</script>";
        echo "<script>window.location.href = 'search.php';</script>";
    }
}
?>
