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
            document.getElementById('data-container').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
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
