-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 01:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sim_gizi`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GenerateDummyDistribusi` (IN `num_rows` INT)   BEGIN
    -- Deklarasi variabel
    DECLARE v_counter INT DEFAULT 0;
    DECLARE v_petugas_id INT;
    DECLARE v_sekolah_id INT;
    DECLARE v_lokasi_gps VARCHAR(255);
    DECLARE v_tanggal DATE;
    DECLARE v_jam TIME;
    DECLARE v_jumlah INT;
    DECLARE v_foto VARCHAR(255);

    -- Loop untuk membuat data sebanyak num_rows
    WHILE v_counter < num_rows DO
        -- 1. Ambil ID Petugas Distribusi secara acak
        -- GANTI 'admin distribusi' dengan nama role yang benar jika berbeda
        SELECT id_users INTO v_petugas_id
        FROM tb_users
        WHERE role = 'admin_distribusi' -- Sesuaikan role jika perlu
        ORDER BY RAND() -- Ambil secara acak
        LIMIT 1;

        -- 2. Ambil ID Sekolah Tujuan secara acak
        SELECT id_sekolah INTO v_sekolah_id
        FROM tb_sekolah
        ORDER BY RAND()
        LIMIT 1;

        -- 3. Ambil lokasi_gps berdasarkan id_sekolah yang dipilih
        SELECT lokasi_gps INTO v_lokasi_gps
        FROM tb_sekolah
        WHERE id_sekolah = v_sekolah_id;

        -- 4. Generate Tanggal acak (misal dalam 60 hari terakhir)
        -- SET v_tanggal = DATE_SUB(CURDATE(), INTERVAL FLOOR(RAND() * 60) DAY);
		SET v_tanggal = CURDATE();

        -- 5. Generate Jam acak
        SET v_jam = SEC_TO_TIME(FLOOR(RAND() * 86400)); -- Angka acak antara 00:00:00 - 23:59:59

        -- 6. Generate Jumlah acak (misal antara 50 - 300)
        SET v_jumlah = FLOOR(RAND() * (300 - 50 + 1)) + 50;

        -- 7. Generate nama file Foto dummy
        SET v_foto = CONCAT('dummy_foto.jpg');

        -- 8. Insert data ke tb_distribusi
        INSERT INTO tb_distribusi (
            id_petugas_distribusi,
            id_sekolah_tujuan,
            tanggal,
            jam,
            nama_barang,
            jumlah,
            jumlah_habis,        -- Default 0
            jumlah_kembali,      -- Default 0
            lokasi_gps,          -- Diambil dari tb_sekolah
            lokasi_terkini,      -- Default NULL
            foto,                -- Nama file dummy
            status_konfirmasi,   -- Default '0'
            status_pengiriman,   -- Default '0'
            status_evaluasi,     -- Default 0
            jam_berangkat,       -- Default NULL
            jam_tiba,            -- Default NULL
            gps_awal             -- Default NULL
        ) VALUES (
            v_petugas_id,
            v_sekolah_id,
            v_tanggal,
            v_jam,
            'Makanan',           -- Default 'Makanan'
            v_jumlah,
            0,                   -- jumlah_habis
            0,                   -- jumlah_kembali
            v_lokasi_gps,
            NULL,                -- lokasi_terkini
            v_foto,
            '0',                 -- status_konfirmasi (ENUM)
            '0',                 -- status_pengiriman (ENUM)
            0,                   -- status_evaluasi (INT)
            NULL,                -- jam_berangkat
            NULL,                -- jam_tiba
            NULL                 -- gps_awal
        );

        -- Naikkan counter
        SET v_counter = v_counter + 1;
    END WHILE;

    -- Tampilkan pesan selesai (opsional)
    SELECT CONCAT(num_rows, ' baris data dummy berhasil ditambahkan ke tb_distribusi.') AS Status;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_bahan_makanan`
--

CREATE TABLE `tb_bahan_makanan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(225) NOT NULL,
  `kategori` enum('KH','Protein','Sayur','Buah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_bahan_makanan`
--

INSERT INTO `tb_bahan_makanan` (`id_bahan`, `nama_bahan`, `kategori`) VALUES
(1, 'Nasi Putih', 'KH'),
(2, 'Nasi Kuning', 'KH'),
(3, 'Roti Isi', 'KH'),
(4, 'Nugget Ikan Krispi (Pakai tepung Roti)', 'Protein'),
(5, 'Ikan Suwir', 'Protein'),
(6, 'Ayam saos asam manis', 'Protein'),
(7, 'Bakso ikan saos', 'Protein'),
(8, 'Ayam goreng', 'Protein'),
(9, 'Tahu Goreng', 'Protein'),
(10, 'Tempe Goreng', 'Protein'),
(11, 'Tahu dadu kecap', 'Protein'),
(12, 'Tumis pepaya muda dan wortel', 'Sayur'),
(13, 'Tumis Singkong', 'Sayur'),
(14, 'Tumis Labisiam', 'Sayur'),
(15, 'Tumis kol, wortel, pakcoy', 'Sayur'),
(16, 'Tumis kacang panjang dan wortel', 'Sayur'),
(17, 'Salak', 'Buah'),
(18, 'Pisang', 'Buah'),
(19, 'Jeruk', 'Buah'),
(20, 'Pepaya', 'Buah'),
(21, 'Melon', 'Buah');

-- --------------------------------------------------------

--
-- Table structure for table `tb_distribusi`
--

CREATE TABLE `tb_distribusi` (
  `id_distribusi` int(11) NOT NULL,
  `id_petugas_distribusi` int(11) NOT NULL,
  `id_sekolah_tujuan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `jumlah_habis` int(11) NOT NULL,
  `jumlah_kembali` int(11) NOT NULL,
  `lokasi_gps` varchar(255) DEFAULT NULL,
  `lokasi_terkini` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status_konfirmasi` enum('0','1') NOT NULL DEFAULT '0',
  `status_pengiriman` enum('0','1','2') NOT NULL DEFAULT '0',
  `status_evaluasi` int(11) NOT NULL DEFAULT 0,
  `jam_berangkat` time DEFAULT NULL,
  `jam_tiba` time DEFAULT NULL,
  `gps_awal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_evaluasi`
--

CREATE TABLE `tb_evaluasi` (
  `id_evaluasi` int(11) NOT NULL,
  `id_distribusi` int(11) NOT NULL,
  `catatan` varchar(225) NOT NULL,
  `status_distribusi` varchar(225) NOT NULL,
  `id_admin_kantor` int(11) NOT NULL,
  `tanggal_evaluasi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `tb_evaluasi`
--
DELIMITER $$
CREATE TRIGGER `update_status_evaluasi` AFTER INSERT ON `tb_evaluasi` FOR EACH ROW BEGIN
    UPDATE tb_distribusi
    SET status_evaluasi = '1'
    WHERE id_distribusi = NEW.id_distribusi;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kantor`
--

CREATE TABLE `tb_kantor` (
  `id_kantor` int(11) NOT NULL,
  `nama_kantor` varchar(225) NOT NULL,
  `lokasi` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kantor`
--

INSERT INTO `tb_kantor` (`id_kantor`, `nama_kantor`, `lokasi`) VALUES
(1, 'Kantor Kencana', '-2.9303223324483785, 132.29353385012186');

-- --------------------------------------------------------

--
-- Table structure for table `tb_konfirmasi_distribusi`
--

CREATE TABLE `tb_konfirmasi_distribusi` (
  `id_konfirmasi` int(11) NOT NULL,
  `id_distribusi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `status` enum('Terima','Tolak') NOT NULL,
  `jumlah_diterima` int(11) NOT NULL,
  `jumlah_habis` int(11) NOT NULL,
  `jumlah_kembali` int(11) NOT NULL,
  `catatan` varchar(255) NOT NULL,
  `gambar` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `tb_konfirmasi_distribusi`
--
DELIMITER $$
CREATE TRIGGER `konfirmasi_distribusi_after_insert` AFTER INSERT ON `tb_konfirmasi_distribusi` FOR EACH ROW BEGIN
    UPDATE tb_distribusi
    SET 
    	jumlah_habis = NEW.jumlah_habis,
        jumlah_kembali = NEW.jumlah_kembali,
    	status_konfirmasi = '1',
	    status_pengiriman = '2'
    WHERE id_distribusi = NEW.id_distribusi;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu_harian`
--

CREATE TABLE `tb_menu_harian` (
  `id_menu` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_bahan_kh` int(11) NOT NULL,
  `id_bahan_protein1` int(11) NOT NULL,
  `id_bahan_protein2` int(11) NOT NULL,
  `id_bahan_sayur` int(11) NOT NULL,
  `id_bahan_buah` int(11) NOT NULL,
  `tambahan` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_sekolah`
--

CREATE TABLE `tb_sekolah` (
  `id_sekolah` int(11) NOT NULL,
  `tingkat_sekolah` enum('PAUD','TK','SD','SMP','Kantor') NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL,
  `jumlah_siswa` int(11) DEFAULT NULL,
  `lokasi_gps` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_sekolah`
--

INSERT INTO `tb_sekolah` (`id_sekolah`, `tingkat_sekolah`, `nama_sekolah`, `jumlah_siswa`, `lokasi_gps`) VALUES
(1, 'TK', 'TK Pembina', 42, '-2.9266725527247353, 132.29280553399715'),
(2, 'TK', 'TK Kuncup Harapan', 45, '-2.9279195126799054, 132.29063453621632'),
(3, 'TK', 'TK ABA 1', 55, '-2.925725445039725, 132.26755138096564'),
(4, 'TK', 'TK Garlos', 35, '-2.9269680648016814, 132.2903038008231'),
(5, 'TK', 'TK Baitul makmur', 64, '-2.9270410641011537, 132.28936524048504'),
(6, 'PAUD', 'Paud Khalifah', 70, '-2.930552174980942, 132.29442995224127'),
(7, 'PAUD', 'Paud Islam', 35, '-2.9283298643681928, 132.29145991349802'),
(8, 'SD', 'SD Ypkk Plahar', 50, '-2.930935338250607, 132.29349995039297'),
(9, 'SD', 'SD Inpres 2 Wagom', 299, '-2.9299554120001567, 132.29750963874778'),
(10, 'SD', 'SD Inpres 3 Wagom', 169, '-2.920805714007388, 132.2929090927219'),
(11, 'SD', 'MI Muhammadiyah', 162, '-2.930913264903812, 132.29597021164957'),
(12, 'SD', 'SD Gempa', 50, '-2.9268493919400758, 132.28096178065599'),
(13, 'SMP', 'SMP 5 Satu Atap', 141, '-2.9203432649220162, 132.2930194099122'),
(14, 'SMP', 'Mts Muhammadiyah', 52, '-2.9317982680860597, 132.31268262821231'),
(15, 'SMP', 'SMA YPK Fakfak', 275, '-2.9288201348311294, 132.29757130000448'),
(16, 'SMP', 'MTSN FAKFAK', 570, '-2.9314107123609676, 132.29664863505096'),
(17, 'Kantor', 'Kantor Kencana ', 1, '-2.9303223324483785, 132.29353385012186');

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_harian`
--

CREATE TABLE `tb_stok_harian` (
  `id_stok` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_total` int(11) NOT NULL,
  `jumlah_sisa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id_users` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` enum('admin_kantor','admin_sekolah','admin_distribusi') NOT NULL,
  `nama` varchar(225) NOT NULL,
  `id_asal_sekolah` int(11) DEFAULT NULL,
  `no_hp` varchar(225) NOT NULL,
  `status` enum('Tidak Aktif','Aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`id_users`, `username`, `password`, `role`, `nama`, `id_asal_sekolah`, `no_hp`, `status`) VALUES
(1, 'kantor', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_kantor', 'Kantor Kencana', 17, '081247796665', 'Aktif'),
(2, 'distribusi', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_distribusi', 'Distirbusi Kencana', NULL, '081247796665', 'Aktif'),
(3, 'TK_Pembina', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'TK Pembina', 1, '081247796664', 'Aktif'),
(4, 'TK_Kuncup_Harapan', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'TK Kuncup Harapan', 2, '6281111000002', 'Aktif'),
(5, 'TK_ABA_1', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'TK ABA 1', 3, '6281111000003', 'Aktif'),
(6, 'TK_Garlos', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'TK Garlos', 4, '6281111000004', 'Aktif'),
(7, 'TK_Baitul_makmur', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'TK Baitul makmur', 5, '6281111000005', 'Aktif'),
(8, 'Paud_Khalifah', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'Paud Khalifah', 6, '6281111000006', 'Aktif'),
(9, 'Paud_Islam', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'Paud Islam', 7, '6281111000007', 'Aktif'),
(10, 'SD_Ypkk_Plahar', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SD Ypkk Plahar', 8, '6281111000008', 'Aktif'),
(11, 'SD_Inpres_2_Wagom', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SD Inpres 2 Wagom', 9, '6281111000009', 'Aktif'),
(12, 'SD_Inpres_3_Wagom', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SD Inpres 3 Wagom', 10, '6281111000010', 'Aktif'),
(13, 'MI_Muhammadiyah', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'MI Muhammadiyah', 11, '6281111000011', 'Aktif'),
(14, 'SD_Gempa', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SD Gempa', 12, '6281111000012', 'Aktif'),
(15, 'SMP_5_Satu_Atap', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SMP 5 Satu Atap', 13, '6281111000013', 'Aktif'),
(16, 'Mts_Muhammadiyah', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'Mts Muhammadiyah', 14, '6281111000014', 'Aktif'),
(17, 'SMA_YPK_Fakfak', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'SMA YPK Fakfak', 15, '6281111000015', 'Aktif'),
(18, 'MTSN_FAKFAK', '$2y$10$gO27qQ6RKR1T6wzUHf2U4Ov2FhLfY3rTUaIgCmJfvRrYDBKDJUXxC', 'admin_sekolah', 'MTSN FAKFAK', 16, '6281111000016', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bahan_makanan`
--
ALTER TABLE `tb_bahan_makanan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `tb_distribusi`
--
ALTER TABLE `tb_distribusi`
  ADD PRIMARY KEY (`id_distribusi`),
  ADD KEY `id_petugas_distribusi` (`id_petugas_distribusi`),
  ADD KEY `id_sekolah` (`id_sekolah_tujuan`);

--
-- Indexes for table `tb_evaluasi`
--
ALTER TABLE `tb_evaluasi`
  ADD PRIMARY KEY (`id_evaluasi`),
  ADD KEY `id_distribusi` (`id_distribusi`);

--
-- Indexes for table `tb_kantor`
--
ALTER TABLE `tb_kantor`
  ADD PRIMARY KEY (`id_kantor`);

--
-- Indexes for table `tb_konfirmasi_distribusi`
--
ALTER TABLE `tb_konfirmasi_distribusi`
  ADD PRIMARY KEY (`id_konfirmasi`),
  ADD KEY `id_distribusi` (`id_distribusi`);

--
-- Indexes for table `tb_menu_harian`
--
ALTER TABLE `tb_menu_harian`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tb_sekolah`
--
ALTER TABLE `tb_sekolah`
  ADD PRIMARY KEY (`id_sekolah`);

--
-- Indexes for table `tb_stok_harian`
--
ALTER TABLE `tb_stok_harian`
  ADD PRIMARY KEY (`id_stok`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_bahan_makanan`
--
ALTER TABLE `tb_bahan_makanan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_distribusi`
--
ALTER TABLE `tb_distribusi`
  MODIFY `id_distribusi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_evaluasi`
--
ALTER TABLE `tb_evaluasi`
  MODIFY `id_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_kantor`
--
ALTER TABLE `tb_kantor`
  MODIFY `id_kantor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_konfirmasi_distribusi`
--
ALTER TABLE `tb_konfirmasi_distribusi`
  MODIFY `id_konfirmasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_menu_harian`
--
ALTER TABLE `tb_menu_harian`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_sekolah`
--
ALTER TABLE `tb_sekolah`
  MODIFY `id_sekolah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_stok_harian`
--
ALTER TABLE `tb_stok_harian`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
