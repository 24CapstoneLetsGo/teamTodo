<?php
session_start();

// 디버그용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL 연결 설정
$host = "localhost";
$user = "lego";
$password = "lego";
$dbname = "team_todo";
$charset = "utf8mb4";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset($charset);

// 폼 데이터 수신 및 유효성 검사
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['name'];          // 이름(username)
    $email = $_POST['username'];         // 이메일(email)
    $phone_num = $_POST['phone1'] . '-' . $_POST['phone2'] . '-' . $_POST['phone3'];  // 전화번호(phone_num)
    $group_name = $_POST['position'];    // 직무(group_name)
    $team_name = $_POST['affiliation'];  // 소속(team_name)
    $passwd = $_POST['password'];        // 비밀번호(passwd)
    $confirm_password = $_POST['confirm-password'];

    if ($passwd !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // 비밀번호 해시화
    $passwd_hashed = password_hash($passwd, PASSWORD_BCRYPT);

    // 사용자 정보 삽입
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone_num, group_name, team_name, passwd) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $username, $email, $phone_num, $group_name, $team_name, $passwd_hashed);
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

