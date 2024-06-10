<?php
session_start();
ob_start(); // 출력 버퍼링 시작

include 'config.php'; // 데이터베이스 연결 파일 포함

// 디버그용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 폼 데이터 수신 및 유효성 검사
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_num = $_POST['phone_num'];
    $group_name = $_POST['group_name'];
    $team_name = $_POST['team_name'];
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

    // 사용자 정보 삽입 (team_id 사용)
    $stmt = $conn->prepare("INSERT INTO users (email, username, phone_num, group_name, team_id, passwd) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // 전화번호를 문자열로 바인딩하기 위해 ssssssi로 변경
    $stmt->bind_param("sssssi", $email, $username, $phone_num, $group_name, $team_id, $passwd);
    if ($stmt->execute()) {
        header("Location: login.html");
        ob_end_flush(); // 출력 버퍼링 끝내기
        exit();
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
ob_end_flush(); // 출력 버퍼링 끝내기
?>
