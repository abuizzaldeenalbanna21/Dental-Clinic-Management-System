<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $message = [
        'id' => uniqid(),
        'patient_id' => $_SESSION['user_id'],
        'subject' => htmlspecialchars($_POST['subject']),
        'body' => htmlspecialchars($_POST['body']),
        'date' => date('Y-m-d H:i:s'),
        'read' => false,
        'type' => 'outgoing'
    ];

    // Save message
    $messages = json_decode(file_get_contents('messages.json'), true);
    $messages[] = $message;
    file_put_contents('messages.json', json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: communication.php');
    exit();
}
?>