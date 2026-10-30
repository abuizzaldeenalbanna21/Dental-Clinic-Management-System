<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process file upload
    $avatarPath = 'uploads/';
    if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath . $filename);
    }

    // Save profile data
    $profileData = [
        'user_id' => $_SESSION['user_id'],
        'personal_info' => [
            'fullname' => htmlspecialchars($_POST['fullname']),
            'dob' => htmlspecialchars($_POST['dob']),
            'gender' => htmlspecialchars($_POST['gender']),
            'avatar' => $filename ?? null
        ],
        'contact_info' => [
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'phone' => htmlspecialchars($_POST['phone']),
            'address' => htmlspecialchars($_POST['address'])
        ],
        'medical_history' => [
            'conditions' => $_POST['conditions'] ?? [],
            'allergies' => json_decode($_POST['allergies'], true) ?? []
        ]
    ];

    // Save to JSON
    file_put_contents("profiles/{$_SESSION['user_id']}.json", 
                     json_encode($profileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: profile.php');
    exit();
}
?>