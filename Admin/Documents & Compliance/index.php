<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-en="Documents & Compliance" data-ar="المستندات والامتثال"></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .rtl { direction: rtl; text-align: right; }
        .document-card { border-left: 3px solid #0d6efd; }
        .version-history { max-height: 300px; overflow-y: auto; }
        .backup-status { width: 15px; height: 15px; border-radius: 50%; }
        .drag-drop-area { border: 2px dashed #dee2e6; }
    </style>
</head>
<body>
    <button onclick="toggleLanguage()" class="btn btn-secondary m-3" 
            data-en="عربي" data-ar="English"></button>

    <div class="container my-5">
        <!-- Document Repository -->
        <div class="card mb-4">
            <div class="card-header" data-en="Document Repository" data-ar="مستودع المستندات"></div>
            <div class="card-body">
                <div class="drag-drop-area p-5 text-center mb-4"
                     data-en-drop="Drop files here to upload" 
                     data-ar-drop="إسقاط الملفات هنا للتحميل"
                     ondrop="handleDrop(event)"
                     ondragover="handleDragOver(event)">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                </div>
                
                <div class="row g-4" id="documentList">
                    <?php include('get_documents.php'); ?>
                </div>
            </div>
        </div>

        <!-- Version Control -->
        <div class="card mb-4">
            <div class="card-header" data-en="Version History" data-ar="تاريخ الإصدارات"></div>
            <div class="card-body">
                <div class="version-history">
                    <table class="table">
                        <thead>
                            <tr>
                                <th data-en="Version" data-ar="الإصدار"></th>
                                <th data-en="Date" data-ar="التاريخ"></th>
                                <th data-en="Author" data-ar="المؤلف"></th>
                                <th data-en="Changes" data-ar="التغييرات"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include('get_versions.php'); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Backup Management -->
        <div class="card">
            <div class="card-header" data-en="Backup Management" data-ar="إدارة النسخ الاحتياطي"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="backup-status bg-success"></span>
                        <span data-en="Last Backup" data-ar="آخر نسخة احتياطية"></span>
                        <span id="lastBackup"><?php include('get_last_backup.php'); ?></span>
                    </div>
                    <button class="btn btn-primary" onclick="startBackup()"
                            data-en="Start Backup" data-ar="بدء النسخ الاحتياطي"></button>
                </div>
                <div class="progress mt-3">
                    <div class="progress-bar" id="backupProgress" style="width: 0%"></div>
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
                if(text) el.textContent = text;
            });
        }

        // File Handling
        function handleDragOver(e) {
            e.preventDefault();
            e.target.classList.add('border-primary');
        }

        async function handleDrop(e) {
            e.preventDefault();
            e.target.classList.remove('border-primary');
            const files = e.dataTransfer.files;
            // Handle file upload
            console.log('Files dropped:', files);
        }

        // Backup Simulation
        async function startBackup() {
            const progressBar = document.getElementById('backupProgress');
            for(let i = 0; i <= 100; i++) {
                progressBar.style.width = `${i}%`;
                await new Promise(r => setTimeout(r, 50));
            }
            document.getElementById('lastBackup').textContent = new Date().toLocaleString();
        }

        window.onload = applyLanguage;
    </script>
</body>
</html>