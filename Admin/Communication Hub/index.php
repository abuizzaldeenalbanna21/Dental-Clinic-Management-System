<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Communication Hub" data-ar="مركز الاتصالات"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .chat-window { height: 400px; overflow-y: auto; }
        .message-bubble { max-width: 80%; border-radius: 15px; }
        .call-log-entry { transition: all 0.2s; }
        .call-log-entry:hover { transform: translateX(5px); }
        .template-response { cursor: pointer; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Secure Messaging -->
        <div class="card mb-4">
            <div class="card-header" data-en="Secure Messaging" data-ar="المراسلة الآمنة"></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="list-group">
                            <button class="list-group-item list-group-item-action active" 
                                    data-en="New Message" data-ar="رسالة جديدة"></button>
                            <?php include('get_message_threads.php'); ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="chat-window mb-3" id="chatMessages"></div>
                        <div class="input-group">
                            <select class="form-select w-25" id="templateSelect">
                                <?php include('get_templates.php'); ?>
                            </select>
                            <textarea class="form-control" id="messageInput" 
                                      data-en-placeholder="Type your message..." 
                                      data-ar-placeholder="اكتب رسالتك..."></textarea>
                            <button class="btn btn-primary" onclick="sendMessage()"
                                    data-en="Send" data-ar="إرسال"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Broadcast Tools -->
        <div class="card mb-4">
            <div class="card-header" data-en="Broadcast Tools" data-ar="أدوات البث"></div>
            <div class="card-body">
                <form id="broadcastForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="broadcastType">
                                <option value="sms" data-en="SMS" data-ar="رسالة نصية"></option>
                                <option value="email" data-en="Email" data-ar="بريد إلكتروني"></option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" 
                                   data-en-placeholder="Enter message subject..." 
                                   data-ar-placeholder="أدخل عنوان الرسالة...">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" rows="4" 
                                      data-en-placeholder="Compose your broadcast message..." 
                                      data-ar-placeholder="اكتب رسالة البث..."></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" 
                                    data-en="Send Broadcast" data-ar="إرسال البث"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Call Logs -->
        <div class="card">
            <div class="card-header" data-en="Call Logs" data-ar="سجلات المكالمات"></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="Date/Time" data-ar="التاريخ/الوقت"></th>
                            <th data-en="Duration" data-ar="المدة"></th>
                            <th data-en="Purpose" data-ar="الغرض"></th>
                            <th data-en="Outcome" data-ar="النتيجة"></th>
                        </tr>
                    </thead>
                    <tbody id="callLogs">
                        <?php include('get_call_logs.php'); ?>
                    </tbody>
                </table>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logCallModal"
                        data-en="Log New Call" data-ar="تسجيل مكالمة جديدة"></button>
            </div>
        </div>
    </div>

    <!-- Log Call Modal -->
    <div class="modal fade" id="logCallModal">
        <!-- Modal content loaded dynamically -->
    </div>

    <script>
        // Language Management
        let currentLang = localStorage.getItem('adminLang') || 'en';
        
        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            localStorage.setItem('adminLang', currentLang);
            applyLanguage();
            loadMessages();
        }

        function applyLanguage() {
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            document.querySelectorAll('[data-en], [data-ar]').forEach(el => {
                const text = el.getAttribute(`data-${currentLang}`);
                if(el.placeholder) {
                    el.placeholder = text;
                } else {
                    el.textContent = text;
                }
            });
        }

        // Message Handling
        async function sendMessage() {
            const message = document.getElementById('messageInput').value;
            // Implement encryption and send to server
            console.log('Encrypted message:', btoa(message));
            document.getElementById('messageInput').value = '';
        }

        // Load messages every 5 seconds
        function loadMessages() {
            fetch('get_messages.php')
                .then(response => response.json())
                .then(messages => {
                    const container = document.getElementById('chatMessages');
                    container.innerHTML = messages.map(msg => `
                        <div class="message-bubble ${msg.sender === 'admin' ? 'bg-primary text-white ms-auto' : 'bg-light'} 
                            p-3 mb-2">
                            <div class="fw-bold">${msg.sender}</div>
                            <div>${atob(msg.content)}</div>
                            <small class="text-muted">${msg.timestamp}</small>
                        </div>
                    `).join('');
                });
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            setInterval(loadMessages, 5000);
        };
    </script>
</body>
</html>