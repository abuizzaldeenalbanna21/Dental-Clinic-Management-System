<?php
session_start();

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Validate CSRF token
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid CSRF token");
}

// Input validation
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = $_POST['password'];
$remember_me = isset($_POST['remember_me']);

// Read users from JSON (example - replace with database in production)
$users = json_decode(file_get_contents('users.json'), true);

foreach ($users as $user) {
    if (($user['email'] === $username || $user['username'] === $username) &&
        password_verify($password, $user['password_hash'])) {
        
        // Regenerate session ID
        session_regenerate_id(true);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

        // Remember me functionality (secure implementation)
        if ($remember_me) {
            $token = bin2hex(random_bytes(32));
            $expiry = time() + 60 * 60 * 24 * 30; // 30 days
            
            setcookie('remember_token', $token, [
                'expires' => $expiry,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            // Store token in database (pseudo-code)
            // update_user_token($user['id'], hash('sha256', $token));
        }

        header("Location: dashboard.php");
        exit();
    }
}

// If authentication fails
die("Invalid credentials");
?>