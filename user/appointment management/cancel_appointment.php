<?php
session_start();
$id = $_GET['id'];

$appointments = json_decode(file_get_contents('appointments.json'), true);
foreach ($appointments as &$appt) {
    if ($appt['id'] === $id && $appt['patient_id'] === $_SESSION['user_id']) {
        $appt['status'] = 'Cancelled';
        $appt['history'][] = [
            'date' => date('Y-m-d H:i:s'),
            'action' => 'Cancelled by patient'
        ];
    }
}

file_put_contents('appointments.json', json_encode($appointments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'OK';
?>