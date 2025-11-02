<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'error'=>'Method not allowed']); exit; }
require_once 'db.php';
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id = isset($body['id']) ? intval($body['id']) : 0;
if ($id <= 0) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Zły id']); exit; }
$stmt = $mysqli->prepare("DELETE FROM reviews WHERE id=?");
$stmt->bind_param('i', $id);
$ok = $stmt->execute();
if (!$ok) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>$stmt->error]); exit; }
echo json_encode(['ok'=>true]);
?>