<?php
include '../config.php';
header('Content-Type: application/json');

// Ambil data dari form
$id_petugas = $_POST['id_petugas_distribusi'] ?? null;
$nama_barang = $_POST['nama_barang'] ?? 'Makanan'; // Tetap ambil nama_barang
$id_sekolah = $_POST['sekolah'] ?? null;
$jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0;
$tanggal = $_POST['tanggal'] ?? null;
$jam = $_POST['jam'] ?? null;
$lokasi = $_POST['lokasi'] ?? null;

// --- 1. AMBIL ID_MENU BARU DARI POST ---
$id_menu = $_POST['id_menu'] ?? null; // Ambil id_menu yang dikirim dari form

// Mulai Transaksi Database
mysqli_begin_transaction($conn);

try {
    // Validasi input dasar
    if (empty($id_petugas) || empty($id_sekolah) || empty($tanggal) || empty($jam) || $jumlah <= 0 || empty($id_menu)) {
        throw new Exception("Data input tidak lengkap. Pastikan tanggal, sekolah, jumlah, dan menu sudah dipilih.");
    }

    // --- 2. PERBAIKAN GET_RESULT UNTUK CEK STOK ---
    $stmt_cek = $conn->prepare("SELECT jumlah_sisa FROM tb_stok_harian WHERE tanggal = ? FOR UPDATE");
    if ($stmt_cek === false) {
        throw new Exception("Prepare statement cek stok gagal: " . $conn->error);
    }

    $stmt_cek->bind_param("s", $tanggal);
    $stmt_cek->execute();
    $stmt_cek->store_result(); // Simpan hasil

    if ($stmt_cek->num_rows === 0) {
        $stmt_cek->close();
        throw new Exception("Stok untuk tanggal $tanggal belum diinput.");
    }

    $stmt_cek->bind_result($jumlah_sisa_dari_db); // Ikat hasil
    $stmt_cek->fetch(); // Ambil nilai
    $stok_sisa = (int)$jumlah_sisa_dari_db;
    $stmt_cek->close();
    // --- Akhir Perbaikan get_result() ---

    if ($jumlah > $stok_sisa) {
        throw new Exception("Jumlah distribusi ($jumlah) melebihi sisa stok ($stok_sisa).");
    }

    // Update stok (Tidak berubah)
    $stmt_update = $conn->prepare("UPDATE tb_stok_harian SET jumlah_sisa = jumlah_sisa - ? WHERE tanggal = ?");
    $stmt_update->bind_param("is", $jumlah, $tanggal);
    if (!$stmt_update->execute()) {
        $stmt_update->close();
        throw new Exception("Gagal mengupdate stok harian.");
    }
    $stmt_update->close();

    // --- 3. PERBAIKAN UPLOAD FOTO (AGAR TRANSAKSI ROLLBACK) ---
    $nama_foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        // Validasi ekstensi dasar
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("Format file foto tidak diizinkan.");
        }

        $nama_foto = 'distribusi_' . time() . '.' . $ext;
        $target = '../../uploads/' . $nama_foto;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            // Ganti echo/exit dengan throw agar transaksi di-rollback
            throw new Exception("Gagal upload foto!");
        }
    } else {
        // Ganti echo/exit dengan throw agar transaksi di-rollback
        throw new Exception("Foto wajib diupload!");
    }
    // --- Akhir Perbaikan Upload ---

    // --- 4. UBAH QUERY INSERT (Tambahkan id_menu) ---
    $stmt_insert = $conn->prepare(
        "INSERT INTO tb_distribusi (
            id_petugas_distribusi, tanggal, jam, nama_barang, jumlah, 
            id_sekolah_tujuan, id_menu, foto, lokasi_gps
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)" // Sekarang ada 9 placeholder
    );
    if ($stmt_insert === false) {
        throw new Exception("Prepare statement insert gagal: " . $conn->error);
    }

    // --- 5. UBAH BIND_PARAM (Tambahkan $id_menu) ---
    // Tipe data baru: "isssiiiss" (9 parameter)
    $stmt_insert->bind_param(
        "isssiiiss",
        $id_petugas,
        $tanggal,
        $jam,
        $nama_barang, // Tetap ada
        $jumlah,
        $id_sekolah,
        $id_menu, // Parameter baru
        $nama_foto,
        $lokasi
    );

    if (!$stmt_insert->execute()) {
        $stmt_insert->close();
        throw new Exception("Gagal menyimpan data distribusi: " . $stmt_insert->error);
    }
    $stmt_insert->close();

    // Jika semua berhasil, commit transaksi
    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'Data distribusi berhasil disimpan.']);
} catch (Exception $e) {
    // Jika ada error, rollback semua perubahan
    mysqli_rollback($conn);
    http_response_code(400); // Kirim status error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Selalu tutup koneksi
    if (isset($conn)) {
        $conn->close();
    }
}
// Hapus $conn->close(); ganda di luar blok
