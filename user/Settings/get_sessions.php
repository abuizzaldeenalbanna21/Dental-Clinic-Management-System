<?php
$sessions = json_decode(file_get_contents('sessions.json'), true);

foreach ($sessions as $session) {
    echo '
    <div class="security-session mb-3 p-3">
        <div class="d-flex justify-content-between">
            <div>
                <strong>'.$session['browser'].'</strong>
                <div class="text-muted">'.$session['ip'].'</div>
                <small>'.date('M d, Y H:i', strtotime($session['last_activity'])).'</small>
            </div>
            <button class="btn btn-sm btn-outline-danger" 
                    data-en="Revoke" data-ar="إلغاء"></button>
        </div>
    </div>';
}
?>