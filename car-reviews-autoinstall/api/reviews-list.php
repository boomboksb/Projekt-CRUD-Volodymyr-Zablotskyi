<?php
require __DIR__.'/common.php';
try {
  $stmt = $pdo->query("SELECT id, created_at, make, model, year, fuel, `usage`, rating, comment, author, date FROM reviews ORDER BY created_at DESC LIMIT 100");
  $rows = $stmt->fetchAll();
  echo json_encode(['ok'=>true, 'data'=>$rows], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>'DB_ERROR', 'details'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
