<?php

$dbhost = 'mariadb';
$dbname = 'snapmarket';
$dbuser = 'root';
$dbpass = 'password';

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kan geen verbinding maken met de database.");
}
