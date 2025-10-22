<?php
require __DIR__.'/common.php';

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);

$make   = trim($body['make']  ?? '');
$model  = trim($body['model'] ?? '');
$year   = (int)($body['year'] ?? 0);
$fuel   = $body['fuel']  ?? '';
$usage  = $body['usage'] ?? '';
$rating = (int)($body['rating'] ?? 0);
$comment= trim($body['comment'] ?? '');
$author = trim($body['author']  ?? '');
$date   = $body['date']  ?? null;

$allowedFuel  = ['petrol','diesel','hybrid','electric'];
$allowedUsage = ['owned','rented','testdrive','other'];

if ($make==='' || $model==='' || $year<1980 || $year>2099 ||
    !in_array($fuel,$allowedFuel,true) || !in_array($usage,$allowedUsage,true) ||
    $rating<1 || $rating>5) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'VALIDATION_ERROR'], JSON_UNESCAPED_UNICODE);
  exit;
}

try {
  $stmt = $pdo->prepare("
    INSERT INTO reviews
      (make, model, year, fuel, `usage`, rating, comment, author, date)
    VALUES
      (:make, :model, :year, :fuel, :usage, :rating, :comment, :author, :date)
  ");
  $stmt->execute([
    ':make'=>$make, ':model'=>$model, ':year'=>$year,
    ':fuel'=>$fuel, ':usage'=>$usage, ':rating'=>$rating,
    ':comment'=>$comment!=='' ? $comment : null,
    ':author'=>$author!=='' ? $author : null,
    ':date'=>$date ?: null
  ]);
  echo json_encode(['ok'=>true], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'DB_ERROR','details'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
