<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About-EV Spot</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    
    <link href="usercss.css" rel="stylesheet">


    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 250px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }
        .card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .card button.book-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .card button.book-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body class="about-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">EV Spot</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html" class="active">User Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Profile</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">User Dashboard</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

  </main>

  <div style="text-align: center; margin-top: 20px; padding: 5px; border-radius: 5px">
    <h5> <b> Search Charging Stations <b> </h5>
    <form method="GET" action="search.php" style="display: inline-block;">
        <input type="text" name="search" id="searchBox" placeholder="Enter city or station name" required>
        <button type="submit">Search</button>
    </form>
</div>


<?php
include('dbsearch.php'); // Include the database connection

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $sql = "SELECT * FROM charging_stations WHERE station_name LIKE ? OR city LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h4><b>Search Results:</b></h4>";
        echo "<div class='card-container'>";

        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>" . htmlspecialchars($row['station_name']) . "</h3>
                    <p><strong>Owner Email:</strong> " . htmlspecialchars($row['owner_email']) . "</p>
                    <p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>
                    <p><strong>City:</strong> " . htmlspecialchars($row['city']) . "</p>
                    <p><strong>Slots:</strong> " . htmlspecialchars($row['slots']) . "</p>
                    <button class='book-button' onclick=\"window.location.href='book_form.php?station_name=" . urlencode($row['station_name']) . 
                        "&location=" . urlencode($row['location']) . 
                        "&city=" . urlencode($row['city']) . 
                        "&slots=" . $row['slots'] . "'\">Book Now</button>
                  </div>";
        }

        echo "</div>";
    } else {
        echo "<p>No results found for '$search'.</p>";
    }
}
?>
    
    <footer id="footer" class="footer dark-background" style="position: relative; bottom: 0; left: 0; width: 100%; margin-top: 20px; z-index: 1000;">
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
            </div>
        </div>
        <div class="credits">
            <h4>Designed by team EV spot</h4>
        </div>
    </div>
</footer>



  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>