<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Patient Dashboard" data-ar="لوحة تحكم المريض"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl {
            direction: rtl;
            text-align: right;
        }
        .notification-badge {
            position: relative;
            top: -2px;
            margin-left: 5px;
        }
        .quick-action-btn {
            transition: transform 0.2s;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
        }
        .scrollable-section {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 data-en="Welcome to the dental clinic!" data-ar="مرحبًا بك في عيادة الأسنان!" class="mb-0"></h2>
            <button onclick="toggleLanguage()" class="btn btn-secondary" 
                    data-en="عربي" data-ar="English"></button>
        </div>
    </header>

    <div class="container my-5">
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button class="btn btn-primary quick-action-btn" data-en="Book Appointment" data-ar="حجز موعد">
                        <i class="fas fa-calendar-plus"></i>
                    </button>
                    <button class="btn btn-success quick-action-btn" data-en="Medical History" data-ar="السجل الطبي">
                        <i class="fas fa-file-medical"></i>
                    </button>
                    <button class="btn btn-info quick-action-btn" data-en="Pay Online" data-ar="الدفع الإلكتروني">
                        <i class="fas fa-credit-card"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Upcoming Appointments -->
                <div class="card mb-4">
                    <div class="card-header" data-en="Upcoming Appointments" data-ar="المواعيد القادمة"></div>
                    <div class="card-body scrollable-section">
                        <?php include('get_appointments.php'); ?>
                    </div>
                </div>

                <!-- Treatment Plan -->
                <div class="card">
                    <div class="card-header" data-en="Treatment Plan Summary" data-ar="ملخص خطة العلاج"></div>
                    <div class="card-body">
                        <?php include('get_treatment_plan.php'); ?>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Notifications -->
                <div class="card">
                    <div class="card-header" data-en="Notifications" data-ar="الإشعارات">
                        <span class="badge bg-danger notification-badge">3</span>
                    </div>
                    <div class="card-body scrollable-section">
                        <ul class="list-group" id="notification-list">
                            <!-- Notifications loaded via JS -->
                        </ul>
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

        // Load notifications from JSON
        fetch('notifications.json')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notification-list');
                data.forEach(notification => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="${notification.icon} me-2"></i>
                                <span data-en="${notification.text_en}" data-ar="${notification.text_ar}"></span>
                            </div>
                            <small class="text-muted">${notification.date}</small>
                        </div>
                    `;
                    list.appendChild(li);
                });
                applyLanguage(); // Apply language after loading dynamic content
            });

        // Initialize language on load
        window.onload = applyLanguage;
    </script>
</body>
</html>