<?php
session_start();
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit('Forbidden'); }
$user_id = (int)$_SESSION['user_id'];
$acts_id = filter_input(INPUT_POST, 'acts_id', FILTER_VALIDATE_INT);
if (!$acts_id || empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400); exit('Invalid request');
}
$check = $conn->prepare('SELECT id FROM act_logs WHERE user_id=? AND acts_id=? LIMIT 1');
$check->bind_param('ii', $user_id, $acts_id);
$check->execute();
if ($check->get_result()->num_rows) { http_response_code(409); exit('เข้าร่วมแล้ว'); }

$file = $_FILES['file'];
if ($file['size'] > 5 * 1024 * 1024) { http_response_code(400); exit('File too large'); }
$type = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
$extensions = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','application/pdf'=>'pdf'];
if (!isset($extensions[$type])) { http_response_code(400); exit('File type not allowed'); }
$name = bin2hex(random_bytes(16)) . '.' . $extensions[$type];
$target = __DIR__ . '/uploads/' . $name;
if (!move_uploaded_file($file['tmp_name'], $target)) { http_response_code(500); exit('Upload failed'); }
$stmt = $conn->prepare("INSERT INTO act_logs (user_id, acts_id, joined_at, files, status) VALUES (?, ?, NOW(), ?, 'pending')");
$stmt->bind_param('iis', $user_id, $acts_id, $name);
try { $stmt->execute(); }
catch (Throwable $e) { unlink($target); http_response_code(500); exit('Save failed'); }
exit('ส่งหลักฐานสำเร็จ');
