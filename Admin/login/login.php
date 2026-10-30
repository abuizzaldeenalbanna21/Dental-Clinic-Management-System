<?php
session_start();
require 'security_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    $users = json_decode(file_get_contents('admin_users.json'), true);
    
    foreach ($users as $user) {
        if (password_verify($password, $user['password_hash']) && $email === $user['email']) {
            // MFA Check
            $mfa_code = generate_mfa_code();
            send_mfa_code($user, $mfa_code); // Implement SMS/email sending
            
            $_SESSION['mfa_required'] = true;
            $_SESSION['temp_user'] = $user;
            $_SESSION['mfa_code'] = password_hash($mfa_code, PASSWORD_DEFAULT);
            $_SESSION['mfa_expiry'] = time() + 300; // 5 minutes
            
            header('Location: mfa_verify.php');
            exit();
        }
    }
    log_activity('Failed login attempt from ' . $_SERVER['REMOTE_ADDR']);
    $error = "Invalid credentials";
}

function generate_mfa_code() {
    return strval(rand(100000, 999999));
}

function log_activity($action) {
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'user_id' => $_SESSION['user_id'] ?? 'unknown',
        'action' => $action,
        'ip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $logs = json_decode(file_get_contents('activity_logs.json'), true);
    $logs[] = $log;
    file_put_contents('activity_logs.json', json_encode($logs, JSON_PRETTY_PRINT));
}
?>