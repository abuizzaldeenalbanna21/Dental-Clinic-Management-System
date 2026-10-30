<?php
$plans = json_decode(file_get_contents('treatment_plans.json'), true);

echo '<div class="list-group">';
foreach ($plans as $plan) {
    echo '
    <div class="list-group-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 data-en="'.$plan['title_en'].'" data-ar="'.$plan['title_ar'].'"></h6>
                <small data-en="'.$plan['status_en'].'" data-ar="'.$plan['status_ar'].'" 
                       class="badge bg-'.$plan['status_color'].'"></small>
            </div>
            <span class="text-muted">'.$plan['progress'].'%</span>
        </div>
        <div class="progress mt-2" style="height: 8px;">
            <div class="progress-bar bg-'.$plan['status_color'].'" 
                 role="progressbar" style="width: '.$plan['progress'].'%"></div>
        </div>
    </div>';
}
echo '</div>';
?>