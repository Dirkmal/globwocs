<?php
session_start();
function requireLogin(): void {
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: login.php');
        exit;
    }
}
function isLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']);
}
