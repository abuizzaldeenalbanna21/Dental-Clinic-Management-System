<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Communication Center" data-ar="مركز الاتصالات"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .message-card { transition: all 0.2s; }
        .message-card:hover { transform: translateX(5px); }
        .unread { background-color: #e3f2fd; }
        .scrollable-section { max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <div class="row g-4">
            <!-- Messages Section -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header" data-en="Secure Messaging" data-ar="المراسلة الآمنة"></div>
                    <div class="card-body scrollable-section">
                        <div class="list-group" id="messagesList">
                            <?php include('get_messages.php'); ?>
                        </div>
                    </div>
                </div>

                <!-- New Message Form -->
                <div class="card">
                    <div class="card-header" data-en="Compose New Message" data-ar="رسالة جديدة"></div>
                    <div class="card-body">
                        <form id="messageForm" action="send_message.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= bin2hex(random_bytes(32)) ?>">
                            
                            <div class="mb-3">
                                <label class="form-label" data-en="Subject" data-ar="الموضوع"></label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" data-en="Message" data-ar="الرسالة"></label>
                                <textarea class="form-control" name="body" rows="4" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" 
                                    data-en="Send Message" data-ar="إرسال الرسالة"></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Requests -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header" data-en="Prescription Refills" data-ar="تجديد الوصفات"></div>
                    <div class="card-body">
                        <form id="refillForm" action="request_refill.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label" data-en="Medication" data-ar="الدواء"></label>
                                <select class="form-select" name="medication" required>
                                    <?php include('get_medications.php'); ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" data-en="Quantity" data-ar="الكمية"></label>
                                <input type="number" class="form-control" name="quantity" min="1" required>
                            </div>
                            
                            <button type="submit" class="btn btn-warning w-100" 
                                    data-en="Request Refill" data-ar="طلب تجديد"></button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" data-en="Treatment Questions" data-ar="أسئلة العلاج"></div>
                    <div class="card-body">
                        <form id="questionForm" action="ask_question.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label" data-en="Related Treatment" data-ar="العلاج المرتبط"></label>
                                <select class="form-select" name="treatment" required>
                                    <?php include('get_treatments.php'); ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" data-en="Question" data-ar="السؤال"></label>
                                <textarea class="form-control" name="question" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-info w-100" 
                                    data-en="Ask Question" data-ar="طرح سؤال"></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Language Management
        let currentLang = localStorage.getItem('preferredLang') || 'en';
        
        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            localStorage.setItem('preferredLang', currentLang);
            applyLanguage();
        }

        function applyLanguage() {
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            
            document.querySelectorAll('[data-en], [data-ar]').forEach(element => {
                const text = element.getAttribute(`data-${currentLang}`);
                if (text) element.textContent = text;
            });
        }

        // Mark message as read
        function markAsRead(id) {
            fetch(`mark_read.php?id=${id}`)
                .then(response => document.getElementById(`message-${id}`).classList.remove('unread'));
        }

        // Initialize language
        window.onload = applyLanguage;
    </script>
</body>
</html>