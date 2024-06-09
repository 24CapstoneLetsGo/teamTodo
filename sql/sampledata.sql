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
('shin@dankook.ac.kr', 'shin', '010-1234-5678', '단국대학교', 1, '111'),
('seo@dankook.ac.kr', 'seo', '010-2345-6789', '단국대학교', 2, '111'),
('moon@dankook.ac.kr', 'moon', '010-3456-7890', '단국대학교', 3, '111');


-- 샘플 목표 데이터 삽입
INSERT INTO goals (goal_name, team_id) VALUES
('클컴', 1),
('캡스톤', 1),
('클컴', 2),
('캡스톤', 3);

-- 샘플 할 일 데이터 삽입
INSERT INTO todo (todo_content, is_done, goal_id) VALUES
('UI 디자인', 0, 1),
('데이터 분석', 0, 2),
('모델 트레이닝', 0, 3);
