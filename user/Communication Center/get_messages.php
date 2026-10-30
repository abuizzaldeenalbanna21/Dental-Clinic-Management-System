<?php
$messages = json_decode(file_get_contents('messages.json'), true);

foreach ($messages as $msg) {
    $unreadClass = $msg['read'] ? '' : 'unread';
    echo '
    <a href="view_message.php?id='.$msg['id'].'" class="list-group-item list-group-item-action message-card '.$unreadClass.'"
       id="message-'.$msg['id'].'" onclick="markAsRead(''.$msg['id'].'')">
        <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">'.htmlspecialchars($msg['subject']).'</h6>
            <small>'.$msg['date'].'</small>
        </div>
        <p class="mb-1">'.substr(htmlspecialchars($msg['body']), 0, 50).'...</p>
    </a>';
}
?>