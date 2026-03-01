<?php
$uploadDir = '..uploads/projects/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        die('Failed to create upload directory: ' . $uploadDir);
    }
}

if (!is_writable($uploadDir)) {
    die('Upload directory is not writable: ' . realpath($uploadDir));
}
?>