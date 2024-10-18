<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php'; // Zorg ervoor dat de databaseverbinding werkt

// Foutmeldingen inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect naar login als niet ingelogd
    exit;
}

// Haal gebruikersgegevens op
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, date_joined FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Geen gebruiker gevonden.";
    exit;
}

// Bijwerken van gebruikersinformatie
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Update de gegevens in de database
    $update_stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE user_id = :user_id");
    $update_stmt->execute(['username' => $new_username, 'email' => $new_email, 'user_id' => $user_id]);

    // Herlaad de pagina om de wijzigingen te zien
    header("Location: profiel.php");
    exit;
}

// Toevoegen van een favoriet recept
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $recipe_id = $_POST['recipe_id'];

    // Voeg het recept toe aan de favorieten
    $add_stmt = $pdo->prepare("INSERT INTO favorieten (user_id, recept_id) VALUES (:user_id, :recept_id)");
    $add_stmt->execute(['user_id' => $user_id, 'recept_id' => $recipe_id]);

    // Herlaad de pagina om de wijzigingen te zien
    header("Location: profiel.php");
    exit;
}

// Verwijderen van een favoriet recept
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_favorite'])) {
    $recipe_id = $_POST['recipe_id'];

    // Verwijder het recept uit de favorieten
    $remove_stmt = $pdo->prepare("DELETE FROM favorieten WHERE user_id = :user_id AND recept_id = :recept_id");
    $remove_stmt->execute(['user_id' => $user_id, 'recept_id' => $recipe_id]);

    // Herlaad de pagina om de wijzigingen te zien
    header("Location: profiel.php");
    exit;
}

// Haal de favorieten op
$favorites_stmt = $pdo->prepare("SELECT r.recept_id, r.naam, r.afbeelding FROM favorieten f JOIN recepten r ON f.recept_id = r.recept_id WHERE f.user_id = :user_id");
$favorites_stmt->execute(['user_id' => $user_id]);
$favorites = $favorites_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary {
            background-color: #c2ad62; /* Bright Red */
        }

        .bg-secondary {
            background-color: #ffffff; /* Gold */
        }

        .bg-accent {
            background-color: #2b9e2a; /* Bright Green */
        }

        .text-accent {
            color: #ffffff; /* White Text */
        }
    </style>
</head>
<body class="bg-secondary text-gray-800">
<header class="bg-primary text-accent p-6 shadow-lg">
    <nav class="container mx-auto flex justify-between items-center">
        <a href="index.php" class="text-4xl font-bold italic">CookZ 🍽️</a>
        <ul class="flex space-x-8">
            <li><a href="index.php" class="hover:text-gray-300 transition">Home</a></li>
            <li><a href="recepten.php" class="hover:text-gray-300 transition">Recepten</a></li>
            <li><a href="favorieten.php" class="hover:text-gray-300 transition">Favorieten</a></li>
            <li><a href="profiel.php" class="hover:text-gray-300 transition">Profiel</a></li>
            <li><a href="logout.php" class="hover:text-gray-300 transition">Uitloggen</a></li>
        </ul>
    </nav>
</header>

<main class="container mx-auto mt-10">
    <h1 class="text-5xl font-bold text-center text-accent">Profiel</h1>
    <section class="mt-10">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-4xl font-bold mb-4">Jouw Gegevens</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Naam:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="border rounded p-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">E-mailadres:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="border rounded p-2 w-full">
                </div>
                <input type="submit" name="update_profile" value="Gegevens Bijwerken" class="bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">
            </form>
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-4xl font-bold mb-4">Favoriete Recepten</h2>
        <ul class="list-disc pl-5">
            <?php if ($favorites): ?>
                <?php foreach ($favorites as $favorite): ?>
                    <li class="mb-2">
                        <span><?php echo htmlspecialchars($favorite['naam']); ?></span>
                        <form method="POST" action="" class="inline">
                            <input type="hidden" name="recipe_id" value="<?php echo $favorite['recept_id']; ?>">
                            <input type="submit" name="remove_favorite" value="Verwijder" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600 transition">
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Geen favoriete recepten gevonden.</li>
            <?php endif; ?>
        </ul>
        <h2 class="text-4xl font-bold mb-4 mt-10">Voeg een Favoriet Recept Toe</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="recipe_id" class="block text-gray-700">Recept ID:</label>
                <input type="text" name="recipe_id" id="recipe_id" class="border rounded p-2 w-full" required>
            </div>
            <input type="submit" name="add_favorite" value="Voeg Favoriet Toe" class="bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">
        </form>
    </section>
</main>
</body>
</html>
