<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Admin Security" data-ar="الأمان الإداري"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .security-card { border-left: 3px solid #0d6efd; }
        .compliance-badge { font-size: 0.8rem; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Multi-Factor Authentication Section -->
        <div class="card mb-4">
            <div class="card-header" data-en="Multi-Factor Authentication" data-ar="المصادقة متعددة العوامل"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mfaSMS">
                            <label class="form-check-label" for="mfaSMS" 
                                   data-en="SMS Verification" data-ar="التحقق عبر الرسائل"></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mfaEmail">
                            <label class="form-check-label" for="mfaEmail" 
                                   data-en="Email Verification" data-ar="التحقق عبر البريد"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Management -->
        <div class="card mb-4">
            <div class="card-header" data-en="Session Settings" data-ar="إعدادات الجلسة"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-en="Auto Logout After" data-ar="تسجيل خروج تلقائي بعد"></label>
                        <select class="form-select" id="sessionTimeout">
                            <option value="15" data-en="15 minutes" data-ar="15 دقيقة"></option>
                            <option value="30" data-en="30 minutes" data-ar="30 دقيقة"></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ipTracking">
                            <label class="form-check-label" for="ipTracking" 
                                   data-en="Enable IP Tracking" data-ar="تفعيل تتبع العنوان"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Status -->
        <div class="card mb-4">
            <div class="card-header" data-en="Compliance Status" data-ar="حالة التوافق"></div>
            <div class="card-body">
                <div class="d-flex gap-3">
                    <span class="badge bg-success compliance-badge" 
                          data-en="GDPR Compliant" data-ar="متوافق مع GDPR"></span>
                    <span class="badge bg-info compliance-badge" 
                          data-en="HIPAA Ready" data-ar="جاهز لHIPAA"></span>
                </div>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="card">
            <div class="card-header" data-en="Activity Logs" data-ar="سجلات النشاط"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th data-en="Timestamp" data-ar="الوقت"></th>
                                <th data-en="Activity" data-ar="النشاط"></th>
                                <th data-en="IP Address" data-ar="العنوان"></th>
                            </tr>
                        </thead>
                        <tbody id="activityLogs">
                            <?php include('get_activity_logs.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Language Management
        let currentLang = localStorage.getItem('adminLang') || 'en';
        
        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            localStorage.setItem('adminLang', currentLang);
            applyLanguage();
        }

        function applyLanguage() {
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            document.querySelectorAll('[data-en], [data-ar]').forEach(el => {
                el.textContent = el.getAttribute(`data-${currentLang}`);
            });
        }

        // Session Timeout
        let inactivityTimer;
        function resetTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(logout, document.getElementById('sessionTimeout').value * 60000);
        }

        function logout() {
            window.location.href = 'logout.php';
        }

        document.addEventListener('mousemove', resetTimer);
        document.addEventListener('keypress', resetTimer);
        window.onload = () => {
            applyLanguage();
            resetTimer();
        };
    </script>
</body>
</html>