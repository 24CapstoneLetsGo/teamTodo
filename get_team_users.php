<?php
include 'config.php'; // 데이터베이스 연결 파일 포함

$team_id = isset($_GET['team_id']) ? intval($_GET['team_id']) : 0;

if ($team_id === 0) {
    echo json_encode(["error" => "Invalid team ID"]);
    exit;
}

$sql = "SELECT u.username, u.email, u.phone_num, u.group_name, t.team_name 
        FROM users u 
        JOIN teams t ON u.team_id = t.team_id 
        WHERE u.team_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();
$users = [];

while ($user = $result->fetch_assoc()) {
    $users[] = $user;
}

echo json_encode($users);
?>

