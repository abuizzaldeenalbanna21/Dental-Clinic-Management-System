<?php
$appointments = json_decode(file_get_contents('appointments.json'), true);

echo '<div class="list-group">';
foreach ($appointments as $appt) {
    echo '
    <a href="#" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1" data-en="'.$appt['title_en'].'" data-ar="'.$appt['title_ar'].'"></h5>
            <small>'.$appt['date'].'</small>
        </div>
        <p class="mb-1" data-en="'.$appt['description_en'].'" data-ar="'.$appt['description_ar'].'"></p>
        <small class="text-muted" data-en="Status: '.$appt['status_en'].'" data-ar="الحالة: '.$appt['status_ar'].'"></small>
    </a>';
}
echo '</div>';
?>