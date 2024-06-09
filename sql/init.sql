-- 데이터베이스 생성
create database team_todo;

-- lego 사용자 생성 + 권한 주기 
create user lego@localhost identified by 'lego';
grant all privileges on team_todo. * to lego@localhost;
commit;

-- 데이터베이스 사용
USE team_todo;

-- 팀 테이블 생성
CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(50) NOT NULL
);

-- 사용자 테이블 생성
CREATE TABLE users (
    username VARCHAR(50) NOT NULL,
    email INT AUTO_INCREMENT PRIMARY KEY,
    phone_num integer not null,
    group_name varchar(20) not null,
    team_name varchar(30) not null,
    passwd VARCHAR(100) NOT NULL,
    team_id INT,
    FOREIGN KEY (team_id) REFERENCES teams(team_id)
);

-- 목표 테이블 생성
CREATE TABLE goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    goal_name VARCHAR(50) NOT NULL,
    team_id INT,
    username VARCHAR(50),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (username) REFERENCES users(username)
);

-- 할 일 테이블 생성
CREATE TABLE todo (
    todo_id INT AUTO_INCREMENT PRIMARY KEY,
    todo_content VARCHAR(200) NOT NULL,
    is_done TINYINT NOT NULL DEFAULT 0,
    goal_id INT,
    team_id INT,
    username VARCHAR(50),
    FOREIGN KEY (goal_id) REFERENCES goals(goal_id),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (username) REFERENCES users(username)
);


'''
-- 공지사항 테이블 생성
CREATE TABLE notice (
    notice_id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT,
    username VARCHAR(50),
    todo_content VARCHAR(200),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (todo_content) REFERENCES todo(todo_content)
);
'''