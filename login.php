<?php
include 'config.php';
session_start(); // Zorg ervoor dat je de sessie start bovenaan de pagina

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Controleer de inloggegevens (dit is slechts een voorbeeld)
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Sla user_id op in de sessie na succesvol inloggen
        $_SESSION['user_id'] = $user['user_id'];

        // Redirect de gebruiker naar de profielpagina
        header("Location: profiel.php");
        exit;
    } else {
        echo "Ongeldige inloggegevens";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookZ | Inloggen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary { background-color: #c2ad62; }
        .bg-secondary { background-color: #ffffff; }
        .bg-accent { background-color: #2b9e2a; }
        .text-accent { color: #ffffff; }
    </style>
</head>
<body class="bg-secondary text-gray-800">
<main class="container mx-auto mt-10">
    <h1 class="text-4xl font-bold text-center mb-6">Inloggen</h1>

    <form action="login.php" method="post" class="max-w-lg mx-auto bg-white p-8 rounded shadow-lg">
        <div class="mb-4">
            <label for="email" class="block text-gray-700">E-mail</label>
            <input type="email" id="email" name="email" class="border border-gray-300 p-2 w-full rounded mt-2" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Wachtwoord</label>
            <input type="password" id="password" name="password" class="border border-gray-300 p-2 w-full rounded mt-2" required>
        </div>
        <button type="submit" class="bg-accent text-white py-2 px-4 rounded w-full hover:bg-green-500 transition">
            Inloggen
        </button>
    </form>
</main>
</body>
</html>
