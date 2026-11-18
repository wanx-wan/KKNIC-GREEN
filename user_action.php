<?php
require_once __DIR__ . '/admin_guard.php';
require_once __DIR__ . '/db.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';
if (!$id || $id < 1) { http_response_code(400); exit('Invalid user'); }

if ($action === 'edit') {
    $name = trim($_POST['name'] ?? '');
    $role = $_POST['role'] ?? '';
    if ($name === '' || !in_array($role, ['admin', 'user'], true)) {
        http_response_code(400); exit('Invalid data');
    }
    $stmt = $conn->prepare('UPDATE users SET firstname=?, role=? WHERE id=?');
    $stmt->bind_param('ssi', $name, $role, $id);
    $stmt->execute();
    if (($_SESSION['user_id'] ?? null) == $id) {
        $_SESSION['firstname'] = $name;
        $_SESSION['role'] = $role;
    }
    exit('ok');
}
if ($action === 'delete') {
    if (($_SESSION['user_id'] ?? null) == $id) { http_response_code(400); exit('Cannot delete yourself'); }
    $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    exit('ok');
}
if ($action === 'toggle') {
    $stmt = $conn->prepare("UPDATE users SET status=IF(status='active','inactive','active') WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt = $conn->prepare('SELECT status FROM users WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    exit($row['status'] ?? 'error');
}
http_response_code(400);
exit('Unknown action');
