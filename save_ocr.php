 <?php
session_start();
include '../php/connection.php'; // adjust path if needed

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $filename = $_POST['filename'] ?? '';
    $extracted_text = $_POST['text'] ?? '';

    if (!$filename || !$extracted_text) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing data']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO uploads (user_id, filename, extracted_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $filename, $extracted_text);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
?>