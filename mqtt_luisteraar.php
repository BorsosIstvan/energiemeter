<?php
echo ('Hello');
// Probeer nu het bestand te schrijven
if (file_put_contents('/var/www/html/HitData/live_watt.txt', 'Hooi') === false) {
    echo "Fout: Kan niet schrijven naar het bestand. Bestaat de map wel en heeft Apache rechten?<br>";
} else {
    echo "Succes: 'Hooi' is geschreven!<br>";
}
// Maak verbinding met de lokale MQTT server
echo('probeer nieuwe client maken');
$client = new Mosquitto\Client();
echo('new client gemaakt');

$client->onConnect(function() use ($client) {
    // Abonneer op het topic van je ESP
    $client->subscribe('huis/meter/fase1', 0);
	echo('subscribed');
});

$client->onMessage(function($message) {
    // Zodra de ESP data stuurt, schrijf het direct naar een bestand
    file_put_contents('/var/www/html/HitData/live_watt.txt', $message->payload);
	echo($message->payload);
});

// Maak verbinding met je eigen server
$client->connect('poci.n-soft.net', 1883, 60);

// Blijf oneindig luisteren naar MQTT
$client->loopForever();
?>
