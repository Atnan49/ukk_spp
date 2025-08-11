<?php
/**
 * Model untuk mengelola data siswa
 */
class Siswa extends BaseModel
{
    public $table_name = 'siswa';
    
    public function getByUserId($id_user)
    {
        $id_user = intval($id_user);
        $query = "SELECT s.*, k.nama_kelas, k.kompetensi_keahlian, spp.tahun, spp.nominal
                  FROM siswa s
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                  LEFT JOIN spp ON s.id_spp = spp.id_spp
                  WHERE s.id_user = $id_user";
        
        $result = $this->mysqli->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getAllSiswa()
    {
        $query = "SELECT s.*, k.nama_kelas, k.kompetensi_keahlian
                  FROM siswa s
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
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
    
    public function getById($id_siswa)
    {
        $id_siswa = intval($id_siswa);
        $query = "SELECT s.*, k.nama_kelas, k.kompetensi_keahlian, spp.tahun, spp.nominal
                  FROM siswa s
                  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                  LEFT JOIN spp ON s.id_spp = spp.id_spp
                  WHERE s.id_siswa = $id_siswa";
        
        $result = $this->mysqli->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}
