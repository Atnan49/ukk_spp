<?php
/**
 * Controller untuk mengelola sistem uang saku siswa
 */
class UangSakuController extends Controller
{
    public function index()
    {
        checkIsNotLogin();
        $uangSakuModel = $this->model('UangSaku');
        $data = [
            'username' => $_SESSION['username'] ?? null,
            'level' => $_SESSION['level'] ?? null,
        ];
        // Hak akses admin/petugas
        if (!empty($_SESSION['level']) && ($_SESSION['level'] == 'Admin' || $_SESSION['level'] == 'Petugas')) {
            $data['pengajuan'] = $uangSakuModel->getAllPengajuan();
            $data['title'] = 'Kelola Uang Saku';
            $this->view('uang_saku/admin', $data);
        }
        // Hak akses siswa
        elseif (!empty($_SESSION['level']) && $_SESSION['level'] == 'Siswa') {
            $siswaModel = $this->model('Siswa');
            $siswa = $siswaModel->getByUserId($_SESSION['id_user'] ?? 0);
            if ($siswa) {
                $data['siswa'] = $siswa;
                $data['saldo'] = $uangSakuModel->getSaldo($siswa['id_siswa']);
                $data['riwayat'] = $uangSakuModel->getRiwayatSiswa($siswa['id_siswa']);
                $data['title'] = 'Uang Saku Saya';
                $this->view('uang_saku/siswa', $data);
            } else {
                $data['error'] = 'Data siswa tidak ditemukan. Anda belum terdaftar sebagai siswa.';
                $this->view('uang_saku/siswa', $data);
            }
        } else {
            echo '<div class="alert alert-danger">Akses tidak diizinkan.</div>';
        }
    }
    
    public function pengajuan()
    {
        checkIsNotLogin();
        
        if ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Petugas') {
            header("location: " . urlTo('/uang_saku'));
            exit();
        }
        
        $uangSakuModel = $this->model('UangSaku');
        $data['pengajuan_pending'] = $uangSakuModel->getPengajuanByStatus('pending');
        $data['title'] = 'Pengajuan Uang Saku';
        
        $this->view('uang_saku/pengajuan', $data);
    }
    
    public function approve($id_topup)
    {
        checkIsNotLogin();
        
        if ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Petugas') {
            header("location: " . urlTo('/uang_saku'));
            exit();
        }
        
        $uangSakuModel = $this->model('UangSaku');
        $result = $uangSakuModel->approvePengajuan($id_topup, $_SESSION['id_user']);
        
        if ($result) {
            header("location: " . urlTo('/uang_saku/pengajuan?status=approved'));
        } else {
            header("location: " . urlTo('/uang_saku/pengajuan?status=error'));
        }
        exit();
    }
    
    public function reject()
    {
        checkIsNotLogin();
        
        if ($_SESSION['level'] != 'Admin' && $_SESSION['level'] != 'Petugas') {
            header("location: " . urlTo('/uang_saku'));
            exit();
        }
        
        if ($_POST) {
            $id_topup = $_POST['id_topup'];
            $reason = $_POST['rejection_reason'];
            
            $uangSakuModel = $this->model('UangSaku');
            $result = $uangSakuModel->rejectPengajuan($id_topup, $reason);
            
            if ($result) {
                header("location: " . urlTo('/uang_saku/pengajuan?status=rejected'));
            } else {
                header("location: " . urlTo('/uang_saku/pengajuan?status=error'));
            }
            exit();
        }
    }
    
    public function tambah_wali()
    {
        checkIsNotLogin();
        
        if ($_SESSION['level'] != 'Admin') {
            header("location: " . urlTo('/uang_saku'));
            exit();
        }
        
        if ($_POST) {
            $waliModel = $this->model('WaliSiswa');
            $data = [
                'id_siswa' => $_POST['id_siswa'],
                'nama_wali' => $_POST['nama_wali'],
                'no_hp_wali' => $_POST['no_hp_wali'],
                'alamat_wali' => $_POST['alamat_wali']
            ];
            
            $result = $waliModel->tambahWali($data);
            
            if ($result) {
                header("location: " . urlTo('/uang_saku?status=wali_added'));
            } else {
                header("location: " . urlTo('/uang_saku?status=wali_error'));
            }
            exit();
        }
        
        // Tampilkan form tambah wali
        $siswaModel = $this->model('Siswa');
        $data['siswa_list'] = $siswaModel->getAllSiswa();
        $data['title'] = 'Tambah Wali Siswa';
        $this->view('uang_saku/tambah_wali', $data);
    }
    
    public function pengajuan_wali()
    {
        // Endpoint untuk wali mengajukan top up (bisa diakses public atau dengan auth terpisah)
        if ($_POST) {
            $waliModel = $this->model('WaliSiswa');
            $uangSakuModel = $this->model('UangSaku');
            
            $data = [
                'id_siswa' => $_POST['id_siswa'],
                'id_wali' => $_POST['id_wali'],
                'nominal' => $_POST['nominal'],
                'catatan' => $_POST['catatan'] ?? ''
            ];
            
            $result = $uangSakuModel->tambahPengajuan($data);
            
            if ($result) {
                echo "Pengajuan berhasil dikirim. Menunggu persetujuan admin.";
            } else {
                echo "Terjadi kesalahan. Silakan coba lagi.";
            }
            exit();
        }
        
        // Form pengajuan untuk wali
        $data['title'] = 'Pengajuan Top Up Uang Saku';
        $this->view('uang_saku/form_wali', $data);
    }
}
