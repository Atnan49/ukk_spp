<?php include '../app/controllers/view/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2><i class="fas fa-wallet"></i> Uang Saku Saya</h2>
            
            <!-- Saldo Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4>Saldo Uang Saku</h4>
                            <h2>Rp <?= number_format($saldo, 0, ',', '.'); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4>Data Siswa</h4>
                            <h5><?= htmlspecialchars($siswa['nama_siswa']); ?></h5>
                            <p>NISN: <?= htmlspecialchars($siswa['nisn']); ?></p>
                            <p>Kelas: <?= htmlspecialchars($siswa['nama_kelas']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Riwayat Top Up -->
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Top Up Uang Saku</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nominal</th>
                                    <th>Wali Pengaju</th>
                                    <th>Status</th>
                                    <th>Tanggal Disetujui</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($riwayat)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada riwayat top up</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($riwayat as $index => $r): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($r['tanggal_pengajuan'])); ?></td>
                                            <td>
                                                <?php if ($r['status'] == 'approved'): ?>
                                                    <span class="text-success">+ Rp <?= number_format($r['nominal'], 0, ',', '.'); ?></span>
                                                <?php else: ?>
                                                    Rp <?= number_format($r['nominal'], 0, ',', '.'); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($r['nama_wali']); ?></td>
                                            <td>
                                                <?php
                                                $badge_class = '';
                                                $status_text = '';
                                                switch ($r['status']) {
                                                    case 'pending':
                                                        $badge_class = 'bg-warning';
                                                        $status_text = 'Menunggu';
                                                        break;
                                                    case 'approved':
                                                        $badge_class = 'bg-success';
                                                        $status_text = 'Disetujui';
                                                        break;
                                                    case 'rejected':
                                                        $badge_class = 'bg-danger';
                                                        $status_text = 'Ditolak';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $badge_class; ?>"><?= $status_text; ?></span>
                                            </td>
                                            <td>
                                                <?= $r['approved_at'] ? date('d/m/Y H:i', strtotime($r['approved_at'])) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php if ($r['status'] == 'rejected' && $r['rejection_reason']): ?>
                                                    <span class="text-danger"><?= htmlspecialchars($r['rejection_reason']); ?></span>
                                                <?php elseif ($r['catatan']): ?>
                                                    <?= htmlspecialchars($r['catatan']); ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Info untuk Wali -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-info-circle"></i> Informasi untuk Wali/Orang Tua</h5>
                </div>
                <div class="card-body">
                    <p>Untuk menambah uang saku, wali/orang tua dapat menggunakan link berikut:</p>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= urlTo('/uang_saku/pengajuan_wali'); ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard(this.previousElementSibling)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <small class="text-muted">Bagikan link ini kepada wali/orang tua untuk mengajukan top up uang saku.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(element) {
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(element.value);
    
    // Show feedback
    const button = element.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>

<?php include '../app/controllers/view/templates/footer.php'; ?>
