<?php
$documents = json_decode(file_get_contents('documents.json'), true);

foreach ($documents as $doc) {
    echo '
    <div class="col-md-4">
        <div class="card document-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-'.$doc['icon'].' me-2"></i>
                        <strong>'.$doc['name'].'</strong>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-download me-2"></i>
                                <span data-en="Download" data-ar="تحميل"></span>
                            </a></li>
                        </ul>
                    </div>
                </div>
                <small class="text-muted">'.$doc['date'].'</small>
            </div>
        </div>
    </div>';
}
?>