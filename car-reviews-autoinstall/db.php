<?php
$DB_HOST = 'fdb1034.awardspace.net';
$DB_USER = '4699601_boomboksb';
$DB_PASS = '22052006Volodya'; // сюди встав реальний пароль від MySQL, який показаний у AwardSpace
$DB_NAME = '4699601_boomboksb';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode(['ok'=>false,'error'=>'DB connect failed: '.$mysqli->connect_error]);
  exit;
}
$mysqli->set_charset('utf8mb4');
?>
