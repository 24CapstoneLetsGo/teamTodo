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

$updates = $data['updates'];

$conn->begin_transaction();

try {
    foreach ($updates as $update) {
        if ($update['type'] === 'task') {
            $stmt = $conn->prepare("UPDATE todo SET position = ? WHERE todo_id = ?");
            $stmt->bind_param("ii", $update['position'], $update['id']);
        } else {
            $stmt = $conn->prepare("UPDATE goals SET position = ? WHERE goal_id = ?");
            $stmt->bind_param("ii", $update['position'], $update['id']);
        }
        $stmt->execute();
        $stmt->close();
    }
    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Failed to update positions: " . $e->getMessage()]);
}

$conn->close();
?>
