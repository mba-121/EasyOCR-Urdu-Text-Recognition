 <?php
session_start();
include 'connection.php'; // Ensure this file is included

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        die("Please fill in both email and password.");
    }

    // Get the user by email (now also retrieving is_admin)
    $query = "SELECT id, username, password, is_admin FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $username, $hashedPassword, $is_admin);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Success - Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $is_admin; // NEW

            // Redirect to homepage or dashboard
            header("Location: index.php");
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>