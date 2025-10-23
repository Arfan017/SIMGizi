<?php

function kirimNotifikasiKonfirmasi($id_distribusi, $nama_petugas, $tanggal, $jam, $sekolah, $jumlah, $catatan, $target)
{
    $token = "i3YewmMtn4fzNLRajzxg"; // ganti dengan API key dari Fonnte
    $token2 = "NkDiCDxucq2xUyq5sTCA"; // Minranda

    $message = "Yth. Adamin Kantor,\n\n" .
        "Dengan ini kami informasikan bahwa distribusi makanan bergizi telah *dikonfirmasi diterima* oleh pihak sekolah.\n\n" .
        "Detail distribusi:\n" .
        "🪪 ID Distribusi (*$id_distribusi*)\n" .
        "📅 Tanggal         : $jam | $tanggal\n" .
        "🏫 Sekolah         : $sekolah\n" .
        "🍱 Jumlah          : $jumlah porsi\n" .
        "📝 Catatan         : $catatan\n\n" .
        "Link Aplikasi Monitoring Gizi: http://SIMGizi.online/\n\n" .
        "Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.\n\n" .
        "_Salam hormat,_\n" .
        "$nama_petugas\n" .
        "(Admin Sekolah)";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.fonnte.com/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'target' => $target,
        'message' => $message,
    ]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: $token2"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}