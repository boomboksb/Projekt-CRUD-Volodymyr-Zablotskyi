<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'error'=>'Method not allowed']); exit; }
require_once 'db.php';
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$fields = ['make','model','year','fuel','usage','gearbox','condition','bodytype','rating','comment','author','date'];
$data = [];
foreach ($fields as $f) { $data[$f] = isset($body[$f]) ? trim((string)$body[$f]) : null; }
if ($data['make']==='' || $data['model']==='' || !is_numeric($data['year'])) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Brak wymaganych pól']); exit; }
$rating = (int)$data['rating']; if ($rating < 1 || $rating > 5) $rating = 5;

// Używamy backticków dla kolumn-zastrzeżonych (`usage`, `condition`)
$stmt = $mysqli->prepare("
INSERT INTO reviews (make, model, year, fuel, `usage`, gearbox, `condition`, bodytype, rating, comment, author, date)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param('ssisssssssss',
  $data['make'], $data['model'], $data['year'], $data['fuel'], $data['usage'],
  $data['gearbox'], $data['condition'], $data['bodytype'], $rating, $data['comment'], $data['author'], $data['date']
);
$ok = $stmt->execute();
if (!$ok) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>$stmt->error]); exit; }
echo json_encode(['ok'=>true,'id'=>$mysqli->insert_id]);
?>