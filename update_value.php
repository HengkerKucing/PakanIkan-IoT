<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'pakan_iwak';

$koneksi = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["value"])) {
    $id = intval($_POST["id"]);
    $value = intval($_POST["value"]);

    $update_query = "UPDATE binary_values SET binary_value = '$value' WHERE id = $id";
    if (mysqli_query($koneksi, $update_query)) {
        echo "Data berhasil diperbarui.";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>
