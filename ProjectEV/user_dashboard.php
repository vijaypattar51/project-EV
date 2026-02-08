<?php
include 'db_connection.php';

// Fetch data from the `charging_stations` table
$query = "SELECT * FROM charging_stations";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* Basic styling for cards */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }

        .card {
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            background-color: #fff;
        }

        .card img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .card p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .book-btn {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
        }

        .book-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Available Charging Stations</h1>
    <div class="container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img src="charging_icon.png" alt="Charging Station">
                
                <p>Station: <?php echo $row['station_name']; ?></p>
                <p>Email: <?php echo $row['owner_email']; ?></p>
                <p>Contact: <?php echo $row['phone_number']; ?></p>
                <form action="book_slot.php" method="POST">
                    <button type="submit" class="book-btn">Book</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
