<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Dental Clinic Login" data-ar="تسجيل الدخول للعيادة"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl {
            direction: rtl;
            text-align: right;
        }
        body {
            background: #f0f8ff;
            min-height: 100vh;
        }
        .login-card {
            max-width: 500px;
            margin: 100px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="login-card">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4" 
                    data-en="Patient Login" 
                    data-ar="تسجيل دخول المريض"></h2>
                
                <form id="loginForm" action="login.php" method="POST">
                    <!-- CSRF Token (simplified example) -->
                    <input type="hidden" name="csrf_token" value="<?= bin2hex(random_bytes(32)) ?>">

                    <div class="mb-3">
                        <label class="form-label" 
                               data-en="Email or Username" 
                               data-ar="البريد الإلكتروني أو اسم المستخدم"></label>
                        <input type="text" 
                               class="form-control" 
                               name="username" 
                               required
                               autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" 
                               data-en="Password" 
                               data-ar="كلمة المرور"></label>
                        <input type="password" 
                               class="form-control" 
                               name="password" 
                               required
                               autocomplete="current-password">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="rememberMe"
                               name="remember_me">
                        <label class="form-check-label" 
                               for="rememberMe"
                               data-en="Remember Me" 
                               data-ar="تذكرني"></label>
                    </div>

                    <button type="submit" 
                            class="btn btn-primary w-100"
                            data-en="Sign In" 
                            data-ar="تسجيل الدخول"></button>

                    <div class="text-center mt-3">
                        <a href="reset-password.php" 
                           data-en="Forgot Password?" 
                           data-ar="نسيت كلمة المرور؟"></a>
                    </div>
                </form>
            </div>
        </div>
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