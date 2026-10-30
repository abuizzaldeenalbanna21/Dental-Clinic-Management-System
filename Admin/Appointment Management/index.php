<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Appointment Management" data-ar="إدارة المواعيد"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .fc-event { cursor: move; }
        .dentist-color { width: 20px; height: 20px; border-radius: 50%; }
        .waitlist-item { transition: all 0.2s; }
        .waitlist-item:hover { transform: translateX(5px); }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Calendar & Waitlist -->
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header" data-en="Appointment Calendar" data-ar="تقويم المواعيد"></div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Waitlist & Quick Stats -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header" data-en="Waitlist" data-ar="قائمة الانتظار"></div>
                    <div class="card-body">
                        <div class="list-group" id="waitlist">
                            <?php include('get_waitlist.php'); ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" data-en="Statistics" data-ar="الإحصائيات"></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small data-en="No-Show Rate" data-ar="معدل الغياب"></small>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-danger" style="width: 15%"></div>
                            </div>
                        </div>
                        <div>
                            <small data-en="Avg Patients/Hour" data-ar="متوسط المرضى/ساعة"></small>
                            <h4>3.2</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reminder Controls -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div data-en="Send Reminders (24h)" data-ar="إرسال التذكيرات (24 ساعة)"></div>
                    <button class="btn btn-primary" onclick="sendReminders()"
                            data-en="Send Now" data-ar="إرسال الآن"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.js"></script>

    <script>
        // Language Management
        let currentLang = localStorage.getItem('adminLang') || 'en';
        
        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            localStorage.setItem('adminLang', currentLang);
            applyLanguage();
            calendar.refetchEvents();
        }

        function applyLanguage() {
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            document.querySelectorAll('[data-en], [data-ar]').forEach(el => {
                el.textContent = el.getAttribute(`data-${currentLang}`);
            });
        }

        // Calendar Configuration
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            eventDrop: async (info) => {
                await updateAppointmentTime(info.event.id, info.event.start);
            },
            events: async () => {
                const response = await fetch('get_appointments.php');
                return await response.json();
            },
            eventContent: (arg) => ({
                html: `<div class="d-flex align-items-center gap-2">
                         <div class="dentist-color" style="background:${arg.event.extendedProps.color}"></div>
                         ${arg.event.title}
                       </div>`
            })
        });
        calendar.render();

        // Send Reminders
        async function sendReminders() {
            const response = await fetch('send_reminders.php');
            alert(currentLang === 'en' ? 'Reminders sent!' : 'تم إرسال التذكيرات!');
        }

        // Initialize
        window.onload = applyLanguage;
    </script>
</body>
</html>