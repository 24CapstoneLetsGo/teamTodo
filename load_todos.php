<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$email = $_SESSION['email'];

// 사용자 정보 가져오기
$user_stmt = $conn->prepare("SELECT team_id FROM users WHERE email = ?");
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$team_id = $user['team_id'];

// 할 일 및 제목 정보 가져오기
$goals_stmt = $conn->prepare("SELECT goal_id, goal_name FROM goals WHERE team_id = ?");
$goals_stmt->bind_param("i", $team_id);
$goals_stmt->execute();
$goals_result = $goals_stmt->get_result();
$goals = $goals_result->fetch_all(MYSQLI_ASSOC);

$todos_stmt = $conn->prepare("SELECT todo_id, todo_content, is_done, goal_id, email FROM todo WHERE team_id = ?");
$todos_stmt->bind_param("i", $team_id);
$todos_stmt->execute();
$todos_result = $todos_stmt->get_result();
$todos = $todos_result->fetch_all(MYSQLI_ASSOC);

echo json_encode(["goals" => $goals, "todos" => $todos]);

$goals_stmt->close();
$todos_stmt->close();
$conn->close();
?>
