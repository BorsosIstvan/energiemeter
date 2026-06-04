<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Energiemeter & Timer Test</title>
    <style>
        body {
			font-family: 'Segoe UI', sans-serif; 
			margin: 0; background-color: #0b0c10; 
			color: #ffffff; display: flex; justify-content: center; 
			min-height: 100vh; 
			}
		.container { width: 100%; max-width: 450px; 
			background: linear-gradient(180deg, #160c1b 0%, #0b0c10 100%); 
			padding: 25px 20px; 
			box-sizing: border-box; 
			display: flex; flex-direction: column; 
			justify-content: space-between; 
			box-shadow: 0 0 30px rgba(0,0,0,0.6); 
			text-align: center; 
        }
        .watt-display {
            font-size: 48px;
            font-weight: bold;
            color: #d35400;
            margin: 20px 0;
        }
        .timer-box {
            margin-top: 20px;
            padding: 10px;
            background-color: #eee;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
        }
    </style>
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
                    document.getElementById('stroomWaarde').innerText = aantalTicks;
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
