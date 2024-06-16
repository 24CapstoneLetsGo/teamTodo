<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "message" => "JSON decode error: " . json_last_error_msg()]);
    exit;
}

$todoId = $data['id'];
$type = $data['type'];

if ($type === "task") {
    $sql = "DELETE FROM todo WHERE todo_id = ?";
} else if ($type === "title") {
    $sql = "DELETE FROM goals WHERE goal_id = ?";
} else {
    echo json_encode(["success" => false, "message" => "Invalid todo type"]);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $todoId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete todo: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
