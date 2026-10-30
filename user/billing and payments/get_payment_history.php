<?php
$history = json_decode(file_get_contents('payment_history.json'), true);

foreach ($history as $payment) {
    echo "<tr>
            <td>{$payment['date']}</td>
            <td>".number_format($payment['amount'], 2)." 
                <small data-en=\"JOD\" data-ar=\"دينار أردني\"></small></td>
            <td data-en=\"{$payment['method']}\" data-ar=\"{$payment['method_ar']}\"></td>
            <td><span class=\"badge bg-success\" data-en=\"{$payment['status']}\" 
                   data-ar=\"{$payment['status_ar']}\"></span></td>
          </tr>";
}
?>