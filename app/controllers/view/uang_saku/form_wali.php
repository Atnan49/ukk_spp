<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Top Up Uang Saku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3><i class="fas fa-wallet"></i> Pengajuan Top Up Uang Saku</h3>
                    <p class="mb-0">Form untuk Wali/Orang Tua Siswa</p>
                </div>
                <div class="card-body">
                    <?php if ($_POST): ?>
                        <!-- Success/Error Message Display -->
                        <div class="alert alert-info">
                            Pengajuan sedang diproses...
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?= urlTo('/uang_saku/pengajuan_wali'); ?>">
                            <div class="mb-3">
                                <label for="id_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                                <input type="number" name="id_siswa" id="id_siswa" class="form-control" 
                                       placeholder="Masukkan ID Siswa" required>
                                <small class="text-muted">Hubungi admin sekolah untuk mendapatkan ID siswa</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_wali" class="form-label">ID Wali <span class="text-danger">*</span></label>
                                <input type="number" name="id_wali" id="id_wali" class="form-control" 
                                       placeholder="Masukkan ID Wali Anda" required>
                                <small class="text-muted">Hubungi admin sekolah untuk mendapatkan ID wali</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal Top Up <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="nominal" id="nominal" class="form-control" 
                                           placeholder="0" min="10000" step="1000" required>
                                </div>
                                <small class="text-muted">Minimal Rp 10.000</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3" 
                                          placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Ajukan Top Up
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> Informasi Pengajuan
                </div>
                <div class="card-body">
                    <h6>Cara Pengajuan Top Up:</h6>
                    <ol>
                        <li>Hubungi admin sekolah untuk mendapatkan ID Siswa dan ID Wali Anda</li>
                        <li>Isi form pengajuan dengan lengkap</li>
                        <li>Klik "Ajukan Top Up" untuk mengirim pengajuan</li>
                        <li>Tunggu persetujuan dari admin sekolah</li>
                        <li>Uang saku akan bertambah setelah disetujui</li>
                    </ol>
                    
                    <h6 class="mt-3">Ketentuan:</h6>
                    <ul>
                        <li>Minimal top up Rp 10.000</li>
                        <li>Pengajuan akan diproses dalam 1-2 hari kerja</li>
                        <li>Hubungi admin jika pengajuan tidak diproses dalam 3 hari kerja</li>
                    </ul>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-phone"></i> Kontak Admin
                </div>
                <div class="card-body">
                    <p><strong>Kantor Tata Usaha:</strong></p>
                    <p><i class="fas fa-phone"></i> (021) 1234-5678<br>
                       <i class="fas fa-envelope"></i> admin@sekolah.sch.id<br>
                       <i class="fas fa-clock"></i> Senin - Jumat, 07:00 - 15:00 WIB</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Format input nominal
document.getElementById('nominal').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});

// Validate form
document.querySelector('form').addEventListener('submit', function(e) {
    const nominal = parseInt(document.getElementById('nominal').value);
    if (nominal < 10000) {
        e.preventDefault();
        alert('Nominal minimal adalah Rp 10.000');
        return false;
    }
    
    return confirm('Yakin ingin mengajukan top up sebesar Rp ' + nominal.toLocaleString('id-ID') + '?');
});
</script>

</body>
</html>
