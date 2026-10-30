<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $cardNumber = preg_replace('/\s+/', '', $_POST['card_number']);
    
    if (!$amount || $amount <= 0 || !preg_match('/^\d{16}$/', $cardNumber)) {
        die("Invalid payment details");
    }

    // Process payment (simulated)
    $paymentData = [
        'date' => date('Y-m-d H:i:s'),
        'amount' => $amount,
        'method' => 'Credit Card',
        'status' => 'Completed',
        'reference' => uniqid('PAY-')
    ];

    // Update balance
    $balance = json_decode(file_get_contents('balance.json'), true);
    $balance['amount'] -= $amount;
    file_put_contents('balance.json', json_encode($balance));

    // Save payment history
    $history = json_decode(file_get_contents('payment_history.json'), true);
    $history[] = $paymentData;
    file_put_contents('payment_history.json', json_encode($history));

    header('Location: billing.php');
    exit();
}
?>