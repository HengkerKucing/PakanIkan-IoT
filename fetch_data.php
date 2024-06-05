<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'pakan_iwak';

$koneksi = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$query = "SELECT id, binary_value FROM binary_values";
$result = mysqli_query($koneksi, $query);

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

        $jarak = $jarak_row['jarak'] ?? 'Tidak ada data';
        $tglData = $jarak_row['tglData'] ?? 'Tidak ada data';

        echo "<div class='meja'>";
        echo "Meja Ke-$id: " . ($binary_value ? '<span style="color: green;">Tersedia</span>' : '<span style="color: red;">Tidak Tersedia</span>') . "<br>";
        echo "Jarak: " . $jarak . " cm<br>";
        echo "Waktu Pengukuran: " . $tglData . "<br>";
        echo "<button onclick='sendData($id, 1)'>Pesanan-$id</button>";
        echo "</div>";
    }
} else {
    echo "Tidak ada data ditemukan dalam database.";
}

mysqli_close($koneksi);
?>
