---------------------------------------------------------------------------------------------------------------

CREATE TABLE tb_skema (
    id_skema INT AUTO_INCREMENT PRIMARY KEY,
    nomor_skema VARCHAR(100) NOT NULL,
    judul_skema VARCHAR(100) NOT NULL,
    standar_kompetensi_kerja VARCHAR(100) NOT NULL,
    id_asesor INT NOT NULL
);

CREATE TABLE tb_unit_kompetensi (
    id_unit INT AUTO_INCREMENT PRIMARY KEY,
    id_skema INT NOT NULL,
    kode_unit VARCHAR(100) NOT NULL,
    judul_unit TEXT NOT NULL
);

CREATE TABLE tb_elemen (
    id_elemen INT AUTO_INCREMENT PRIMARY KEY,
    id_unit INT NOT NULL,
    nama_elemen TEXT NOT NULL
);

CREATE TABLE tb_kuk (
    id_kuk INT AUTO_INCREMENT PRIMARY KEY,
    id_elemen INT NOT NULL,
    kuk TEXT NOT NULL
);

INSERT INTO tb_skema (nomor_skema, judul_skema, standar_kompetensi_kerja, id_asesor) VALUES
('SKM-001', 'Skema Web Programming', 'SKKNI 2023', 1),
('SKM-002', 'Skema Desain Grafis', 'SKKNI 2022', 2);

INSERT INTO tb_unit_kompetensi (id_skema, kode_unit, judul_unit) VALUES
(1, 'WP-01', 'Dasar Pemrograman Web'),
(1, 'WP-02', 'Pengembangan Frontend'),
(2, 'DG-01', 'Prinsip Desain Dasar');

INSERT INTO tb_elemen (id_unit, nama_elemen) VALUES
(1, 'Mengenal HTML dan CSS'),
(2, 'Framework JavaScript'),
(3, 'Layout dan Komposisi');

INSERT INTO tb_kuk (id_elemen, kuk) VALUES
(1, 'Menjelaskan struktur dasar HTML'),
(1, 'Membuat layout sederhana menggunakan CSS'),
(2, 'Mengimplementasikan React.js'),
(3, 'Membuat poster mengacu prinsip grid');



-----
-----