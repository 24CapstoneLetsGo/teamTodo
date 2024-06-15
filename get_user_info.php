<?php
session_start();
include 'config.php'; // 데이터베이스 연결 파일 포함

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$email = $_SESSION['email'];

$sql = "SELECT u.username, u.email, u.phone_num, u.group_name, t.team_name 
        FROM users u 
        JOIN teams t ON u.team_id = t.team_id 
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode($user);
?>

