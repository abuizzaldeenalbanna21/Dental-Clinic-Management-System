<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Staff Management" data-ar="إدارة الموظفين"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .license-alert { border-left: 3px solid #dc3545; }
        .shift-card { transition: all 0.2s; }
        .shift-card:hover { transform: translateY(-3px); }
        .permission-table td { vertical-align: middle; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Employee Directory -->
        <div class="card mb-4">
            <div class="card-header" data-en="Employee Directory" data-ar="دليل الموظفين"></div>
            <div class="card-body">
                <div class="row g-4" id="employeeDirectory">
                    <?php include('get_employees.php'); ?>
                </div>
            </div>
        </div>

        <!-- Shift Management -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header" data-en="Shift Scheduler" data-ar="جدول الورديات"></div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th data-en="Employee" data-ar="الموظف"></th>
                                    <th data-en="Mon" data-ar="الإثنين"></th>
                                    <th data-en="Tue" data-ar="الثلاثاء"></th>
                                    <!-- Add other days -->
                                </tr>
                            </thead>
                            <tbody id="shiftSchedule">
                                <?php include('get_shifts.php'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Overtime Tracking -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header" data-en="Overtime Tracking" data-ar="تتبع العمل الإضافي"></div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php include('get_overtime.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Permissions -->
        <div class="card mb-4">
            <div class="card-header" data-en="Role Permissions" data-ar="صلاحيات الأدوار"></div>
            <div class="card-body">
                <table class="table permission-table">
                    <thead>
                        <tr>
                            <th data-en="Role" data-ar="الدور"></th>
                            <th data-en="View Appointments" data-ar="عرض المواعيد"></th>
                            <th data-en="Edit Records" data-ar="تعديل السجلات"></th>
                            <!-- Add other permissions -->
                        </tr>
                    </thead>
                    <tbody id="rolePermissions">
                        <?php include('get_permissions.php'); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leave Management -->
        <div class="card">
            <div class="card-header" data-en="Leave Management" data-ar="إدارة الإجازات"></div>
            <div class="card-body">
                <div id="leaveCalendar"></div>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#requestLeaveModal"
                        data-en="Request Leave" data-ar="طلب إجازة"></button>
            </div>
        </div>
    </div>

    <!-- Leave Request Modal -->
    <div class="modal fade" id="requestLeaveModal">
        <!-- Modal content with form -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js"></script>
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

        // Initialize calendar
        const calendar = new FullCalendar.Calendar(document.getElementById('leaveCalendar'), {
            initialView: 'dayGridMonth',
            events: 'get_leaves.php'
        });
        calendar.render();

        // License expiration check
        function checkLicenses() {
            document.querySelectorAll('.license-date').forEach(dateElement => {
                const expiryDate = new Date(dateElement.dataset.expiry);
                const daysLeft = Math.floor((expiryDate - new Date()) / (1000*60*60*24));
                
                if(daysLeft < 30) {
                    dateElement.closest('.employee-card').classList.add('license-alert');
                }
            });
        }

        window.onload = () => {
            applyLanguage();
            checkLicenses();
        };
    </script>
</body>
</html>