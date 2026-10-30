<?php
// Role-Based Access Control
function check_role($allowed_roles) {
    if (!in_array($_SESSION['user_role'], $allowed_roles)) {
        header('Location: unauthorized.php');
        exit();
    }
}

// Session Security
function secure_session_start() {
    session_start();
    
    if (!isset($_SESSION['ip'])) {
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    } elseif ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
        session_unset();
        session_destroy();
        header('Location: login.php?error=suspicious');
        exit();
    }
}

// GDPR Compliance
function sanitize_data($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>