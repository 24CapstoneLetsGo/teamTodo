<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$email = $_SESSION['email'];

// 사용자 정보 가져오기
$user_stmt = $conn->prepare("SELECT username, team_id FROM users WHERE email = ?");
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$username = $user['username'];
$team_id = $user['team_id'];

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$content = $data['content'];

if ($type === 'title') {
    // goals 테이블에 제목 추가
    $stmt = $conn->prepare("INSERT INTO goals (goal_name, team_id, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $content, $team_id, $username);
} else {
    // todo 테이블에 할 일 추가
    $goal_id = NULL; // 기본적으로 NULL로 설정 (목표에 속하지 않는 할 일)
    $stmt = $conn->prepare("INSERT INTO todo (todo_content, is_done, goal_id, team_id, username) VALUES (?, 0, ?, ?, ?)");
    $stmt->bind_param("siis", $content, $goal_id, $team_id, $username);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add todo"]);
}

$stmt->close();
$conn->close();
?>

