<?php
include 'config.php'; // 데이터베이스 연결 파일 포함

$team_name = isset($_GET['team_name']) ? $_GET['team_name'] : '';

if (empty($team_name)) {
    echo json_encode(["error" => "Invalid team name"]);
    exit;
}

$sql = "SELECT u.username, u.email, u.phone_num, u.group_name, t.team_name 
        FROM users u 
        JOIN teams t ON u.team_id = t.team_id 
        WHERE t.team_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $team_name);
$stmt->execute();
$result = $stmt->get_result();
$users = [];

while ($user = $result->fetch_assoc()) {
    $users[] = $user;
}

if (empty($users)) {
    echo json_encode(["error" => "No users found"]);
} else {
    echo json_encode($users);
}
?>
