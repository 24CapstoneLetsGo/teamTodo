<?php
session_start();

// MySQL 연결 설정
$host = "localhost";
$user = "lego";
$password = "lego";
$dbname = "team_todo";
$charset = "utf8mb4";

// PHP 오류 출력 설정 (디버깅 목적)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset($charset);

// 폼 데이터 수신 및 유효성 검사
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['username']; // 로그인 폼에서 입력받은 이메일
    $password = $_POST['password'];

    // 사용자 정보 조회
    $stmt = $conn->prepare("SELECT passwd FROM users WHERE email=?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->bind_result($hashed_password);
    if (!$stmt->fetch()) {
        // 사용자가 존재하지 않으면
        die("User does not exist.");
    }
    $stmt->close();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        // 로그인 성공
        $_SESSION['email'] = $email;
        header("Location: index.html");
        exit();
    } else {
        // 로그인 실패
        echo "Invalid email or password.";
    }
}

$conn->close();
?>

