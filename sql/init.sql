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
    team_name VARCHAR(50) NOT NULL
);

-- 사용자 테이블 생성
CREATE TABLE IF NOT EXISTS users (
    email VARCHAR(100) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    phone_num VARCHAR(15) NOT NULL,
    group_name VARCHAR(20) NOT NULL,
    team_id INT,
    passwd VARCHAR(100) NOT NULL,
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    INDEX idx_team_id (team_id),
    INDEX idx_username (username)
);

-- 목표 테이블 생성
CREATE TABLE IF NOT EXISTS goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    goal_name VARCHAR(50) NOT NULL,
    team_id INT,
    username VARCHAR(50),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (username) REFERENCES users(username),
    INDEX idx_team_id (team_id),
    INDEX idx_goals_username (username)
);

-- 할 일 테이블 생성
CREATE TABLE IF NOT EXISTS todo (
    todo_id INT AUTO_INCREMENT PRIMARY KEY,
    todo_content VARCHAR(200) NOT NULL,
    is_done TINYINT NOT NULL DEFAULT 0,
    goal_id INT,
    team_id INT,
    username VARCHAR(50),
    FOREIGN KEY (goal_id) REFERENCES goals(goal_id),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (username) REFERENCES users(username),
    INDEX idx_goal_id (goal_id),
    INDEX idx_team_id (team_id),
    INDEX idx_todo_username (username)
);
