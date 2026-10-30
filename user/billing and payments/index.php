<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Billing & Payments" data-ar="الفواتير والمدفوعات"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .payment-card { transition: transform 0.2s; }
        .payment-card:hover { transform: translateY(-3px); }
        .balance-card { background: linear-gradient(45deg, #0062cc, #0096FF); color: white; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Current Balance -->
        <div class="card balance-card mb-4">
            <div class="card-body">
                <h3 data-en="Current Balance" data-ar="الرصيد الحالي"></h3>
                <h1 class="display-4" id="currentBalance">
                    <?php 
                        $balance = json_decode(file_get_contents('balance.json'), true);
                        echo number_format($balance['amount'], 2); 
                    ?> 
                    <small data-en="JOD" data-ar="دينار أردني"></small>
                </h1>
            </div>
        </div>

        <div class="row g-4">
            <!-- Payment Form -->
            <div class="col-lg-5">
                <div class="card payment-card">
                    <div class="card-header" data-en="Make Payment" data-ar="إجراء دفعة"></div>
                    <div class="card-body">
                        <form id="paymentForm" action="process_payment.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label" data-en="Amount" data-ar="المبلغ"></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="amount" 
                                           min="1" step="0.01" required>
                                    <span class="input-group-text" data-en="JOD" data-ar="دينار أردني"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" data-en="Card Number" data-ar="رقم البطاقة"></label>
                                <input type="text" class="form-control" name="card_number" 
                                       pattern="\d{16}" placeholder="4242 4242 4242 4242" required>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" data-en="Expiry Date" data-ar="تاريخ الانتهاء"></label>
                                    <input type="month" class="form-control" name="expiry" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-en="CVV" data-ar="CVV"></label>
                                    <input type="text" class="form-control" name="cvv" 
                                           pattern="\d{3}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100" 
                                    data-en="Pay Now" data-ar="ادفع الآن"></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="col-lg-7">
                <div class="card payment-card">
                    <div class="card-header" data-en="Payment History" data-ar="سجل المدفوعات"></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th data-en="Date" data-ar="التاريخ"></th>
                                        <th data-en="Amount" data-ar="المبلغ"></th>
                                        <th data-en="Method" data-ar="الطريقة"></th>
                                        <th data-en="Status" data-ar="الحالة"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include('get_payment_history.php'); ?>
                                </tbody>
                            </table>
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

        // Form Validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const cardNumber = this.card_number.value.replace(/\s/g, '');
            if (!/^\d{16}$/.test(cardNumber)) {
                alert(currentLang === 'en' ? 'Invalid card number' : 'رقم البطاقة غير صالح');
                e.preventDefault();
            }
        });

        // Initialize language
        window.onload = applyLanguage;
    </script>
</body>
</html>