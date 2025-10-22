<?php
require __DIR__.'/common.php';

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
$id = (int)($body['id'] ?? 0);

if ($id <= 0) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'BAD_ID'], JSON_UNESCAPED_UNICODE);
  exit;
}

try {
  $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
  $stmt->execute([':id'=>$id]);
  echo json_encode(['ok'=>true], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'DB_ERROR','details'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
