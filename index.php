<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pemberi Makan Ikan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" id="data-container">
    <!-- Data akan dimuat di sini melalui AJAX -->
</div>

<script>
function fetchData() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_data.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            console.log("Response Text: ", xhr.responseText); // Logging untuk respons mentah
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.error) {
                    document.getElementById('data-container').innerHTML = data.error;
                    return;
                }

                var content = '';
                data.forEach(function(item) {
                    var jarak = item.jarak !== null ? item.jarak : 'Tidak ada data';
                    var tglData = item.tglData !== null ? item.tglData : 'Tidak ada data';
                    var persentase = jarak !== 'Tidak ada data' ? calculatePercentage(jarak) : 'N/A';

                    content += "<div class='meja'>";
                    content += "<div class='data-box'>Jarak: " + jarak + " cm</div>";
                    content += "<div class='data-box'>Persentase: " + persentase + "%</div>";
                    content += "<div class='data-box'>Waktu Pengukuran: " + tglData + "</div>";
                    content += "<button onclick='sendData(" + item.id + ", 1)'>Beri Makan</button>";
                    content += "</div>";
                });
                document.getElementById('data-container').innerHTML = content;
            } catch (e) {
                console.error("Error parsing JSON: ", e);
                document.getElementById('data-container').innerHTML = 'Error parsing data';
            }
        } else {
            console.error("Request failed. Status: " + xhr.status);
            document.getElementById('data-container').innerHTML = 'Failed to fetch data';
        }
    };
    xhr.onerror = function() {
        console.error("Request failed due to a network error.");
        document.getElementById('data-container').innerHTML = 'Network error';
    };
    xhr.send();
}

function calculatePercentage(jarak) {
    var maxDistance = 13.89; // 13.89 cm adalah 100%
    var percentage = ((maxDistance - jarak) / maxDistance) * 100;
    return percentage.toFixed(2); // mengembalikan dua angka di belakang koma
}

function sendData(id, value) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_value.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id=" + id + "&value=" + value);

    if (value === 1) {
        setTimeout(function() {
            sendData(id, 0);
        }, 1100);
    }
}

// Fetch data immediately on page load
fetchData();

// Set interval to fetch data every 1 minute (60000 milliseconds)
setInterval(fetchData, 60000);
</script>

</body>
</html>
