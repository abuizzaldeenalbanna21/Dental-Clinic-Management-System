<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'personal_info' => [
            'fullname' => htmlspecialchars($_POST['fullname']),
            'dob' => $_POST['dob'],
            // Add other personal fields
        ],
        'medical_history' => [
            'allergies' => htmlspecialchars($_POST['allergies']),
            // Add other medical fields
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Validate required fields
    $required = ['fullname', 'dob' /* add other required fields */];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            die("Required field missing: $field");
        }
    }

    // Save to JSON file
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('patients.json', $json, FILE_APPEND | LOCK_EX);

    // Redirect to success page
    header('Location: success.html');
    exit();
}
?>