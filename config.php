<?php
// Database configuratie
$host = 'localhost'; // Database host (meestal localhost)
$db_name = 'ontkooking'; // Naam van de database
$username = 'ontkooking'; // Vervang dit door je databasegebruikersnaam
$password = 'ontkooking2024'; // Vervang dit door je databasewachtwoord

// Maak de databaseverbinding
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Zet de foutmodus van PDO op Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
    exit(); // Stop de uitvoering als de verbinding mislukt
}
?>
