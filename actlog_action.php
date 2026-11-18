<?php
require_once __DIR__ . '/admin_guard.php';
require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$point = filter_input(INPUT_POST, 'point', FILTER_VALIDATE_INT);
if (($_POST['action'] ?? '') !== 'score' || !$id || $point === null || $point === false) {
    http_response_code(400); exit('Invalid action');
}
$stmt = $conn->prepare('SELECT a.point AS max_point FROM act_logs al JOIN acts a ON al.acts_id=a.id WHERE al.id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row || $point < 0 || $point > (int)$row['max_point']) {
    http_response_code(400); exit('Invalid score');
}
$stmt = $conn->prepare("UPDATE act_logs SET point_earn=?, status='checked' WHERE id=?");
$stmt->bind_param('ii', $point, $id);
$stmt->execute();
exit('ok');
