<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// 오류 메시지 표시 설정 (개발 환경에서만 사용)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$email = $_SESSION['email'];

// 사용자 정보 가져오기
$user_stmt = $conn->prepare("SELECT username, team_id FROM users WHERE email = ?");
if (!$user_stmt) {
    error_log("Failed to prepare user query: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Failed to prepare user query: " . $conn->error]);
    exit;
}
$user_stmt->bind_param("s", $email);
if (!$user_stmt->execute()) {
    error_log("Failed to execute user query: " . $user_stmt->error);
    echo json_encode(["success" => false, "message" => "Failed to execute user query: " . $user_stmt->error]);
    exit;
}
$user_result = $user_stmt->get_result();
if (!$user_result) {
    error_log("Failed to get user result: " . $user_stmt->error);
    echo json_encode(["success" => false, "message" => "Failed to get user result: " . $user_stmt->error]);
    exit;
}
$user = $user_result->fetch_assoc();
$username = $user['username'];
$team_id = $user['team_id'];

$data = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error: " . json_last_error_msg());
    echo json_encode(["success" => false, "message" => "JSON decode error: " . json_last_error_msg()]);
    exit;
}
$type = $data['type'];
$content = $data['content'];
$is_done = isset($data['is_done']) ? $data['is_done'] : 0;

// 로그 추가
error_log("Received data: " . print_r($data, true));
error_log("Type: $type, Content: $content, Team ID: $team_id, Is Done: $is_done");

if ($type === 'title') {
    // goals 테이블에 제목 추가
    $stmt = $conn->prepare("INSERT INTO goals (goal_name, team_id) VALUES (?, ?)");
    if (!$stmt) {
        error_log("Failed to prepare goals query: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Failed to prepare goals query: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("si", $content, $team_id);
} else {
    // todo 테이블에 할 일 추가
    $goal_id = NULL; // 기본적으로 NULL로 설정 (목표에 속하지 않는 할 일)
    $stmt = $conn->prepare("INSERT INTO todo (todo_content, is_done, goal_id, team_id, email) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("Failed to prepare todo query: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Failed to prepare todo query: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("siiis", $content, $is_done, $goal_id, $team_id, $email);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "username" => $username, "todo_id" => $stmt->insert_id]);
} else {
    error_log("Failed to add todo: " . $stmt->error);
    echo json_encode(["success" => false, "message" => "Failed to add todo: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
