-- 데이터베이스 사용
USE team_todo;

-- 샘플 팀 데이터 삽입
INSERT INTO teams (team_id, team_name) VALUES
(1, 'designer'),
(2, 'front-end'),
(3, 'back-end'),
(4, 'ai'),
(5, 'planner');

-- 샘플 사용자 데이터 삽입
INSERT INTO users (email, username, phone_num, group_name, team_id, passwd) VALUES
('shin@dankook.ac.kr', 'shin', '1012345678', 'Dankook Univ', 1, '111'),
('seo@dankook.ac.kr', 'seo', '1023456789', 'Dankook Univ', 2, '111'),
('moon@dankook.ac.kr', 'moon', '1034567890', 'Dankook Univ', 3, '111');

-- 샘플 목표 데이터 삽입
INSERT INTO goals (goal_name, team_id) VALUES
('Cloud', 1),
('capstone', 1),
('cloud', 2),
('Capstone', 3);

-- 샘플 할 일 데이터 삽입
INSERT INTO todo (todo_content, is_done, goal_id, team_id, email) VALUES
('UI desing', 0, 1, 1, 'shin@dankook.ac.kr'),
('data anal', 0, 2, 1, 'shin@dankook.ac.kr'),
('model trainning', 0, 3, 2, 'seo@dankook.ac.kr');
