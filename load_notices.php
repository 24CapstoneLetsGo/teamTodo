<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$email = $_SESSION['email'];

// 사용자 정보에서 팀 아이디 가져오기
$sql = "SELECT u.team_id FROM users u WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$team_id = $user['team_id'];

// 해당 팀 아이디의 모든 노티스 가져오기
$sql = "
    SELECT n.notice_content 
    FROM notices n
    LEFT JOIN todo t ON n.todo_id = t.todo_id
    LEFT JOIN goals g ON n.goal_id = g.goal_id
    WHERE t.team_id = ? OR g.team_id = ?
    ORDER BY n.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $team_id, $team_id);
$stmt->execute();
$result = $stmt->get_result();

$notices = [];
while ($row = $result->fetch_assoc()) {
    $notices[] = $row['notice_content'];
}

echo json_encode(["notices" => $notices]);

$conn->close();
?>
