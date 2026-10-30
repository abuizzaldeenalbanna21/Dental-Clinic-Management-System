<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = json_decode(file_get_contents('settings.json'), true);
    
    $settings['fullname'] = htmlspecialchars($_POST['fullname']);
    $settings['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!empty($_POST['password'])) {
        $settings['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    file_put_contents('settings.json', json_encode($settings, JSON_PRETTY_PRINT));
    
    header('Location: settings.php');
    exit();
}
?>