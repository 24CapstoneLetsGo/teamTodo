<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// 디버그를 위해 현재 세션 이메일 출력
echo "Logged in user email: " . $_SESSION['email'] . "<br>";

$host = 'localhost';
$dbname = 'team_todo';
$username = 'lego';
$password = 'lego';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT username, email, phone_num, group_name, team_name FROM users WHERE email = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userInfo) {
        echo json_encode($userInfo);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
