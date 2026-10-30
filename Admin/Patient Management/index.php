<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Patient Management" data-ar="إدارة المرضى"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .search-suggestions { position: absolute; z-index: 1000; width: 100%; }
        .patient-card { transition: transform 0.2s; }
        .patient-card:hover { transform: translateY(-3px); }
        .signature-pad { border: 1px solid #ddd; background: white; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Patient Search Interface -->
        <div class="card mb-4">
            <div class="card-header" data-en="Patient Search" data-ar="بحث المرضى"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8 position-relative">
                        <input type="text" class="form-control" id="searchInput" 
                               data-en-placeholder="Search patients..." 
                               data-ar-placeholder="ابحث عن المرضى..."
                               onkeyup="showSuggestions(this.value)">
                        <div class="search-suggestions list-group d-none" id="suggestions"></div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" onclick="searchPatients()"
                                data-en="Search" data-ar="بحث"></button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="insuranceStatus">
                            <option value="all" data-en="All Insurance" data-ar="كل التأمينات"></option>
                            <option value="active" data-en="Active" data-ar="نشط"></option>
                        </select>
                    </div>
                    <div class="col-md-2 form-check">
                        <input type="checkbox" class="form-check-input" id="outstandingBalance">
                        <label class="form-check-label" for="outstandingBalance"
                               data-en="Has Balance" data-ar="مدين"></label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span data-en="Search Results" data-ar="نتائج البحث"></span>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary" onclick="exportCSV()"
                            data-en="Export CSV" data-ar="تصدير CSV"></button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="exportPDF()"
                            data-en="Export PDF" data-ar="تصدير PDF"></button>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="Name" data-ar="الاسم" onclick="sortColumn('name')"></th>
                            <th data-en="ID" data-ar="الرقم" onclick="sortColumn('id')"></th>
                            <th data-en="Last Visit" data-ar="آخر زيارة" onclick="sortColumn('lastVisit')"></th>
                            <th data-en="Next Appointment" data-ar="الموعد القادم" onclick="sortColumn('nextAppointment')"></th>
                            <th data-en="Actions" data-ar="إجراءات"></th>
                        </tr>
                    </thead>
                    <tbody id="searchResults"></tbody>
                </table>
            </div>
        </div>

        <!-- Patient Profile Modal -->
        <div class="modal fade" id="patientModal">
            <!-- Modal content loaded dynamically -->
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
                if (el.placeholder) {
                    el.placeholder = text;
                } else {
                    el.textContent = text;
                }
            });
        }

        // Patient Search Functions
        async function showSuggestions(query) {
            const response = await fetch(`search_patients.php?q=${query}`);
            const suggestions = await response.json();
            
            const suggestionsDiv = document.getElementById('suggestions');
            suggestionsDiv.innerHTML = suggestions.map(p => `
                <a class="list-group-item list-group-item-action" 
                   onclick="selectPatient('${p.id}')">
                   ${p.name} (${p.id})
                </a>
            `).join('');
            suggestionsDiv.classList.remove('d-none');
        }

        async function loadPatientProfile(id) {
            const response = await fetch(`get_patient.php?id=${id}`);
            const patient = await response.json();
            
            // Populate modal with patient data
            const modalContent = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>${patient.name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs for different sections -->
                        <div class="row">
                            <div class="col-md-4">
                                <h6 data-en="Personal Details" data-ar="تفاصيل شخصية"></h6>
                                <form id="patientForm">
                                    <!-- Editable fields -->
                                </form>
                            </div>
                            <div class="col-md-8">
                                <h6 data-en="Medical History" data-ar="التاريخ الطبي"></h6>
                                <div class="signature-pad" id="signaturePad"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('patientModal').innerHTML = modalContent;
            new bootstrap.Modal(document.getElementById('patientModal')).show();
        }

        // Initialize
        window.onload = applyLanguage;
    </script>
</body>
</html>