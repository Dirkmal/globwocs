<?php
// admin/upload-image.php — receives ONE base64 image at a time via AJAX
header('Content-Type: application/json');

require_once 'includes/auth.php';
if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Not authenticated']);
    exit;
}
require_once '../includes/db.php';
$db = getDB();

$projectId = (int)($_POST['project_id'] ?? 0);
$dataUrl   = $_POST['image_data']  ?? '';
$caption   = trim($_POST['caption'] ?? '');
$isCover   = (int)($_POST['is_cover'] ?? 0);
$sortOrder = (int)($_POST['sort_order'] ?? 0);

if (!$projectId || !$dataUrl) {
    echo json_encode(['ok' => false, 'error' => 'Missing data']);
    exit;
}

// Validate project exists
$check = $db->prepare("SELECT id FROM projects WHERE id=?");
$check->execute([$projectId]);
if (!$check->fetch()) {
    echo json_encode(['ok' => false, 'error' => 'Project not found']);
    exit;
}

// Decode base64
if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $dataUrl, $m)) {
    echo json_encode(['ok' => false, 'error' => 'Invalid image format']);
    exit;
}
$ext    = ($m[1] === 'jpg') ? 'jpeg' : $m[1];
$binary = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
if (!$binary || strlen($binary) < 100) {
    echo json_encode(['ok' => false, 'error' => 'Image decode failed']);
    exit;
}

$uploadDir = '../uploads/projects/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$filename = uniqid('img_') . '.' . $ext;
if (!file_put_contents($uploadDir . $filename, $binary)) {
    echo json_encode(['ok' => false, 'error' => 'Could not write file — check uploads/ permissions']);
    exit;
}

// If this is the cover, clear existing cover first
if ($isCover) {
    $db->prepare("UPDATE project_images SET is_cover=0 WHERE project_id=?")->execute([$projectId]);
}

$db->prepare("
    INSERT INTO project_images (project_id, filename, caption, is_cover, sort_order)
    VALUES (?, ?, ?, ?, ?)
")->execute([$projectId, $filename, $caption, $isCover, $sortOrder]);

echo json_encode([
    'ok'       => true,
    'filename' => $filename,
    'id'       => (int)$db->lastInsertId(),
]);
