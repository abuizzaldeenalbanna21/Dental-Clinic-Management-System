<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl {
            direction: rtl;
            text-align: right;
        }
        .required::after {
            content: "*";
            color: red;
            margin-left: 3px;
        }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3">عربي/English</button>

    <div class="container mt-5">
        <h1 data-en="Patient Registration" data-ar="تسجيل مريض جديد" class="text-center mb-4"></h1>
        
        <form id="registrationForm" action="register.php" method="POST" enctype="multipart/form-data">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header" data-en="Personal Information" data-ar="المعلومات الشخصية"></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required" data-en="Full Name" data-ar="الاسم الكامل"></label>
                            <input type="text" class="form-control" name="fullname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required" data-en="Date of Birth" data-ar="تاريخ الميلاد"></label>
                            <input type="date" class="form-control" name="dob" required>
                        </div>
                        <!-- Add more personal info fields -->
                    </div>
                </div>
            </div>

            <!-- Medical History -->
            <div class="card mb-4">
                <div class="card-header" data-en="Medical History" data-ar="التاريخ الطبي"></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" data-en="Do you have any allergies?" data-ar="هل لديك أي حساسية؟"></label>
                        <textarea class="form-control" name="allergies" rows="2"></textarea>
                    </div>
                    <!-- Add more medical history questions -->
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms" data-en="I agree to the Terms of Service" data-ar="أوافق على شروط الخدمة"></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="privacy" required>
                        <label class="form-check-label" for="privacy" data-en="I agree to the Privacy Policy" data-ar="أوافق على سياسة الخصوصية"></label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" data-en="Create Account" data-ar="إنشاء حساب"></button>
        </form>
    </div>

    <script>
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

        // Apply preferred language on page load
        window.onload = applyLanguage;
    </script>
</body>
</html>