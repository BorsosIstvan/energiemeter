<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live MQTT Energiemeter</title>
    <!-- Inladen van de officiële MQTT JavaScript bibliotheek -->
    <script src="https://cloudflare.com" type="text/javascript"></script>
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
            color: #2e7d32; /* Groen voor een duurzame look */
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Huidig Verbruik (MQTT)</h1>
        <!-- Hier tonen we de live waarde van de ESP -->
        <div class="watt-display"><span id="stroomWaarde">--</span> W</div>
        <p>Status: <span id="status">Verbinden met MQTT...</span></p>
    </div>

    <script>
        // 1. Genereer een unieke Client ID voor deze browser-tab
        const clientId = "Browser_Client_" + Math.random().toString(16).substr(2, 8);

        // 2. Maak verbinding met de WebSocket-poort (9001) van je broker
        // We gebruiken 'window.location.hostname' zodat het automatisch werkt op poci.n-soft.net
        const client = new Paho.MQTT.Client(window.location.hostname, 9001, clientId);

        // 3. Koppel de functies aan de gebeurtenissen
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        // 4. Maak daadwerkelijk verbinding
        client.connect({
            onSuccess: onConnect,
            onFailure: function(err) {
                document.getElementById('status').innerText = "Verbinding mislukt: " + err.errorMessage;
                document.getElementById('status').style.color = "red";
            }
        });

        // Functie die start als de browser succesvol is ingelogd op de MQTT server
        function onConnect() {
            document.getElementById('status').innerText = "Verbonden (Wachten op ESP32...)";
            document.getElementById('status').style.color = "blue";
            
            // Abonneer op het exacte topic van jouw ESP32
            client.subscribe("huis/meter/fase1");
        }

        // Functie die start als de verbinding onverwacht wegvalt
        function onConnectionLost(responseObject) {
            if (responseObject.errorCode !== 0) {
                document.getElementById('status').innerText = "Verbinding verloren";
                document.getElementById('status').style.color = "red";
            }
        }

        // DE MAGIE: Deze functie start AUTOMATISCH zodra de ESP32 een bericht stuurt!
        // Je hebt hier dus GEEN setInterval() meer nodig!
        function onMessageArrived(message) {
            // message.payloadString bevat de pure tekst (bijv. "450") van de ESP32
            document.getElementById('stroomWaarde').innerText = message.payloadString;
            
            document.getElementById('status').innerText = "Live verbonden";
            document.getElementById('status').style.color = "green";
        }
    </script>

</body>
</html>
