<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Clinic Operations" data-ar="عمليات العيادة"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .sterilization-progress { height: 25px; }
        .equipment-card { transition: transform 0.2s; }
        .equipment-card:hover { transform: translateY(-3px); }
        .protocol-step { counter-increment: step-counter; }
        .protocol-step::before { content: counter(step-counter); }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Room Management -->
        <div class="card mb-4">
            <div class="card-header" data-en="Room Management" data-ar="إدارة الغرف"></div>
            <div class="card-body">
                <div class="row g-4">
                    <?php include('get_rooms.php'); ?>
                </div>
            </div>
        </div>

        <!-- Supplier Database -->
        <div class="card mb-4">
            <div class="card-header" data-en="Supplier Management" data-ar="إدارة الموردين"></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="Supplier" data-ar="المورد"></th>
                            <th data-en="Last Order" data-ar="آخر طلبية"></th>
                            <th data-en="Items" data-ar="المواد"></th>
                            <th data-en="Action" data-ar="إجراء"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include('get_suppliers.php'); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Emergency Protocols -->
        <div class="card">
            <div class="card-header" data-en="Emergency Protocols" data-ar="بروتوكولات الطوارئ"></div>
            <div class="card-body">
                <div class="accordion" id="protocolAccordion">
                    <?php include('get_protocols.php'); ?>
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
                const text = el.getAttribute(`data-${currentLang}`);
                if(el.placeholder) {
                    el.placeholder = text;
                } else {
                    el.textContent = text;
                }
            });
        }

        // Sterilization Timer
        function updateSterilizationTimers() {
            document.querySelectorAll('.sterilization-timer').forEach(timer => {
                const lastSterilized = new Date(timer.dataset.last);
                const minutesPassed = Math.floor((new Date() - lastSterilized) / 60000);
                const progress = (minutesPassed/120)*100;
                
                timer.querySelector('.progress-bar').style.width = `${progress}%`;
                timer.querySelector('.timer-text').textContent = 
                    currentLang === 'en' 
                    ? `${minutesPassed} minutes since last sterilization`
                    : `${minutesPassed} دقيقة منذ آخر تعقيم`;
            });
        }

        // Equipment Status Updates
        function updateEquipmentStatus() {
            document.querySelectorAll('.downtime-counter').forEach(counter => {
                const startTime = new Date(counter.dataset.start);
                const minutesDown = Math.floor((new Date() - startTime) / 60000);
                counter.textContent = `${minutesDown}m`;
            });
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            setInterval(() => {
                updateSterilizationTimers();
                updateEquipmentStatus();
            }, 60000);
            updateSterilizationTimers();
            updateEquipmentStatus();
        };
    </script>
</body>
</html>