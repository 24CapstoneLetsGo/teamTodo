/*
-- lego 사용자 생성 + 권한 주기 
CREATE USER IF NOT EXISTS 'lego'@'localhost' IDENTIFIED BY 'lego';
GRANT ALL PRIVILEGES ON team_todo.* TO 'lego'@'localhost';
COMMIT;
*/
-- lego 사용자 생성 + 권한 주기 
CREATE USER IF NOT EXISTS 'lego'@'%' IDENTIFIED BY 'lego';
GRANT ALL PRIVILEGES ON team_todo.* TO 'lego'@'%';
COMMIT;

-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS team_todo;

-- 데이터베이스 사용
USE team_todo;

-- 팀 테이블 생성
CREATE TABLE IF NOT EXISTS teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(50) NOT NULL,
    INDEX idx_team_name (team_name)
);

-- 사용자 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(100) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    phone_num VARCHAR(15) NOT NULL,
    group_name VARCHAR(20) NOT NULL,
    team_id INT,
    passwd VARCHAR(100) NOT NULL,
    FOREIGN KEY (team_id) REFERENCES teams(team_id)
);

-- 목표 테이블 생성
CREATE TABLE IF NOT EXISTS goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    goal_name VARCHAR(50) NOT NULL,
    team_id INT,
    FOREIGN KEY (team_id) REFERENCES teams(team_id)
);

-- 할 일 테이블 생성
CREATE TABLE IF NOT EXISTS todo (
    todo_id INT AUTO_INCREMENT PRIMARY KEY,
    todo_content VARCHAR(200) NOT NULL,
    is_done TINYINT NOT NULL DEFAULT 0,
    goal_id INT,
    team_id INT,
    email VARCHAR(100),
    FOREIGN KEY (goal_id) REFERENCES goals(goal_id),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (email) REFERENCES users(email)
);

-- 노티스 테이블 생성
CREATE TABLE IF NOT EXISTS notices (
    notice_id INT AUTO_INCREMENT PRIMARY KEY,
    notice_content TEXT NOT NULL,
    todo_id INT,
    goal_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (todo_id) REFERENCES todo(todo_id),
    FOREIGN KEY (goal_id) REFERENCES goals(goal_id)
);
