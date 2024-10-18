<?php
session_start();
include 'config.php'; // Verbind met de database

// Verwerken van de registratie
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg waarden van het formulier
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Eenvoudige validatie
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Vul alstublieft alle velden in.";
    } elseif ($password !== $confirm_password) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        // Hash het wachtwoord
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Opslaan in de database
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'user')");
            if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: profiel.php"); // Verwijs naar het profiel
                exit;
            } else {
                $error = "Er is een fout opgetreden. Probeer het opnieuw.";
            }
        } catch (PDOException $e) {
            // Foutafhandeling
            echo "Database error: " . $e->getMessage();
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Je kunt hier eventueel extra stijlen toevoegen */
    </style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
    <h1 class="text-4xl font-bold text-center">Registreren</h1>
    <form method="POST" action="" class="mt-5 max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
        <div class="mb-4">
            <label for="username" class="block text-lg font-medium">Gebruikersnaam</label>
            <input id="username" name="username" type="text" required class="border border-gray-300 rounded-md p-2 w-full" placeholder="Gebruikersnaam">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-lg font-medium">E-mailadres</label>
            <input id="email" name="email" type="email" required class="border border-gray-300 rounded-md p-2 w-full" placeholder="E-mailadres">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-lg font-medium">Wachtwoord</label>
            <input id="password" name="password" type="password" required class="border border-gray-300 rounded-md p-2 w-full" placeholder="Wachtwoord">
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block text-lg font-medium">Bevestig Wachtwoord</label>
            <input id="confirm_password" name="confirm_password" type="password" required class="border border-gray-300 rounded-md p-2 w-full" placeholder="Bevestig Wachtwoord">
        </div>
        <div>
            <input type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition" value="Registreren">
        </div>
        <?php if (!empty($error)): ?>
            <div class="mt-4 text-red-500 text-center"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>
    <p class="text-center mt-4">
        Heb je al een account? <a href="login.php" class="text-blue-500">Log hier in</a>
    </p>
</div>
</body>
</html>
