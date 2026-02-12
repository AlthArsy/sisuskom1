CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(32) NOT NULL UNIQUE,
  `nama` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Admin', 'Asesor', 'Assesi') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `users` (`nik`, `nama`, `password`, `role`) VALUES
('', 'Admin Utama', 'Admin1234', 'Admin'),
('', 'Admin DUA', 'Admin1234', 'Admin'),
('1', 'Asesor Satu', 'Admin1234', 'Asesor'),
('2', 'Asesor Dua', 'Admin1234', 'Asesor'),
('3', 'Assesi Satu', 'Admin1234', 'Assesi'),
('4', 'Assesi Dua', 'Admin1234', 'Assesi');

 CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Asesor','Asesi') NOT NULL,
  `id_referensi` int(11) DEFAULT NULL
);

INSERT INTO `users` (`username`, `password`, `role`, `id_referensi`) VALUES
('admin1', 'Admin1234', 'Admin', NULL),
('admin2', 'Admin1234', 'Admin', NULL),
('asesor1', 'Admin1234', 'Asesor', NULL),
('asesor2', 'Admin1234', 'Asesor', NULL),
('assesi1', 'Admin1234', 'Assesi', NULL),
('assesi2', 'Admin1234', 'Assesi', NULL);