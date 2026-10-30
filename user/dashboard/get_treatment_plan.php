<?php
$treatment = json_decode(file_get_contents('treatment.json'), true);

echo '<ul class="list-group">';
foreach ($treatment as $item) {
    echo '
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span data-en="'.$item['title_en'].'" data-ar="'.$item['title_ar'].'"></span>
        <span class="badge bg-'.$item['status_color'].' rounded-pill">
            <span data-en="'.$item['status_en'].'" data-ar="'.$item['status_ar'].'"></span>
        </span>
    </li>';
}
echo '</ul>';
?>