<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Treatment Management" data-ar="إدارة العلاجات"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .procedure-card { transition: transform 0.2s; }
        .procedure-card:hover { transform: translateY(-3px); }
        .low-stock { background-color: #ffe6e6; }
        .maintenance-due { border-left: 3px solid #ffc107; }
        .price-tier { font-size: 0.9rem; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Procedure Catalog -->
        <div class="card mb-4">
            <div class="card-header" data-en="Procedure Catalog" data-ar="قائمة الإجراءات"></div>
            <div class="card-body">
                <div class="row g-4" id="procedureList">
                    <?php include('get_procedures.php'); ?>
                </div>
            </div>
        </div>

        <!-- Inventory & Equipment -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header" data-en="Inventory Alerts" data-ar="تنبيهات المخزون"></div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th data-en="Item" data-ar="العنصر"></th>
                                    <th data-en="Stock" data-ar="المخزون"></th>
                                    <th data-en="Status" data-ar="الحالة"></th>
                                </tr>
                            </thead>
                            <tbody id="inventoryList">
                                <?php include('get_inventory.php'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header" data-en="Equipment Maintenance" data-ar="صيانة المعدات"></div>
                    <div class="card-body">
                        <div class="list-group" id="maintenanceLogs">
                            <?php include('get_maintenance.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Templates -->
        <div class="card">
            <div class="card-header" data-en="Prescription Templates" data-ar="قوالب الوصفات"></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="templateSelect">
                            <?php include('get_prescriptions.php'); ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="border p-3" id="templatePreview"></div>
                    </div>
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
                if (text) el.textContent = text;
            });
        }

        // Inventory Alerts
        function checkStockLevels() {
            document.querySelectorAll('.stock-level').forEach(item => {
                const currentStock = parseInt(item.dataset.stock);
                const minStock = parseInt(item.dataset.min);
                
                if(currentStock < minStock) {
                    item.closest('tr').classList.add('low-stock');
                    item.innerHTML = `${currentStock} <i class="fas fa-exclamation-triangle text-danger"></i>`;
                }
            });
        }

        // Prescription Template Preview
        document.getElementById('templateSelect').addEventListener('change', function() {
            const template = JSON.parse(this.value);
            document.getElementById('templatePreview').innerHTML = `
                <h5>${template.name}</h5>
                <p>${template.details}</p>
                <pre>${template.dosage}</pre>
            `;
        });

        window.onload = () => {
            applyLanguage();
            checkStockLevels();
        };
    </script>
</body>
</html>