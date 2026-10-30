<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Clinic Analytics" data-ar="التحليلات الإحصائية"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .rtl { direction: rtl; text-align: right; }
        .report-card { transition: transform 0.2s; }
        .report-card:hover { transform: translateY(-3px); }
        .chart-container { position: relative; height: 400px; }
        .export-dropdown { min-width: 150px; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Report Selection -->
        <div class="card mb-4">
            <div class="card-header" data-en="Generate Reports" data-ar="إنشاء التقارير"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select class="form-select" id="reportType" onchange="loadReport()">
                            <option value="financial" data-en="Financial Report" data-ar="تقرير مالي"></option>
                            <option value="clinical" data-en="Clinical Report" data-ar="تقرير طبي"></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle export-dropdown" 
                                    data-bs-toggle="dropdown" data-en="Export" data-ar="تصدير"></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" onclick="exportPDF()" 
                                       data-en="PDF" data-ar="PDF"></a></li>
                                <li><a class="dropdown-item" onclick="exportExcel()" 
                                       data-en="Excel" data-ar="إكسل"></a></li>
                                <li><a class="dropdown-item" onclick="window.print()" 
                                       data-en="Print" data-ar="طباعة"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Reports -->
        <div class="card mb-4 report-card" id="financialReport">
            <div class="card-header" data-en="Financial Analytics" data-ar="التحليلات المالية"></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clinical Reports -->
        <div class="card report-card d-none" id="clinicalReport">
            <div class="card-header" data-en="Clinical Analytics" data-ar="التحليلات الطبية"></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="treatmentChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="infectionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographic Trends -->
        <div class="card mt-4 report-card">
            <div class="card-header" data-en="Demographic Trends" data-ar="الاتجاهات الديموغرافية"></div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="demographicChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Language Management
        let currentLang = localStorage.getItem('adminLang') || 'en';
        let charts = {};

        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'ar' : 'en';
            localStorage.setItem('adminLang', currentLang);
            applyLanguage();
            refreshCharts();
        }

        function applyLanguage() {
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            document.querySelectorAll('[data-en], [data-ar]').forEach(el => {
                const text = el.getAttribute(`data-${currentLang}`);
                if(text) el.textContent = text;
            });
        }

        // Chart Management
        async function loadReport() {
            const reportType = document.getElementById('reportType').value;
            document.getElementById('financialReport').classList.toggle('d-none', reportType !== 'financial');
            document.getElementById('clinicalReport').classList.toggle('d-none', reportType !== 'clinical');
            
            const data = await fetch(`get_report.php?type=${reportType}`).then(r => r.json());
            initializeCharts(data);
        }

        function initializeCharts(data) {
            destroyCharts();
            
            // Revenue Chart
            charts.revenue = new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: data.revenue,
                options: getChartOptions(currentLang === 'en' ? 'Revenue by Service' : 'الإيرادات حسب الخدمة')
            });

            // Performance Chart
            charts.performance = new Chart(document.getElementById('performanceChart'), {
                type: 'radar',
                data: data.performance,
                options: getChartOptions(currentLang === 'en' ? 'Dentist Performance' : 'أداء الأطباء')
            });

            // Treatment Chart
            charts.treatment = new Chart(document.getElementById('treatmentChart'), {
                type: 'line',
                data: data.treatment,
                options: getChartOptions(currentLang === 'en' ? 'Treatment Success Rate' : 'معدل نجاح العلاج')
            });

            // Infection Chart
            charts.infection = new Chart(document.getElementById('infectionChart'), {
                type: 'doughnut',
                data: data.infection,
                options: getChartOptions(currentLang === 'en' ? 'Infection Control' : 'التحكم في العدوى')
            });

            // Demographic Chart
            charts.demographic = new Chart(document.getElementById('demographicChart'), {
                type: 'pie',
                data: data.demographic,
                options: getChartOptions(currentLang === 'en' ? 'Patient Demographics' : 'التركيبة السكانية للمرضى')
            });
        }

        function getChartOptions(title) {
            return {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }
                }
            };
        }

        function destroyCharts() {
            Object.values(charts).forEach(chart => chart.destroy());
        }

        function refreshCharts() {
            loadReport();
        }

        // Export Functions
        function exportPDF() {
            // Implement PDF export using jsPDF
            console.log('Exporting to PDF...');
        }

        function exportExcel() {
            // Implement Excel export using ExcelJS
            console.log('Exporting to Excel...');
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            loadReport();
        };
    </script>
</body>
</html>