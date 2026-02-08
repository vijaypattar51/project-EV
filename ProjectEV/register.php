<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $username = strtolower($_POST['username']); // Convert to lowercase
    $email = strtolower($_POST['Email']);       // Optional: Convert email to lowercase
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);


    if ($role == 'user') {
        $table = 'users';
    } elseif ($role == 'owner') {
        $table = 'owners';
    } else {
        die("Invalid role.");
    }

    $sql = "INSERT INTO $table (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo ucfirst($role) . " registration successful!";
        header("Location: index.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
