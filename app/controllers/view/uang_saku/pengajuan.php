<?php include '../app/controllers/view/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2><i class="fas fa-hourglass-half"></i> Pengajuan Uang Saku Pending</h2>
            
            <div class="mb-3">
                <a href="<?= urlTo('/uang_saku'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php
                    switch ($_GET['status']) {
                        case 'approved':
                            echo '<i class="fas fa-check-circle"></i> Pengajuan berhasil disetujui!';
                            break;
                        case 'rejected':
                            echo '<i class="fas fa-times-circle"></i> Pengajuan berhasil ditolak!';
                            break;
                        case 'error':
                            echo '<i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan!';
                            break;
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Pengajuan Menunggu Persetujuan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Siswa</th>
                                    <th>NISN</th>
                                    <th>Wali Pengaju</th>
                                    <th>No. HP Wali</th>
                                    <th>Nominal</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pengajuan_pending)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i><br>
                                            Tidak ada pengajuan yang menunggu persetujuan
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pengajuan_pending as $index => $p): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($p['tanggal_pengajuan'])); ?></td>
                                            <td><?= htmlspecialchars($p['nama_siswa']); ?></td>
                                            <td><?= htmlspecialchars($p['nisn']); ?></td>
                                            <td><?= htmlspecialchars($p['nama_wali']); ?></td>
                                            <td><?= htmlspecialchars($p['no_hp_wali'] ?: '-'); ?></td>
                                            <td><strong>Rp <?= number_format($p['nominal'], 0, ',', '.'); ?></strong></td>
                                            <td><?= $p['catatan'] ? htmlspecialchars($p['catatan']) : '-'; ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= urlTo('/uang_saku/approve/' . $p['id_topup']); ?>" 
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('Setujui pengajuan uang saku sebesar Rp <?= number_format($p['nominal'], 0, ',', '.'); ?> untuk <?= htmlspecialchars($p['nama_siswa']); ?>?')">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal<?= $p['id_topup']; ?>">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal<?= $p['id_topup']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="<?= urlTo('/uang_saku/reject'); ?>">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Pengajuan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_topup" value="<?= $p['id_topup']; ?>">
                                                            <div class="mb-3">
                                                                <strong>Pengajuan dari:</strong> <?= htmlspecialchars($p['nama_wali']); ?><br>
                                                                <strong>Untuk siswa:</strong> <?= htmlspecialchars($p['nama_siswa']); ?><br>
                                                                <strong>Nominal:</strong> Rp <?= number_format($p['nominal'], 0, ',', '.'); ?>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" class="form-control" rows="3" 
                                                                          placeholder="Berikan alasan mengapa pengajuan ini ditolak..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times"></i> Tolak Pengajuan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/controllers/view/templates/footer.php'; ?>
