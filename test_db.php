<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=Portfolio;charset=utf8mb4', 'root', 'root');
$stmt = $pdo->query('SELECT id, title, image FROM project');
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->query('SELECT id, project_id, image_name FROM project_image');
$images = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "PROJECTS:\n";
echo json_encode($projects, JSON_PRETTY_PRINT) . "\n";
echo "IMAGES:\n";
echo json_encode($images, JSON_PRETTY_PRINT) . "\n";
