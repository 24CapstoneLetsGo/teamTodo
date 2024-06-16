<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// 오늘 생성된 노티스 가져오기
$sql = "SELECT notice_content FROM notices WHERE DATE(created_at) = CURDATE()";
$result = $conn->query($sql);

$notices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row['notice_content'];
    }
}

echo json_encode(["notices" => $notices]);

$conn->close();
?>
