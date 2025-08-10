CREATE TABLE spp(
    id_spp INT NOT NULL AUTO_INCREMENT,
    tahun VARCHAR(10),
    nominal INT,
    PRIMARY KEY(id_spp));
    
CREATE TABLE kelas(
    id_kelas INT NOT NULL AUTO_INCREMENT,
    nama_kelas VARCHAR(20),
    kompetensi_keahlian VARCHAR(50),
    PRIMARY KEY(id_kelas));

CREATE TABLE users(
    id_user INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(25),
    password VARCHAR(125),
    level ENUM('Admin','Petugas','Siswa'),
    gambar VARCHAR(125),
    remember_token VARCHAR(125),
    PRIMARY KEY(id_user));

CREATE TABLE siswa(
    id_siswa INT NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    id_spp INT NOT NULL,
    id_kelas INT NOT NULL,
    nisn VARCHAR(10) UNIQUE,
    nis VARCHAR(8) UNIQUE,
    nama_siswa VARCHAR(35),
    alamat_siswa TEXT,
    no_telepon_siswa VARCHAR(15),
    PRIMARY KEY(id_siswa),
    FOREIGN KEY(id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY(id_spp) REFERENCES spp(id_spp) ON DELETE CASCADE,
    FOREIGN KEY(id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE
);

CREATE TABLE petugas(
    id_petugas INT NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    nama_petugas VARCHAR(55),
    no_hp_petugas VARCHAR(25),
    alamat_petugas TEXT,
    PRIMARY KEY (id_petugas),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE pembayaran(
    id_pembayaran INT NOT NULL AUTO_INCREMENT,
    id_siswa INT NOT NULL,
    id_petugas INT NOT NULL,
    id_spp INT NOT NULL,
    tanggal_pembayaran DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    jumlah INT NOT NULL,
    PRIMARY KEY (id_pembayaran),
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE,
    FOREIGN KEY (id_petugas) REFERENCES petugas(id_petugas) ON DELETE CASCADE,
    FOREIGN KEY (id_spp) REFERENCES siswa(id_spp) ON DELETE CASCADE
);


-- =============================================
-- Sistem Uang Saku Siswa
-- 1) Data wali siswa (orang tua/guardian)
CREATE TABLE IF NOT EXISTS wali_siswa (
    id_wali INT NOT NULL AUTO_INCREMENT,
    id_siswa INT NOT NULL,
    nama_wali VARCHAR(55) NOT NULL,
    no_hp_wali VARCHAR(25),
    alamat_wali TEXT,
    PRIMARY KEY (id_wali),
    INDEX idx_wali_siswa (id_siswa),
    CONSTRAINT fk_wali_siswa_siswa
        FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 2) Pengajuan top up uang saku oleh wali, dikonfirmasi admin
CREATE TABLE IF NOT EXISTS uang_saku_topup (
    id_topup INT NOT NULL AUTO_INCREMENT,
    id_siswa INT NOT NULL,
    id_wali INT NOT NULL,
    tanggal_pengajuan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    nominal INT NOT NULL,
    catatan VARCHAR(255),
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    approved_by INT NULL, -- id_user admin yang menyetujui
    approved_at DATETIME NULL,
    rejection_reason VARCHAR(255) NULL,
    PRIMARY KEY (id_topup),
    INDEX idx_topup_siswa_status (id_siswa, status),
    INDEX idx_topup_wali (id_wali),
    INDEX idx_topup_approved_by (approved_by),
    CONSTRAINT fk_topup_siswa
        FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_topup_wali
        FOREIGN KEY (id_wali) REFERENCES wali_siswa(id_wali)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_topup_approved_by
        FOREIGN KEY (approved_by) REFERENCES users(id_user)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3) View saldo uang saku per siswa (akumulasi topup berstatus approved)
CREATE OR REPLACE VIEW vw_uang_saku_saldo AS
SELECT 
    s.id_siswa,
    COALESCE(SUM(CASE WHEN t.status = 'approved' THEN t.nominal ELSE 0 END), 0) AS saldo_uang_saku
FROM siswa s
LEFT JOIN uang_saku_topup t ON t.id_siswa = s.id_siswa
GROUP BY s.id_siswa;

-- 4) Triggers validasi dan otomatisasi field approval
DELIMITER $$

CREATE TRIGGER trg_uang_saku_topup_bi
BEFORE INSERT ON uang_saku_topup
FOR EACH ROW
BEGIN
    IF NEW.nominal IS NULL OR NEW.nominal <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nominal topup harus lebih dari 0';
    END IF;
    IF NEW.status IS NULL THEN
        SET NEW.status = 'pending';
    END IF;
END$$

CREATE TRIGGER trg_uang_saku_topup_bu
BEFORE UPDATE ON uang_saku_topup
FOR EACH ROW
BEGIN
    IF NEW.nominal IS NULL OR NEW.nominal <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nominal topup harus lebih dari 0';
    END IF;

    -- Jika menyetujui: wajib approved_by, set approved_at otomatis
    IF NEW.status = 'approved' AND (NEW.approved_by IS NULL OR NEW.approved_by = 0) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'approved_by (id user admin) wajib diisi saat menyetujui';
    END IF;
    IF NEW.status = 'approved' AND (OLD.status <> 'approved') THEN
        SET NEW.approved_at = NOW();
        SET NEW.rejection_reason = NULL;
    END IF;

    -- Jika menolak: wajib ada alasan, kosongkan approved_by & approved_at
    IF NEW.status = 'rejected' AND (NEW.rejection_reason IS NULL OR NEW.rejection_reason = '') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'rejection_reason wajib diisi saat menolak';
    END IF;
    IF NEW.status = 'rejected' THEN
        SET NEW.approved_by = NULL;
        SET NEW.approved_at = NULL;
    END IF;

    -- Kembali ke pending membersihkan metadata
    IF NEW.status = 'pending' THEN
        SET NEW.approved_by = NULL;
        SET NEW.approved_at = NULL;
        SET NEW.rejection_reason = NULL;
    END IF;
END$$

DELIMITER ;
