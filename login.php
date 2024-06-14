<?php
session_start();

// 디버깅용 오류 보고 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL 연결 설정
include 'config.php';
$conn->set_charset("utf8mb4");

// 폼 데이터 수신 및 유효성 검사
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // 로그인 폼에서 입력받은 이메일
    $passwd = $_POST['password'];

    // 사용자 정보 조회
    $stmt = $conn->prepare("SELECT passwd FROM users WHERE email=?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->bind_result($db_password);
    if (!$stmt->fetch()) {
        // 사용자가 존재하지 않으면
        echo "User does not exist.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // 디버깅: 데이터베이스에서 가져온 비밀번호와 입력된 비밀번호 비교
    echo "Database password: " . $db_password . "<br>";
    echo "Provided password: " . $passwd . "<br>";

    if ($db_password === $passwd) {
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
