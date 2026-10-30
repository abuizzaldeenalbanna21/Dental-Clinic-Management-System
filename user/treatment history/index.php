<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Treatment History" data-ar="السجل العلاجي"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .timeline { position: relative; }
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            width: 2px;
            height: 100%;
            background: #dee2e6;
        }
        .progress-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .treatment-card { transition: transform 0.2s; }
        .treatment-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Treatment Timeline -->
        <div class="card mb-4">
            <div class="card-header" data-en="Treatment History" data-ar="السجل العلاجي"></div>
            <div class="card-body">
                <div class="timeline">
                    <?php include('get_treatment_history.php'); ?>
                </div>
            </div>
        </div>

        <!-- Current Treatment Plans -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header" data-en="Active Treatment Plans" data-ar="خطط العلاج النشطة"></div>
                    <div class="card-body">
                        <div id="activePlans">
                            <?php include('get_active_plans.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Tracking -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header" data-en="Treatment Progress" data-ar="تقدم العلاج"></div>
                    <div class="card-body">
                        <div id="progressTracking">
                            <?php include('get_progress.php'); ?>
                        </div>
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

        // Initialize language
        window.onload = applyLanguage;
    </script>
</body>
</html>