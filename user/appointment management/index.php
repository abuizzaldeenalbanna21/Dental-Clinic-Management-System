<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Appointment Management" data-ar="إدارة المواعيد"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .appointment-card { transition: transform 0.2s; }
        .appointment-card:hover { transform: translateY(-3px); }
        .history-table { max-height: 400px; overflow-y: auto; }
        .dentist-select { min-width: 200px; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- New Appointment Section -->
        <div class="card mb-4">
            <div class="card-header" data-en="Schedule New Appointment" data-ar="حجز موعد جديد"></div>
            <div class="card-body">
                <form id="appointmentForm" action="save_appointment.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" data-en="Date" data-ar="التاريخ"></label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" data-en="Time" data-ar="الوقت"></label>
                            <input type="time" class="form-control" name="time" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" data-en="Dentist" data-ar="الطبيب"></label>
                            <select class="form-select dentist-select" name="dentist" required>
                                <?php include('get_dentists.php'); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" data-en="Reason" data-ar="السبب"></label>
                            <select class="form-select" name="reason" required>
                                <?php include('get_reasons.php'); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" 
                                    data-en="Schedule Appointment" data-ar="حجز الموعد"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="row">
            <!-- Upcoming Appointments -->
            <div class="col-lg-6">
                <h3 data-en="Upcoming Appointments" data-ar="المواعيد القادمة"></h3>
                <div id="upcomingAppointments" class="row g-3">
                    <?php include('get_upcoming_appointments.php'); ?>
                </div>
            </div>

            <!-- Appointment History -->
            <div class="col-lg-6">
                <h3 data-en="Appointment History" data-ar="سجل المواعيد"></h3>
                <div class="history-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th data-en="Date" data-ar="التاريخ"></th>
                                <th data-en="Status" data-ar="الحالة"></th>
                                <th data-en="Actions" data-ar="الإجراءات"></th>
                            </tr>
                        </thead>
                        <tbody id="appointmentHistory">
                            <?php include('get_appointment_history.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal">
        <!-- Modal content loaded dynamically -->
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

        // Handle Appointment Actions
        function cancelAppointment(id) {
            if (confirm(currentLang === 'en' ? 'Confirm cancellation?' : 'تأكيد الإلغاء؟')) {
                fetch(`cancel_appointment.php?id=${id}`)
                    .then(response => location.reload());
            }
        }

        function loadRescheduleModal(id) {
            fetch(`get_appointment.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('rescheduleModal').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('rescheduleModal')).show();
                });
        }

        // Initialize language
        window.onload = applyLanguage;
    </script>
</body>
</html>