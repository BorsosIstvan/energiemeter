<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Energiemeter via PHP-MQTT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: inline-block;
        }
        .watt-display {
            font-size: 48px;
            font-weight: bold;
            color: #d35400; /* Oranje look */
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Huidig Verbruik (MQTT -> PHP)</h1>
        <div class="watt-display"><span id="stroomWaarde">--</span> W</div>
        <p>Status: <span id="status">Laden...</span></p>
		        <!-- DE TIMER TELLER -->
        <div class="timer-box">
            Timer updates: <strong id="timerTeller">0</strong> keer uitgevoerd
        </div>
    </div>
	
	<?php require_once('mqtt_luisteraar.php'); ?>

    <script>
		// We maken een variabele aan die we elke seconde gaan ophogen
		let aantalTicks = 0;
        function updateScherm() {
			// 1. Hoog de teller op en toon hem op het scherm
            aantalTicks++;
            document.getElementById('timerTeller').innerText = aantalTicks;
            // Vraag de waarde op bij het php script
            fetch('geef_watt.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('stroomWaarde').innerText = data;
                    document.getElementById('status').innerText = "Live verbonden";
                    document.getElementById('status').style.color = "green";
                })
                .catch(error => {
                    document.getElementById('status').innerText = "Fout bij ophalen";
                    document.getElementById('status').style.color = "red";
                });
        }

        // Voer direct uit bij openen
        updateScherm();

        // Herhaal dit ELKE SECONDE (1000ms)
        setInterval(function() {
            updateScherm();
        }, 1000);
    </script>

</body>
</html>
