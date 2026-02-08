<!-- User login and registration -->
<?php
session_start();
include('db.php'); // Include database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $redirect = ''; // Variable to store the redirect URL

    // Handle user login
    if ($role == 'user' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login successful, set session
                $_SESSION['username'] = $user['username'];
                $redirect = 'search.php'; // Set the redirect URL for the user dashboard
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "User not found!";
        }
    }

    // Handle user registration
    if ($role == 'user' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if the username already exists
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists!";
        } else {
            // Insert the new user into the database
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sss', $username, $email, $password);
            if ($stmt->execute()) {
                echo "Registration successful! <a href='index.html'>Login</a>";
                $redirect = 'index.html'; // Redirect to user login page after registration
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
