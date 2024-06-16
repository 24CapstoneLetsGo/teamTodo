<?php
include 'config.php';

$team_name = $_GET['team_name'] ?? '';

$query = "
    SELECT 
        t.todo_id, t.todo_content, t.is_done, t.position, 'task' AS type, NULL AS goal_name
    FROM todo t
    JOIN teams tm ON t.team_id = tm.team_id
    WHERE tm.team_name = ?
    UNION
    SELECT 
        g.goal_id, NULL, NULL, g.position, 'title' AS type, g.goal_name
    FROM goals g
    JOIN teams tm ON g.team_id = tm.team_id
    WHERE tm.team_name = ?
    ORDER BY position
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $team_name, $team_name);
$stmt->execute();
$result = $stmt->get_result();

$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($todos);
?>
