<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlashMessage(string $message, string $type = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type // 'success', 'danger', 'warning', 'info'
    ];
}

function getFlashMessage(): ?array {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']); // Remove após exibir uma vez
        return $flash;
    }
    return null;
}

function hasFlashMessage(): bool {
    return isset($_SESSION['flash_message']);
}
