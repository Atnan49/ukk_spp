<?php
/**
 * Model untuk mengelola data wali siswa
 */
class WaliSiswa extends BaseModel
{
    public $table_name = 'wali_siswa';
    
    public function getByIdSiswa($id_siswa)
    {
        $id_siswa = intval($id_siswa);
        $query = "SELECT * FROM wali_siswa WHERE id_siswa = $id_siswa";
        
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function tambahWali($data)
    {
        $id_siswa = intval($data['id_siswa']);
        $nama_wali = $this->mysqli->real_escape_string($data['nama_wali']);
        $no_hp_wali = $this->mysqli->real_escape_string($data['no_hp_wali']);
        $alamat_wali = $this->mysqli->real_escape_string($data['alamat_wali']);
        
        $query = "INSERT INTO wali_siswa (id_siswa, nama_wali, no_hp_wali, alamat_wali)
                  VALUES ($id_siswa, '$nama_wali', '$no_hp_wali', '$alamat_wali')";
        
        return $this->mysqli->query($query);
    }
    
    public function updateWali($id_wali, $data)
    {
        $id_wali = intval($id_wali);
        $nama_wali = $this->mysqli->real_escape_string($data['nama_wali']);
        $no_hp_wali = $this->mysqli->real_escape_string($data['no_hp_wali']);
        $alamat_wali = $this->mysqli->real_escape_string($data['alamat_wali']);
        
        $query = "UPDATE wali_siswa 
                  SET nama_wali = '$nama_wali', no_hp_wali = '$no_hp_wali', alamat_wali = '$alamat_wali'
                  WHERE id_wali = $id_wali";
        
        return $this->mysqli->query($query);
    }
    
    public function getById($id_wali)
    {
        $id_wali = intval($id_wali);
        $query = "SELECT w.*, s.nama_siswa, s.nisn
                  FROM wali_siswa w
                  LEFT JOIN siswa s ON w.id_siswa = s.id_siswa
                  WHERE w.id_wali = $id_wali";
        
        $result = $this->mysqli->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getAllWali()
    {
        $query = "SELECT w.*, s.nama_siswa, s.nisn
                  FROM wali_siswa w
                  LEFT JOIN siswa s ON w.id_siswa = s.id_siswa
                  ORDER BY s.nama_siswa ASC";
        
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
}
