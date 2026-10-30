<?php
$documents = json_decode(file_get_contents('documents.json'), true);

foreach ($documents as $doc) {
    echo '
    <div class="col-md-4 col-lg-3 document-card" data-category="'.$doc['category'].'">
        <div class="card h-100">
            <span class="badge bg-primary category-badge" 
                  data-en="'.$doc['category_en'].'" 
                  data-ar="'.$doc['category_ar'].'"></span>
            <img src="'.$doc['thumbnail'].'" 
                 class="card-img-top preview-image" 
                 onclick="previewDocument(\''.$doc['file'].'\', 
                         \''.($currentLang === 'en' ? $doc['title_en'] : $doc['title_ar']).'\', 
                         \''.$doc['type'].'\')">
            <div class="card-body">
                <h6 class="card-title">'.($currentLang === 'en' ? $doc['title_en'] : $doc['title_ar']).'</h6>
                <small class="text-muted">'.$doc['date'].'</small>
                <a href="download.php?file='.urlencode($doc['file']).'" 
                   class="btn btn-sm btn-outline-primary w-100 mt-2"
                   data-en="Download" data-ar="تحميل"></a>
            </div>
        </div>
    </div>';
}
?>