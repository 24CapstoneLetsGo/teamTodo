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

$todo_id = $data['id'];
$is_done = $data['is_done'];

// 할 일 완료 상태 업데이트
$stmt = $conn->prepare("UPDATE todo SET is_done = ? WHERE todo_id = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Failed to prepare query: " . $conn->error]);
    exit;
}
$stmt->bind_param("ii", $is_done, $todo_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update todo: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
