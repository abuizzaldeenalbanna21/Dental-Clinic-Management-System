<?php
$rooms = json_decode(file_get_contents('rooms.json'), true);

foreach ($rooms as $room) {
    echo '
    <div class="col-md-4">
        <div class="card equipment-card">
            <div class="card-body">
                <h5>'.$room['name'].'</h5>
                <div class="sterilization-timer" data-last="'.$room['last_sterilized'].'">
                    <div class="progress sterilization-progress">
                        <div class="progress-bar bg-'.($room['status'] === 'due' ? 'warning' : 'success').'"></div>
                    </div>
                    <small class="timer-text"></small>
                </div>
                <div class="mt-2">
                    <span class="badge bg-primary">'.$room['equipment'].'</span>
                    <span class="badge bg-danger downtime-counter" 
                          data-start="'.$room['downtime_start'].'"></span>
                </div>
            </div>
        </div>
    </div>';
}
?>