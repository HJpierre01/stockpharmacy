<?php
// Connect to the database
$conn = new mysqli("127.0.0.1", "root", "", "pharmacy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];   // User input name
    $password = $_POST['password']; // User input password

    // Query to fetch the user from the database
    $stmt = $conn->prepare("SELECT * FROM account WHERE fullname = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Use password_verify to check if the hashed password matches
        if (password_verify($password, $row['password'])) {
            // Login successful, redirect to chef.html
            header("Location: chef.html");
            exit();
        } else {
            // If password is incorrect
            echo "<script>alert('Wrong password. Please try again.'); window.location.href = 'log.html';</script>";
        }
    } else {
        // If username doesn't exist in the database
        echo "<script>alert('User not found. Please check your username or register.'); window.location.href = 'log.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>