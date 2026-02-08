<!-- Owner login and registration -->
<?php
session_start();
include('db.php'); // Include database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $redirect = ''; // Variable to store the redirect URL

    // Handle owner login
    if ($role == 'owner' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check if the owner exists
        $query = "SELECT * FROM owners WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $owner = $result->fetch_assoc();
            if (password_verify($password, $owner['password'])) {
                // Login successful, set session
                $_SESSION['owner_id'] = $owner['id'];
                $_SESSION['owner_username'] = $owner['username'];
                $redirect = 'ownerdash.html'; // Set the redirect URL for the owner dashboard
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Owner not found!";
        }
    }

    // Handle owner registration
    if ($role == 'owner' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if the username already exists
        $query = "SELECT * FROM owners WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists!";
        } else {
            // Insert the new owner into the database
            $query = "INSERT INTO owners (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sss', $username, $email, $password);
            if ($stmt->execute()) {
                echo "Registration successful! <a href='index.html'>Login</a>";
                $redirect = 'index.html'; // Redirect to owner login page after registration
            } else {
                echo "Error during registration!";
            }
        }
    }

    // If a redirect URL is set, echo JavaScript for redirection
    if (!empty($redirect)) {
        echo "<script type='text/javascript'>window.location.href = '$redirect';</script>";
        exit;
    }
}
?>
