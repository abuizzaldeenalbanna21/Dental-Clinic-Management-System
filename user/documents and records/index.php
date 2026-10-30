<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Medical Documents" data-ar="الوثائق الطبية"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .document-card { transition: transform 0.2s; }
        .document-card:hover { transform: translateY(-3px); }
        .preview-image { height: 200px; object-fit: cover; cursor: pointer; }
        .category-badge { position: absolute; top: 10px; right: 10px; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Document Categories -->
        <div class="row mb-4">
            <div class="col">
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary active" data-filter="all"
                            data-en="All Documents" data-ar="جميع الوثائق"></button>
                    <button class="btn btn-outline-primary" data-filter="xray"
                            data-en="X-Rays" data-ar="الأشعة"></button>
                    <button class="btn btn-outline-primary" data-filter="treatment"
                            data-en="Treatment Plans" data-ar="خطط العلاج"></button>
                    <button class="btn btn-outline-primary" data-filter="consent"
                            data-en="Consent Forms" data-ar="نموذج الموافقة"></button>
                </div>
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="row g-4" id="documentsGrid">
            <?php include('get_documents.php'); ?>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="previewContent"></div>
                </div>
                <div class="modal-footer">
                    <a id="downloadLink" class="btn btn-primary" download
                       data-en="Download" data-ar="تحميل"></a>
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

        // Document Filtering
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;
                document.querySelectorAll('.document-card').forEach(card => {
                    card.style.display = (filter === 'all' || card.dataset.category === filter) ? '' : 'none';
                });
            });
        });

        // Preview Document
        function previewDocument(file, title, type) {
            const previewContent = document.getElementById('previewContent');
            const downloadLink = document.getElementById('downloadLink');
            
            if (type === 'pdf') {
                previewContent.innerHTML = `<embed src="${file}" type="application/pdf" width="100%" height="600px">`;
            } else {
                previewContent.innerHTML = `<img src="${file}" class="img-fluid">`;
            }
            
            document.getElementById('previewTitle').textContent = title;
            downloadLink.href = file;
            
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }

        // Initialize
        window.onload = applyLanguage;
    </script>
</body>
</html>