<?php
include('dbsearch.php'); // Include the database connection

if (isset($_GET['station_name'])) {
    $station_name = urldecode($_GET['station_name']);
    $location = urldecode($_GET['location']);
    $city = urldecode($_GET['city']);
}

// Get the available slots from the database
$sql_get_slots = "SELECT slots FROM charging_stations WHERE station_name = ? AND location = ? AND city = ?";
$stmt_get_slots = $conn->prepare($sql_get_slots);
$stmt_get_slots->bind_param("sss", $station_name, $location, $city);
$stmt_get_slots->execute();
$result_get_slots = $stmt_get_slots->get_result();

if ($result_get_slots->num_rows > 0) {
    $row = $result_get_slots->fetch_assoc();
    $available_slots = $row['slots'];
} else {
    // Handle case when the station is not found (although it should always exist)
    echo "<script>alert('Station not found');</script>";
    exit();
}

// Check if there are any booked slots
$sql_check_booked = "SELECT COUNT(*) AS booked_count FROM booked_slots WHERE station_name = ? AND location = ? AND city = ?";
$stmt_check_booked = $conn->prepare($sql_check_booked);
$stmt_check_booked->bind_param("sss", $station_name, $location, $city);
$stmt_check_booked->execute();
$result_check_booked = $stmt_check_booked->get_result();
$row_booked = $result_check_booked->fetch_assoc();
$has_booked_slots = $row_booked['booked_count'] > 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slots = $_POST['slots'];
    $station_name = $_POST['station_name'];
    $location = $_POST['location'];
    $city = $_POST['city'];

    // Check if there are available slots before booking
    if ($available_slots <= 0) {
        echo "<script>alert('Slots are not available');</script>";
    } else {
        // Check if the user is trying to book more slots than available
        if ($slots > $available_slots) {
            echo "<script>alert('Not enough slots available');</script>";
        } else {
            // Insert into booked_slots table
            $sql = "INSERT INTO booked_slots (station_name, location, city, slots) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $station_name, $location, $city, $slots);
            $stmt->execute();

            // Update available slots in charging_stations table
            $sql_update = "UPDATE charging_stations SET slots = slots - ? WHERE station_name = ? AND location = ? AND city = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("isss", $slots, $station_name, $location, $city);
            $stmt_update->execute();

            // After booking, check if slots became zero
            $available_slots -= $slots;
            if ($available_slots <= 0) {
                echo "<script>alert('Slots are now not available');</script>";
            } else {
                echo "<script>alert('Slot booked successfully');</script>";
            }

            // Update the booked slots flag
            $has_booked_slots = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Slots</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            max-width: 500px;
            text-align: center;
        }
        .card h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 16px;
            margin: 5px 0;
            color: #555;
        }
        .card button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        .card button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3>Book Slots for Station: <?php echo htmlspecialchars($station_name); ?></h3>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
        <p><strong>Available Slots:</strong> <?php echo htmlspecialchars($available_slots); ?></p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="slots">Number of Slots:</label>
                <input type="number" name="slots" id="slots" required min="1" max="<?php echo $available_slots; ?>">
            </div>
            <input type="hidden" name="station_name" value="<?php echo htmlspecialchars($station_name); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
            <input type="hidden" name="city" value="<?php echo htmlspecialchars($city); ?>">
            <button type="submit">Book Slots</button>
        </form>

        <?php if ($has_booked_slots): ?>
            <h3>Booked Station: <?php echo htmlspecialchars($station_name); ?></h3>
            <form method="POST" action="cancel_booking.php">
                <input type="hidden" name="station_name" value="<?php echo htmlspecialchars($station_name); ?>">
                <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
                <input type="hidden" name="city" value="<?php echo htmlspecialchars($city); ?>">
                <input type="hidden" name="slots" value="<?php echo $available_slots; ?>">
                <button type="submit">Cancel Booking</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
