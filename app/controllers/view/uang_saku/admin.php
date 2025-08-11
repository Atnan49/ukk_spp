<?php include '../app/controllers/view/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2><i class="fas fa-wallet"></i> Kelola Uang Saku Siswa</h2>
            
            <!-- Status Alert -->
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
                        case 'wali_added':
                            echo '<i class="fas fa-user-plus"></i> Data wali berhasil ditambahkan!';
                            break;
                        case 'error':
                            echo '<i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan!';
                            break;
                    }
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="mb-3">
                <a href="<?= urlTo('/uang_saku/pengajuan'); ?>" class="btn btn-primary">
                    <i class="fas fa-list"></i> Pengajuan Pending
                </a>
                <a href="<?= urlTo('/uang_saku/tambah_wali'); ?>" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Tambah Wali Siswa
                </a>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5>Pending</h5>
                            <h3><?= count(array_filter($pengajuan, function($p) { return $p['status'] == 'pending'; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Approved</h5>
                            <h3><?= count(array_filter($pengajuan, function($p) { return $p['status'] == 'approved'; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5>Rejected</h5>
                            <h3><?= count(array_filter($pengajuan, function($p) { return $p['status'] == 'rejected'; })); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Total</h5>
                            <h3><?= count($pengajuan); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="card">
                <div class="card-header">
                    <h5>Semua Pengajuan Uang Saku</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>NISN</th>
                                    <th>Wali</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pengajuan)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data pengajuan</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pengajuan as $index => $p): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($p['tanggal_pengajuan'])); ?></td>
                                            <td><?= htmlspecialchars($p['nama_siswa']); ?></td>
                                            <td><?= htmlspecialchars($p['nisn']); ?></td>
                                            <td><?= htmlspecialchars($p['nama_wali']); ?></td>
                                            <td>Rp <?= number_format($p['nominal'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $badge_class = '';
                                                switch ($p['status']) {
                                                    case 'pending':
                                                        $badge_class = 'bg-warning';
                                                        break;
                                                    case 'approved':
                                                        $badge_class = 'bg-success';
                                                        break;
                                                    case 'rejected':
                                                        $badge_class = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $badge_class; ?>"><?= ucfirst($p['status']); ?></span>
                                            </td>
                                            <td><?= $p['approved_by_name'] ? htmlspecialchars($p['approved_by_name']) : '-'; ?></td>
                                            <td>
                                                <?php if ($p['status'] == 'pending'): ?>
                                                    <a href="<?= urlTo('/uang_saku/approve/' . $p['id_topup']); ?>" 
                                                       class="btn btn-sm btn-success"
                                                       onclick="return confirm('Setujui pengajuan ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal<?= $p['id_topup']; ?>">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        
                                        <!-- Reject Modal -->
                                        <?php if ($p['status'] == 'pending'): ?>
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
                                                                    <label class="form-label">Alasan Penolakan</label>
                                                                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
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
