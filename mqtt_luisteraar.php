<?php
echo ('Hello');
file_put_contents('/var/www/html/HitData/live_watt.txt', 'Hooi');
// Maak verbinding met de lokale MQTT server
$client = new Mosquitto\Client();

$client->onConnect(function() use ($client) {
    // Abonneer op het topic van je ESP
    $client->subscribe('huis/meter/fase1', 0);
});

$client->onMessage(function($message) {
    // Zodra de ESP data stuurt, schrijf het direct naar een bestand
    file_put_contents('/var/www/html/HitData/live_watt.txt', $message->payload);
});

// Maak verbinding met je eigen server
$client->connect('poci.n-soft.net', 1883, 60);

// Blijf oneindig luisteren naar MQTT
$client->loopForever();
?>
