<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $appointment = [
        'id' => uniqid(),
        'patient_id' => $_SESSION['user_id'],
        'date' => htmlspecialchars($_POST['date']),
        'time' => htmlspecialchars($_POST['time']),
        'dentist' => htmlspecialchars($_POST['dentist']),
        'reason' => htmlspecialchars($_POST['reason']),
        'status' => 'Scheduled',
        'history' => []
    ];

    // Save to JSON
    $appointments = json_decode(file_get_contents('appointments.json'), true);
    $appointments[] = $appointment;
    file_put_contents('appointments.json', json_encode($appointments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: appointments.php');
    exit();
}
?>