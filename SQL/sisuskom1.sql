CREATE TABLE FR1a (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    no_ktp INT AUTO_INCREMENT PRIMARY KEY,
    tempat_lahir VARCHAR(50) NOT NULL,
    tgl_lahir DATE NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    kebangsaan VARCHAR(50) DEFAULT 'Indonesia',
    alamat_rumah TEXT NOT NULL,
    kode_pos VARCHAR(10),
    phone_rumah VARCHAR(20),
    phone_kantor VARCHAR(20),
    hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    kualifikasi_pendidikan VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE FR1b (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_institusi VARCHAR(100),
    jabatan VARCHAR(100),
    alamat_kantor TEXT,
    kode_pos_kantor VARCHAR(10),
    telp_kantor VARCHAR(20),
    fax VARCHAR(20),
    email_kantor VARCHAR(100)
);
--================================================
CREATE TABLE FR2_Bagian2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor INT NOT NULL,
    tujuan_asesmen VARCHAR(255) NOT NULL
);
--================================================
CREATE TABLE FR3R (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemohon_id INT(11) NOT NULL,
    no_urut INT NOT NULL,
    nama_bukti TEXT NOT NULL, 
    ada BOOLEAN DEFAULT FALSE,
    tidak_ada BOOLEAN DEFAULT FALSE,
    memenuhi_syarat BOOLEAN DEFAULT FALSE,
    tidak_memenuhi_syarat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE FR3B (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemohon_id INT(11) NOT NULL,
    no_urut INT NOT NULL, 
    nama_bukti TEXT NOT NULL, 
    ada BOOLEAN DEFAULT FALSE,
    tidak_ada BOOLEAN DEFAULT FALSE,
    memenuhi_syarat BOOLEAN DEFAULT FALSE,
    tidak_memenuhi_syarat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE FR3R (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pemohon_id INT(11) NOT NULL,
    status_rekomendasi ENUM('Diterima', 'Tidak diterima') NOT NULL,
    nama_pemohon VARCHAR(100) NOT NULL,
    tanda_tangan_pemohon DATE,
    catatan_pemohon TEXT,
    tanda_tangan_admin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE FR3Pd (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    pemohon_id INT(11) NOT NULL,
    no_urut INT(2) NOT NULL,
    nama_bukti TEXT NOT NULL,
    ada BOOLEAN DEFAULT FALSE,
    tidak_ada BOOLEAN DEFAULT FALSE,
    memenuhi_syarat BOOLEAN DEFAULT FALSE,
    tidak_memenuhi_syarat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE FR3Bk (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    pemohon_id INT(11) NOT NULL,
    no_urut INT(2) NOT NULL,
    nama_bukti VARCHAR(255) NOT NULL,
    ada BOOLEAN DEFAULT FALSE,
    tidak_ada BOOLEAN DEFAULT FALSE,
    memenuhi_syarat BOOLEAN DEFAULT FALSE,
    tidak_memenuhi_syarat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE FR3Reg  (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    pemohon_id INT(11) NOT NULL,
    status_rekomendasi ENUM('Diterima', 'Tidak diterima') NOT NULL,
    nama_pemohon VARCHAR(100) NOT NULL,
    tanda_tangan_pemohon DATE,
    catatan_pemohon TEXT,
    tanda_tangan_admin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)


