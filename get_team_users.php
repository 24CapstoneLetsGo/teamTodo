<?php
session_start();
include 'config.php'; // 데이터베이스 연결 파일 포함

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!isset($_GET['team_id'])) {
    echo json_encode(["error" => "No team specified"]);
    exit;
}

$team_id = $_GET['team_id'];

$sql = "SELECT username, email, phone_num, group_name, team_id FROM users WHERE team_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_id); // team_id는 정수형이므로 "i" 사용
$stmt->execute();
$result = $stmt->get_result();
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>
