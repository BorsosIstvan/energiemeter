<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Energiemeter & Timer Test</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1>Huidig Verbruik</h1>
        
        <!-- Hier tonen we de Watt-waarde uit PHP -->
        <div class="watt-display"><span id="stroomWaarde">--</span> W</div>
        
        <p>Status: <span id="status">Laden...</span></p>

        <!-- DE TIMER TELLER -->
        <div class="timer-box">
            Timer updates: <strong id="timerTeller">0</strong> keer uitgevoerd
        </div>
    </div>

    <script>
        // We maken een variabele aan die we elke seconde gaan ophogen
        let aantalTicks = 0;

        function updateScherm() {
            // 1. Hoog de teller op en toon hem op het scherm
            aantalTicks++;
            document.getElementById('timerTeller').innerText = aantalTicks;

            // 2. Vraag de echte MQTT-data op via PHP
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

        // Voer direct 1 keer uit bij het laden van de pagina
        updateScherm();

        // Start de herhaling: elke 1000ms (1 seconde)
        setInterval(function() {
            updateScherm();
        }, 1000);
    </script>

</body>
</html>
