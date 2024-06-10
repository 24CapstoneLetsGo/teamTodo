<?php
session_start();

// 디버그용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL 연결 설정
include 'config.php';
$conn->set_charset("utf8mb4");

// 폼 데이터 수신 및 유효성 검사
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // 이름(username)
    $email = $_POST['email']; // 이메일(email)
    $phone_num = $_POST['phone_num']; // 전화번호(phone_num)
    $group_name = $_POST['group_name']; // 소속(group_name)
    $team_name = $_POST['team_name']; // 직무(team_name)
    $passwd = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($passwd !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // team_id 가져오기
    $stmt = $conn->prepare("SELECT team_id FROM teams WHERE team_name = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $team_name);
    $stmt->execute();
    $stmt->bind_result($team_id);
    $stmt->fetch();
    $stmt->close();

    if (!$team_id) {
        echo "Team not found.";
        exit();
    }

    // 사용자 정보 삽입
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone_num, group_name, team_name, passwd, team_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // 전화번호를 문자열로 바인딩하기 위해 ssssssi로 변경
    $stmt->bind_param("ssssssi", $username, $email, $phone_num, $group_name, $team_name, $passwd, $team_id);
    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
