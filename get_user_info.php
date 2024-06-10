<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include 'config.php'; // 데이터베이스 연결 파일 포함
$conn->set_charset("utf8mb4");

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT username, email, phone_num, group_name, team_id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
    $stmt->close();
    $conn->close();
    exit();
}
$user = $result->fetch_assoc();

$sql = "SELECT team_name FROM teams WHERE team_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit();
}
$stmt->bind_param("i", $user['team_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(["error" => "Team not found"]);
    $stmt->close();
    $conn->close();
    exit();
}
$team = $result->fetch_assoc();
$user['team_name'] = $team['team_name'];

echo json_encode($user);

$stmt->close();
$conn->close();
?>
