<?php

// Kirim notifikasi WA konfirmasi distribusi
function kirimNotifikasiKonfirmasi($id_distribusi, $nama_petugas, $tanggal, $jam, $sekolah, $jumlah, $catatan, $target)
{
    $token = "i3YewmMtn4fzNLRajzxg"; // ganti dengan API key dari Fonnte

    $message = "Yth. Adamin Kantor,\n\n" .
        "Dengan ini kami informasikan bahwa distribusi makanan bergizi telah *dikonfirmasi diterima* oleh pihak sekolah.\n\n" .
        "Detail distribusi:\n" .
        "🪪 ID Distribusi (*$id_distribusi*)\n" .
        "📅 Tanggal         : $jam | $tanggal\n" .
        "🏫 Sekolah         : $sekolah\n" .
        "🍱 Jumlah          : $jumlah porsi\n" .
        "📝 Catatan         : $catatan\n\n" .
        "Link Aplikasi Monitoring Gizi: https://monitgizi.my.id/\n\n" .
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: $token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// $message = "Yth. $nama_petugas,\n\n" .
//     "Dengan ini kami informasikan bahwa distribusi makanan bergizi telah *dikonfirmasi diterima* oleh pihak sekolah.\n\n" .
//     "Detail distribusi:\n" .
//     "📅 Tanggal     : $tanggal\n" .
//     "🏫 Sekolah     : $sekolah\n" .
//     "🍱 Jumlah      : $jumlah porsi\n" .
//     "📝 Catatan     : $catatan\n\n" .
//     "Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.\n" .
//     "_Salam hormat,_\n" .
//     "Admin Distribusi";

// $curl = curl_init();

// curl_setopt_array($curl, array(
//     CURLOPT_URL => 'https://api.fonnte.com/send',
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => array(
//         'target' => '081247796665',
//         'message' => $message,
//         'url' => 'https://example.com/image.jpg', //optional
//         'countryCode' => '62', //optional
//     ),
//     CURLOPT_HTTPHEADER => array(
//         'Authorization: i3YewmMtn4fzNLRajzxg' //change TOKEN to your actual token
//     ),
// ));

// $response = curl_exec($curl);
// if (curl_errno($curl)) {
//     $error_msg = curl_error($curl);
// }
// curl_close($curl);

// if (isset($error_msg)) {
//     echo $error_msg;
// }
// echo $response;
