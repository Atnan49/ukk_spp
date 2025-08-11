<?php include '../app/controllers/view/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2><i class="fas fa-user-plus"></i> Tambah Wali Siswa</h2>
            
            <div class="mb-3">
                <a href="<?= urlTo('/uang_saku'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Form Tambah Data Wali Siswa</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= urlTo('/uang_saku/tambah_wali'); ?>">
                        <div class="mb-3">
                            <label for="id_siswa" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="id_siswa" id="id_siswa" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($siswa_list as $siswa): ?>
                                    <option value="<?= $siswa['id_siswa']; ?>">
                                        <?= htmlspecialchars($siswa['nama_siswa']); ?> - <?= htmlspecialchars($siswa['nisn']); ?>
                                        (<?= htmlspecialchars($siswa['nama_kelas']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_wali" class="form-label">Nama Wali/Orang Tua <span class="text-danger">*</span></label>
                            <input type="text" name="nama_wali" id="nama_wali" class="form-control" 
                                   placeholder="Masukkan nama lengkap wali/orang tua" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_hp_wali" class="form-label">No. HP Wali</label>
                            <input type="tel" name="no_hp_wali" id="no_hp_wali" class="form-control" 
                                   placeholder="Contoh: 081234567890">
                            <small class="text-muted">Nomor HP akan digunakan untuk notifikasi</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat_wali" class="form-label">Alamat Wali</label>
                            <textarea name="alamat_wali" id="alamat_wali" class="form-control" rows="3" 
                                      placeholder="Masukkan alamat lengkap wali/orang tua"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Data Wali
                            </button>
                            <a href="<?= urlTo('/uang_saku'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> Informasi
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Setiap siswa dapat memiliki lebih dari satu wali (ayah, ibu, atau wali lainnya)</li>
                        <li>Data wali digunakan untuk pengajuan top up uang saku siswa</li>
                        <li>Nomor HP wali opsional, namun disarankan diisi untuk kemudahan komunikasi</li>
                        <li>Pastikan data yang dimasukkan sudah benar sebelum menyimpan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/controllers/view/templates/footer.php'; ?>
