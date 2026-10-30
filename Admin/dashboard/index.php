<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Clinic Dashboard" data-ar="لوحة التحكم"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .metric-card { border-left: 3px solid #0d6efd; transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-3px); }
        .emergency-alert { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100% { opacity:1 } 50% { opacity:0.5 } }
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 data-en="Clinic Dashboard" data-ar="لوحة التحكم"></h2>
            <button onclick="toggleLanguage()" class="btn btn-secondary" 
                    data-en="عربي" data-ar="English"></button>
        </div>
    </header>

    <div class="container my-5">
        <!-- Real-Time Metrics -->
        <div class="row g-4 mb-4">
            <!-- Appointments -->
            <div class="col-md-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 data-en="Appointments" data-ar="المواعيد"></h5>
                                <h2 class="text-primary"><?php include('get_appointments.php'); ?></h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-success" data-en="Confirmed" data-ar="مؤكد"></span>
                            <span class="badge bg-warning" data-en="Pending" data-ar="قيد الانتظار"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="col-md-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 data-en="Revenue" data-ar="الإيرادات"></h5>
                                <h2 class="text-success"><?php include('get_revenue.php'); ?></h2>
                            </div>
                            <i class="fas fa-coins fa-3x text-muted"></i>
                        </div>
                        <div class="mt-3">
                            <small data-en="Outstanding" data-ar="المستحقات"></small>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-danger" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Availability -->
            <div class="col-md-4">
                <div class="card metric-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 data-en="Staff On Duty" data-ar="الطاقم المتاح"></h5>
                                <h2 class="text-info"><?php include('get_staff.php'); ?></h2>
                            </div>
                            <i class="fas fa-user-md fa-3x text-muted"></i>
                        </div>
                        <div class="mt-3">
                            <small data-en="Available Dentists" data-ar="أطباء متاحين"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts & Notifications -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" data-en="Alerts & Notifications" data-ar="التنبيهات والإشعارات"></div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item emergency-alert bg-danger bg-opacity-10">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-exclamation-triangle text-danger"></i>
                                        <strong data-en="Emergency Appointment" data-ar="حالة طوارئ"></strong>
                                        <span data-en="Demo patient - Example appointment" data-ar="مريض تجريبي - موعد افتراضي"></span>
                                    </div>
                                    <small>10:30 AM</small>
                                </div>
                            </div>
                            <?php include('get_alerts.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" data-en="Quick Actions" data-ar="إجراءات سريعة"></div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary text-start" data-en="New Patient Profile" data-ar="مريض جديد">
                                <i class="fas fa-user-plus me-2"></i>
                            </button>
                            <button class="btn btn-success text-start" data-en="Generate Report" data-ar="إنشاء تقرير">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                            </button>
                            <button class="btn btn-warning text-start" data-en="Equipment Status" data-ar="حالة الأجهزة">
                                <i class="fas fa-tools me-2"></i>
                            </button>
                        </div>
                    </div>
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

        // Real-time Updates
        function updateMetrics() {
            fetch('get_live_data.php')
                .then(response => response.json())
                .then(data => {
                    // Update DOM elements with new data
                    document.getElementById('appointmentCount').textContent = data.appointments;
                    document.getElementById('revenueAmount').textContent = data.revenue;
                });
        }

        // Update every 60 seconds
        setInterval(updateMetrics, 60000);
        
        window.onload = applyLanguage;
    </script>
</body>
</html>