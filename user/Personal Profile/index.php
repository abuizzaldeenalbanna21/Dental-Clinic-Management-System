<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Patient Profile" data-ar="الملف الشخصي للمريض"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .medical-history-item { transition: all 0.3s; }
        .medical-history-item:hover { transform: translateX(5px); }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Personal Information Section -->
        <div class="card mb-4">
            <div class="card-header" data-en="Personal Information" data-ar="المعلومات الشخصية"></div>
            <div class="card-body">
                <form id="personalInfoForm" action="save_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3 align-items-center">
                        <!-- Profile Picture -->
                        <div class="col-md-3 text-center">
                            <img src="default-avatar.jpg" class="profile-pic rounded-circle mb-3" id="profilePreview">
                            <input type="file" class="form-control" name="avatar" accept="image/*" 
                                   onchange="previewImage(event)">
                        </div>

                        <!-- Personal Details -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" data-en="Full Name" data-ar="الاسم الكامل"></label>
                                    <input type="text" class="form-control" name="fullname" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" data-en="Date of Birth" data-ar="تاريخ الميلاد"></label>
                                    <input type="date" class="form-control" name="dob" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" data-en="Gender" data-ar="الجنس"></label>
                                    <select class="form-select" name="gender" required>
                                        <option value="male" data-en="Male" data-ar="ذكر"></option>
                                        <option value="female" data-en="Female" data-ar="أنثى"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header" data-en="Contact Details" data-ar="تفاصيل الاتصال"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" data-en="Email Address" data-ar="البريد الإلكتروني"></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" data-en="Phone Number" data-ar="رقم الهاتف"></label>
                        <input type="tel" class="form-control" name="phone" pattern="[0-9]{10}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Address" data-ar="العنوان"></label>
                        <textarea class="form-control" name="address" rows="2" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="card">
            <div class="card-header" data-en="Medical History" data-ar="التاريخ الطبي"></div>
            <div class="card-body">
                <form id="medicalHistoryForm">
                    <div class="mb-4">
                        <h5 data-en="Medical Conditions" data-ar="الحالات الطبية"></h5>
                        <div class="row" id="conditionsList">
                            <!-- Conditions loaded from JSON -->
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 data-en="Allergies" data-ar="الحساسيات"></h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="allergyInput" 
                                   placeholder="Add allergy" data-en-placeholder="Add allergy" 
                                   data-ar-placeholder="إضافة حساسية">
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="addAllergy()" data-en="Add" data-ar="إضافة"></button>
                        </div>
                        <div id="allergiesList" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" 
                            data-en="Save Changes" data-ar="حفظ التغييرات"></button>
                </form>
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
                if (text) {
                    if (element.placeholder) {
                        element.placeholder = text;
                    } else {
                        element.textContent = text;
                    }
                }
            });
        }

        // Image Preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profilePreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Medical History Management
        function loadMedicalConditions() {
            fetch('medical_conditions.json')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('conditionsList');
                    data.forEach(condition => {
                        container.innerHTML += `
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="${condition.id}" name="conditions[]">
                                    <label class="form-check-label" for="${condition.id}"
                                           data-en="${condition.name_en}" 
                                           data-ar="${condition.name_ar}"></label>
                                </div>
                            </div>
                        `;
                    });
                    applyLanguage();
                });
        }

        function addAllergy() {
            const input = document.getElementById('allergyInput');
            if (input.value.trim()) {
                const allergy = document.createElement('div');
                allergy.className = 'badge bg-warning text-dark p-2';
                allergy.innerHTML = `
                    ${input.value}
                    <button class="ms-2 btn-close" onclick="this.parentElement.remove()"></button>
                `;
                document.getElementById('allergiesList').appendChild(allergy);
                input.value = '';
            }
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            loadMedicalConditions();
        };
    </script>
</body>
</html>