<?php
$host = 'localhost';
$username = 'ukzgycqh_root';
$password = 'aldiganteng123';
$database = 'ukzgycqh_pakan_iwak';

$koneksi = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    die(json_encode(['error' => 'Koneksi database gagal: ' . mysqli_connect_error()]));
}

$query = "SELECT id, binary_value FROM binary_values";
$result = mysqli_query($koneksi, $query);

$data = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $binary_value = $row['binary_value'];

        // Ambil data terbaru dari tabel data_jarak berdasarkan id terbesar
        $jarak_query = "
            SELECT jarak, tglData 
            FROM data_jarak 
            ORDER BY id DESC 
            LIMIT 1
        ";
        $jarak_result = mysqli_query($koneksi, $jarak_query);
        $jarak_row = mysqli_fetch_assoc($jarak_result);

        $jarak = $jarak_row['jarak'] ?? null;
        $tglData = $jarak_row['tglData'] ?? null;

        $data[] = [
            'id' => $id,
            'binary_value' => $binary_value,
            'jarak' => $jarak,
            'tglData' => $tglData
        ];
    }
} else {
    $data['message'] = 'Tidak ada data ditemukan dalam database.';
}

mysqli_close($koneksi);

header('Content-Type: application/json');
echo json_encode($data);
?>
