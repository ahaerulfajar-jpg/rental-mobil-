-- Tabel galeri gambar per mobil (eksterior, interior, mesin, lainnya)
-- Jalankan sekali di MySQL (phpMyAdmin atau CLI)

CREATE TABLE IF NOT EXISTS `mobil_gambar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobil_id` int(11) NOT NULL,
  `kategori` enum('eksterior','interior','mesin','lainnya') DEFAULT 'eksterior',
  `nama_file` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `url_gambar` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mobil_id` (`mobil_id`),
  CONSTRAINT `mobil_gambar_ibfk_1` FOREIGN KEY (`mobil_id`) REFERENCES `mobil` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 