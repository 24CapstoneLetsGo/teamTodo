<?php
session_start();
header('Content-Type: application/json');

// 디버그용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // 데이터베이스 연결 파일 포함
$conn->set_charset("utf8mb4");

if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = $_POST['team_name'];

    // team_id 가져오기
    $stmt = $conn->prepare("SELECT team_id FROM teams WHERE team_name = ?");
    if ($stmt === false) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $team_name);
    $stmt->execute();
    $stmt->bind_result($team_id);
    $stmt->fetch();
    $stmt->close();

    if (!$team_id) {
        echo json_encode(['error' => 'Team not found']);
        exit();
    }

    // 사용자 정보 업데이트
    $stmt = $conn->prepare("UPDATE users SET team_id = ? WHERE email = ?");
    if ($stmt === false) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("is", $team_id, $email);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Profile updated successfully']);
    } else {
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
