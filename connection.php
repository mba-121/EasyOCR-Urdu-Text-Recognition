 <?php
// Database credentials
$host = "sql100.infinityfree.com";
$user = "if0_39386258";
$password = "Easyocr123";
$database = "if0_39386258_easyocrconverter";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8mb4");
?>