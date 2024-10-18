<?php
session_start();
include 'config.php'; // Zorg ervoor dat de databaseverbinding werkt

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect naar login als niet ingelogd
    exit;
}

// Haal de favorieten op
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.recept_id, r.naam, r.afbeelding FROM favorieten f JOIN recepten r ON f.recept_id = r.recept_id WHERE f.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$favorieten = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorieten</title>
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
    <h1 class="text-5xl font-bold text-center text-accent">Jouw Favorieten</h1>
    <section class="mt-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php if ($favorieten): ?>
                <?php foreach ($favorieten as $favoriet): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($favoriet['afbeelding']); ?>" alt="<?php echo htmlspecialchars($favoriet['naam']); ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($favoriet['naam']); ?></h3>
                            <button onclick="editRecipe('<?php echo htmlspecialchars($favoriet['recept_id']); ?>')" class="mt-4 bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">Bewerken</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Je hebt nog geen favoriete recepten.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="bg-primary text-accent text-center p-4 mt-10">
    &copy; 2024 CookZ. Alle rechten voorbehouden.
</footer>

<script>
    function editRecipe(recipeId) {
        // Logica om het recept te bewerken; dit kan een nieuwe pagina zijn of een modal
        console.log('Bewerk recept:', recipeId);
        alert('Bewerking van recept is nog niet geïmplementeerd.');
    }
</script>
</body>
</html>
