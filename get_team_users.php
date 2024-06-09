<?php
session_start();
include 'config.php'; // 데이터베이스 연결 파일 포함

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!isset($_GET['team'])) {
    echo json_encode(["error" => "No team specified"]);
    exit;
}

$team = $_GET['team'];

$sql = "SELECT username, email, phone_num, group_name, team_name FROM users WHERE team_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $team);
$stmt->execute();
$result = $stmt->get_result();
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>

