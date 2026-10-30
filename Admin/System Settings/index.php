<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="System Settings" data-ar="إعدادات النظام"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .config-card { border-left: 3px solid #0d6efd; }
        .holiday-list { max-height: 300px; overflow-y: auto; }
        .api-status { width: 15px; height: 15px; border-radius: 50%; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Clinic Configuration -->
        <div class="card mb-4 config-card">
            <div class="card-header" data-en="Clinic Configuration" data-ar="إعدادات العيادة"></div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Multi-Branch Management -->
                    <div class="col-md-6">
                        <h5 data-en="Branch Locations" data-ar="الفروع"></h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th data-en="Branch Name" data-ar="اسم الفرع"></th>
                                        <th data-en="Actions" data-ar="الإجراءات"></th>
                                    </tr>
                                </thead>
                                <tbody id="branchList">
                                    <?php include('get_branches.php'); ?>
                                </tbody>
                            </table>
                            <button class="btn btn-sm btn-primary" onclick="showBranchModal()"
                                    data-en="Add Branch" data-ar="إضافة فرع"></button>
                        </div>
                    </div>

                    <!-- Holiday Calendar -->
                    <div class="col-md-6">
                        <h5 data-en="Holiday Calendar" data-ar="إجازات العيادة"></h5>
                        <div class="holiday-list">
                            <?php include('get_holidays.php'); ?>
                        </div>
                        <div class="input-group mt-3">
                            <input type="date" class="form-control" id="newHoliday">
                            <button class="btn btn-primary" onclick="addHoliday()"
                                    data-en="Add Holiday" data-ar="إضافة إجازة"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Localization Settings -->
        <div class="card mb-4 config-card">
            <div class="card-header" data-en="Localization" data-ar="الموقعية"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" data-en="Date Format" data-ar="تنسيق التاريخ"></label>
                        <select class="form-select" id="dateFormat">
                            <option value="dd/mm/yyyy" data-en="DD/MM/YYYY" data-ar="يوم/شهر/سنة"></option>
                            <option value="mm/dd/yyyy" data-en="MM/DD/YYYY" data-ar="شهر/يوم/سنة"></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-en="Time Format" data-ar="تنسيق الوقت"></label>
                        <select class="form-select" id="timeFormat">
                            <option value="12h" data-en="12-hour" data-ar="12 ساعة"></option>
                            <option value="24h" data-en="24-hour" data-ar="24 ساعة"></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" data-en="Currency" data-ar="العملة"></label>
                        <select class="form-select" id="currency">
                            <option value="AED" data-en="AED" data-ar="درهم"></option>
                            <option value="SAR" data-en="SAR" data-ar="ريال"></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Integrations -->
        <div class="card config-card">
            <div class="card-header" data-en="API Integrations" data-ar="التكاملات البرمجية"></div>
            <div class="card-body">
                <div class="row g-4">
                    <?php include('get_apis.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Modal -->
    <div class="modal fade" id="branchModal">
        <!-- Modal content loaded dynamically -->
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
                if(text) el.textContent = text;
            });
        }

        // Branch Management
        async function showBranchModal() {
            const response = await fetch('branch_form.php');
            document.getElementById('branchModal').innerHTML = await response.text();
            new bootstrap.Modal(document.getElementById('branchModal')).show();
        }

        // Holiday Management
        async function addHoliday() {
            const date = document.getElementById('newHoliday').value;
            // Add to JSON and refresh list
            console.log('Adding holiday:', date);
        }

        // Save Settings
        function saveSettings() {
            const settings = {
                dateFormat: document.getElementById('dateFormat').value,
                timeFormat: document.getElementById('timeFormat').value,
                currency: document.getElementById('currency').value
            };
            localStorage.setItem('clinicSettings', JSON.stringify(settings));
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            const savedSettings = JSON.parse(localStorage.getItem('clinicSettings') || '{}');
            if(savedSettings.dateFormat) document.getElementById('dateFormat').value = savedSettings.dateFormat;
            if(savedSettings.timeFormat) document.getElementById('timeFormat').value = savedSettings.timeFormat;
            if(savedSettings.currency) document.getElementById('currency').value = savedSettings.currency;
        };
    </script>
</body>
</html>