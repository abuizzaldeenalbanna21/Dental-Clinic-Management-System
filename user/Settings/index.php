<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Account Settings" data-ar="إعدادات الحساب"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .security-session { border-left: 3px solid #0d6efd; }
        .password-toggle { cursor: pointer; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Account Preferences -->
        <div class="card mb-4">
            <div class="card-header" data-en="Account Preferences" data-ar="تفضيلات الحساب"></div>
            <div class="card-body">
                <form id="accountForm" action="save_account.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" data-en="Full Name" data-ar="الاسم الكامل"></label>
                            <input type="text" class="form-control" name="fullname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" data-en="Email Address" data-ar="البريد الإلكتروني"></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" data-en="Password" data-ar="كلمة المرور"></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" required>
                                <span class="input-group-text password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" 
                                    data-en="Save Changes" data-ar="حفظ التغييرات"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="card mb-4">
            <div class="card-header" data-en="Notification Preferences" data-ar="إعدادات الإشعارات"></div>
            <div class="card-body">
                <form id="notificationForm">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="appointmentReminders">
                        <label class="form-check-label" for="appointmentReminders" 
                               data-en="Appointment Reminders" data-ar="تذكير بالمواعيد"></label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="paymentAlerts">
                        <label class="form-check-label" for="paymentAlerts" 
                               data-en="Payment Alerts" data-ar="تنبيهات الدفع"></label>
                    </div>
                    <button type="submit" class="btn btn-primary" 
                            data-en="Update Preferences" data-ar="تحديث التفضيلات"></button>
                </form>
            </div>
        </div>

        <!-- Privacy & Security -->
        <div class="card">
            <div class="card-header" data-en="Privacy & Security" data-ar="الخصوصية والأمان"></div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 data-en="Security Sessions" data-ar="الجلسات النشطة"></h5>
                    <div id="activeSessions">
                        <?php include('get_sessions.php'); ?>
                    </div>
                </div>
                
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="twoFactor">
                    <label class="form-check-label" for="twoFactor" 
                           data-en="Two-Factor Authentication" data-ar="المصادقة الثنائية"></label>
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

        // Password Toggle
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Load settings from JSON
        function loadSettings() {
            fetch('settings.json')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('[name="fullname"]').value = data.fullname;
                    document.querySelector('[name="email"]').value = data.email;
                    document.getElementById('appointmentReminders').checked = data.notifications.appointment;
                    document.getElementById('paymentAlerts').checked = data.notifications.payment;
                    document.getElementById('twoFactor').checked = data.twoFactor;
                });
        }

        window.onload = () => {
            applyLanguage();
            loadSettings();
        };
    </script>
</body>
</html>