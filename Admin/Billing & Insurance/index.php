<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Billing & Insurance" data-ar="الفواتير والتأمين"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .rtl { direction: rtl; text-align: right; }
        .invoice-item:hover { background-color: #f8f9fa; }
        .payment-card { transition: transform 0.2s; }
        .payment-card:hover { transform: translateY(-3px); }
        .insurance-code { font-family: monospace; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Invoice Generation -->
        <div class="card mb-4">
            <div class="card-header" data-en="Invoice Generation" data-ar="إنشاء الفواتير"></div>
            <div class="card-body">
                <form id="invoiceForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" data-en="Patient ID" data-ar="رقم المريض"></label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" data-en="Insurance Provider" data-ar="شركة التأمين"></label>
                            <select class="form-select" id="insuranceProvider">
                                <option value="ADNIC" data-en="ADNIC" data-ar="أدنيك"></option>
                                <option value="Daman" data-en="Daman" data-ar="ضمان"></option>
                                <option value="No Insureance" data-en="No Insureance" data-ar="لا يوجد تأمين"></option>
                            </select>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mt-4">
                        <div class="invoice-item row g-3 mb-3">
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Description" 
                                       data-en-placeholder="Description" data-ar-placeholder="الوصف">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="material" data-en="Material" data-ar="مواد"></option>
                                    <option value="labor" data-en="Labor" data-ar="عمالة"></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" placeholder="Amount" 
                                       data-en-placeholder="Amount" data-ar-placeholder="المبلغ">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-danger" data-en="Remove" data-ar="إزالة"></button>
                            </div>
                        </div>
                        <button class="btn btn-success" onclick="addInvoiceItem()"
                                data-en="Add Item" data-ar="إضافة عنصر"></button>
                    </div>

                    <!-- Totals -->
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table">
                                    <tr>
                                        <td data-en="Subtotal" data-ar="المجموع الفرعي"></td>
                                        <td id="subtotal">0.00 JOD</td>
                                    </tr>
                                    <tr>
                                        <td data-en="Tax (3%)" data-ar="الضريبة (3%)"></td>
                                        <td id="tax">0.00 JOD</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td data-en="Total Due" data-ar="المبلغ الإجمالي"></td>
                                        <td id="total">0.00 JOD</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payment Processing -->
        <div class="card mb-4">
            <div class="card-header" data-en="Payment Processing" data-ar="معالجة المدفوعات"></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="payment-card card">
                            <div class="card-body">
                                <h5 data-en="Online Payments" data-ar="المدفوعات الإلكترونية"></h5>
                                <select class="form-select mb-3">
                                    <option data-en="Credit Card" data-ar="بطاقة ائتمان"></option>
                                    <option data-en="Apple Pay" data-ar="آبل باي"></option>
                                    <option data-en="Cash" data-ar="كاش"></option>
                                </select>
                                <div id="paymentGateway"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 data-en="Payment Status" data-ar="حالة الدفع"></h5>
                                <canvas id="paymentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debt Management -->
        <div class="card">
            <div class="card-header" data-en="Debt Management" data-ar="إدارة الديون"></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between">
                                <span data-en="Send Reminders" data-ar="إرسال التذكيرات"></span>
                                <button class="btn btn-warning btn-sm"
                                        data-en="Send All" data-ar="إرسال الكل"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger w-100"
                                data-en="Export to Collection Agency" data-ar="تصدير لشركة التحصيل"></button>
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
            updateChart();
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

        // Invoice Calculations
        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.invoice-item input[type="number"]').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            
            const tax = subtotal * 0.03;
            const total = subtotal + tax;
            
            document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' JOD';
            document.getElementById('tax').textContent = tax.toFixed(2) + ' JOD';
            document.getElementById('total').textContent = total.toFixed(2) + ' JOD';
        }

        // Payment Chart
        let paymentChart;
        function updateChart() {
            const labels = currentLang === 'en' 
                ? ['Paid', 'Partial', 'Unpaid'] 
                : ['مدفوع', 'جزئي', 'غير مدفوع'];
            
            if(paymentChart) paymentChart.destroy();
            
            paymentChart = new Chart(document.getElementById('paymentChart'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: [65, 15, 20],
                        backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                    }]
                }
            });
        }

        // Initialize
        window.onload = () => {
            applyLanguage();
            updateChart();
            document.querySelectorAll('.invoice-item input').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });
        };
    </script>
</body>
</html>