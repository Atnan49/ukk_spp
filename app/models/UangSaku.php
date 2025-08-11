<?php
/**
 * Model untuk mengelola data uang saku dan top up
 */
class UangSaku extends BaseModel
{
    public $table_name = 'uang_saku_topup';
    
    public function getAllPengajuan()
    {
        $query = "SELECT t.*, s.nama_siswa, s.nisn, w.nama_wali, u.user_name as approved_by_name
                  FROM uang_saku_topup t
                  LEFT JOIN siswa s ON t.id_siswa = s.id_siswa
                  LEFT JOIN wali_siswa w ON t.id_wali = w.id_wali
                  LEFT JOIN users u ON t.approved_by = u.id_user
                  ORDER BY t.tanggal_pengajuan DESC";
        
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function getPengajuanByStatus($status)
    {
        $status = $this->mysqli->real_escape_string($status);
        $query = "SELECT t.*, s.nama_siswa, s.nisn, w.nama_wali, w.no_hp_wali
                  FROM uang_saku_topup t
                  LEFT JOIN siswa s ON t.id_siswa = s.id_siswa
                  LEFT JOIN wali_siswa w ON t.id_wali = w.id_wali
                  WHERE t.status = '$status'
                  ORDER BY t.tanggal_pengajuan ASC";
        
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function getRiwayatSiswa($id_siswa)
    {
        $id_siswa = intval($id_siswa);
        $query = "SELECT t.*, w.nama_wali, u.user_name as approved_by_name
                  FROM uang_saku_topup t
                  LEFT JOIN wali_siswa w ON t.id_wali = w.id_wali
                  LEFT JOIN users u ON t.approved_by = u.id_user
                  WHERE t.id_siswa = $id_siswa
                  ORDER BY t.tanggal_pengajuan DESC";
        
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function getSaldo($id_siswa)
    {
        $id_siswa = intval($id_siswa);
        $query = "SELECT saldo_uang_saku FROM vw_uang_saku_saldo WHERE id_siswa = $id_siswa";
        
        $result = $this->mysqli->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['saldo_uang_saku'];
        }
        
        return 0;
    }
    
    public function tambahPengajuan($data)
    {
        $id_siswa = intval($data['id_siswa']);
        $id_wali = intval($data['id_wali']);
        $nominal = intval($data['nominal']);
        $catatan = $this->mysqli->real_escape_string($data['catatan']);
        
        $query = "INSERT INTO uang_saku_topup (id_siswa, id_wali, nominal, catatan)
                  VALUES ($id_siswa, $id_wali, $nominal, '$catatan')";
        
        return $this->mysqli->query($query);
    }
    
    public function approvePengajuan($id_topup, $approved_by)
    {
        $id_topup = intval($id_topup);
        $approved_by = intval($approved_by);
        
        $query = "UPDATE uang_saku_topup 
                  SET status = 'approved', approved_by = $approved_by
                  WHERE id_topup = $id_topup AND status = 'pending'";
        
        return $this->mysqli->query($query);
    }
    
    public function rejectPengajuan($id_topup, $reason)
    {
        $id_topup = intval($id_topup);
        $reason = $this->mysqli->real_escape_string($reason);
        
        $query = "UPDATE uang_saku_topup 
                  SET status = 'rejected', rejection_reason = '$reason'
                  WHERE id_topup = $id_topup AND status = 'pending'";
        
        return $this->mysqli->query($query);
    }
    
    public function getPengajuanById($id_topup)
    {
        $id_topup = intval($id_topup);
        $query = "SELECT t.*, s.nama_siswa, w.nama_wali
                  FROM uang_saku_topup t
                  LEFT JOIN siswa s ON t.id_siswa = s.id_siswa
                  LEFT JOIN wali_siswa w ON t.id_wali = w.id_wali
                  WHERE t.id_topup = $id_topup";
        
        $result = $this->mysqli->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
