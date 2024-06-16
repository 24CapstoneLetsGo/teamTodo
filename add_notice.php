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

$notice_content = $data['notice_content'];
$todo_id = isset($data['todo_id']) ? $data['todo_id'] : null;
$goal_id = isset($data['goal_id']) ? $data['goal_id'] : null;

// 사용자 정보에서 팀 아이디 가져오기
$email = $_SESSION['email'];
$sql = "SELECT u.team_id FROM users u WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$team_id = $user['team_id'];

$stmt = $conn->prepare("INSERT INTO notices (notice_content, todo_id, goal_id, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sii", $notice_content, $todo_id, $goal_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add notice: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
