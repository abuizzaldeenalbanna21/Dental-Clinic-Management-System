<?php
$history = json_decode(file_get_contents('treatment_history.json'), true);

foreach ($history as $entry) {
    echo '
    <div class="row g-4 mb-4 treatment-card">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="progress-icon bg-primary text-white rounded-circle">
                            <i class="'.($entry['completed'] ? 'fas fa-check' : 'fas fa-tooth').'"></i>
                        </div>
                        <div>
                            <h5 data-en="'.$entry['title_en'].'" data-ar="'.$entry['title_ar'].'"></h5>
                            <small class="text-muted">'.$entry['date'].'</small>
                        </div>
                    </div>
                    <p class="mb-0" data-en="'.$entry['description_en'].'" data-ar="'.$entry['description_ar'].'"></p>
                </div>
            </div>
        </div>
    </div>';
}
?>