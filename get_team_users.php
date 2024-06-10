<?php
header('Content-Type: application/json');

// 디버그용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // 데이터베이스 연결 파일 포함
$conn->set_charset("utf8mb4");

if (!isset($_GET['team'])) {
    echo json_encode(['error' => 'Team not specified']);
    exit();
}

$team_name = $_GET['team'];

$sql = "SELECT username, email, phone_num, group_name, team_name FROM users WHERE team_name = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit();
}
$stmt->bind_param("s", $team_name);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($user = $result->fetch_assoc()) {
    $users[] = $user;
}

echo json_encode($users);

$stmt->close();
$conn->close();
?>
