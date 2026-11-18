<?php
require_once __DIR__ . '/admin_guard.php';
require_once __DIR__ . '/db.php';

if (($_POST['action'] ?? '') !== 'add') { http_response_code(400); exit('Invalid action'); }
$name = trim($_POST['name'] ?? '');
$point = filter_input(INPUT_POST, 'point', FILTER_VALIDATE_INT);
$start = $_POST['start_at'] ?? '';
$end = $_POST['end_at'] ?? '';
function valid_date(string $date): bool {
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    return $d !== false && $d->format('Y-m-d') === $date;
}
if ($name === '' || $point === false || $point === null || $point < 0 ||
    !valid_date($start) || !valid_date($end) || $end < $start) {
    http_response_code(400); exit('Invalid activity');
}
$stmt = $conn->prepare('INSERT INTO acts (name, point, start_at, end_at, created_at) VALUES (?, ?, ?, ?, NOW())');
$stmt->bind_param('siss', $name, $point, $start, $end);
$stmt->execute();
exit('ok');
