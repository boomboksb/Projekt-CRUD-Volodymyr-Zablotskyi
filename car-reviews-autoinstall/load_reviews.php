<?php
header('Content-Type: application/json');
require_once 'db.php';
$limit = isset($_GET['limit']) ? max(1, min(200, intval($_GET['limit']))) : 100;
// Backticki dla nazw kolumn
$q = "SELECT id, make, model, year, fuel, `usage`, gearbox, `condition`, bodytype, rating, comment, author, date, created_at
      FROM reviews
      ORDER BY created_at DESC, id DESC
      LIMIT ".$limit;
$result = $mysqli->query($q);
if (!$result) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>$mysqli->error]); exit; }
$rows = [];
while ($row = $result->fetch_assoc()) { $rows[] = $row; }
echo json_encode(['ok'=>true,'items'=>$rows]);
?>