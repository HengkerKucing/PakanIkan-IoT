<?php
    $servername = "localhost";
    $username = "ukzgycqh_root";
    $password = "aldiganteng123";
    $dbname = "ukzgycqh_pakan_iwak";

    // Membuat koneksi ke database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Memeriksa koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Check if distance data is sent via GET request
    if (isset($_GET['jarak'])) {
        $jarak = $_GET['jarak'];

        // Insert distance data into the data_jarak table
        $sql_insert = "INSERT INTO data_jarak (jarak, tglData) VALUES ('$jarak', NOW())";
        if (mysqli_query($conn, $sql_insert)) {
            echo "Data jarak berhasil disimpan.";
        } else {
            echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
        }
    }

    // Query untuk mengambil data dari tabel binary_values
    $sql_binary = "SELECT * FROM binary_values ORDER BY id";
    $result_binary = mysqli_query($conn, $sql_binary);

    if (mysqli_num_rows($result_binary) > 0) {
        while($row_binary = mysqli_fetch_assoc($result_binary)) {
            echo $row_binary["binary_value"] . ",";
        }
    } else {
        echo "0 results";
    }

    echo "<br>"; // Menambahkan baris baru untuk memisahkan output

    // Query untuk mengambil data terbaru dari tabel data_jarak
    $sql_sensor = "SELECT jarak FROM data_jarak ORDER BY tglData DESC LIMIT 1";
    $result_sensor = mysqli_query($conn, $sql_sensor);

    if ($result_sensor) {
        $row_sensor = mysqli_fetch_assoc($result_sensor);
        echo "Jarak: " . $row_sensor["jarak"] . " cm";
    } else {
        echo "Tidak ada data sensor terbaru.";
    }

    // Menutup koneksi database
    mysqli_close($conn);
?>
