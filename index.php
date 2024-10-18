<?php
session_start(); // Start de sessie
include 'config.php'; // Verbinding met de database maken

// Haal populaire recepten op uit de database
$stmt = $pdo->query("SELECT * FROM Recipe ORDER BY RAND() LIMIT 5"); // 5 willekeurige recepten
$recepten = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookZ | Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary { background-color: #c2ad62; }
        .bg-secondary { background-color: #ffffff; }
        .bg-accent { background-color: #2b9e2a; }
        .text-accent { color: #ffffff; }
        .bg-banner {
            background-image: url(image/woman-making-salad-kitchen.jpg);
            background-size: cover;
            background-position: center;
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

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profiel.php" class="hover:text-gray-300 transition">Profiel</a></li>
                <li><a href="logout.php" class="hover:text-gray-300 transition">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="login.php" class="hover:text-gray-300 transition">Inloggen</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <!-- Banner Section -->
    <section class="bg-banner h-64 flex items-center justify-center text-center text-accent">
        <div class="bg-black bg-opacity-50 p-6 rounded">
            <h1 class="text-4xl font-bold">Welkom bij CookZ!</h1>
            <p class="mt-4 text-xl">Ontdek heerlijke recepten en verbeter je kookkunsten!</p>
            <a href="recepten.php" class="mt-4 inline-block bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">Bekijk Recepten</a>
        </div>
    </section>

    <!-- Search Section -->
    <section class="container mx-auto mt-10 text-center">
        <h2 class="text-3xl font-semibold">Zoek naar een recept</h2>
        <input type="text" id="search-input" placeholder="Typ hier om te zoeken..." class="mt-4 border border-gray-300 rounded-md p-2 w-1/2 mx-auto shadow-md focus:outline-none focus:ring-2 focus:ring-accent">
        <button onclick="searchRecipe()" class="bg-accent text-accent py-2 px-6 rounded mt-4 hover:bg-green-500 transition">Zoek</button>
    </section>

    <section id="search-results" class="container mx-auto mt-8 hidden text-center">
        <h2 class="text-3xl font-semibold">Zoekresultaten</h2>
        <ul id="results-list" class="list-disc list-inside mt-4 text-left w-1/2 mx-auto"></ul>
    </section>

    <!-- Recommendations Section -->
    <section class="container mx-auto mt-10">
        <h2 class="text-4xl font-bold text-center mb-8">Populaire Recepten</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($recepten as $recept): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="https://source.unsplash.com/1600x900/?<?php echo urlencode($recept['title']); ?>" alt="<?php echo htmlspecialchars($recept['title']); ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($recept['title']); ?></h3>
                        <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($recept['description']); ?></p>
                        <a href="recept-detail.php?id=<?php echo $recept['recipe_id']; ?>" class="mt-4 inline-block bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">Bekijk Recept</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer class="bg-primary text-accent text-center p-4 mt-10">
    &copy; 2024 CookZ. Alle rechten voorbehouden.
</footer>

<script>
    const recipes = [
        { id: 1, name: "Spaghetti Bolognese", link: "recept-detail.php?id=1" },
        { id: 2, name: "Kip Curry", link: "recept-detail.php?id=2" },
        { id: 3, name: "Groentesalade", link: "recept-detail.php?id=3" },
        { id: 4, name: "Pasta Primavera", link: "recept-detail.php?id=4" },
        { id: 5, name: "Chili Con Carne", link: "recept-detail.php?id=5" }
    ];

    function searchRecipe() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const results = recipes.filter(recipe => recipe.name.toLowerCase().includes(query));
        displayResults(results);
    }

    function displayResults(results) {
        const resultsList = document.getElementById('results-list');
        resultsList.innerHTML = '';
        if (results.length > 0) {
            results.forEach(recipe => {
                const listItem = document.createElement('li');
                listItem.innerHTML = `<a href="${recipe.link}" class="text-primary hover:underline">${recipe.name}</a>`;
                resultsList.appendChild(listItem);
            });
            document.getElementById('search-results').classList.remove('hidden');
        } else {
            resultsList.innerHTML = '<li class="text-red-500">Geen recepten gevonden.</li>';
            document.getElementById('search-results').classList.remove('hidden');
        }
    }
</script>
</body>
</html>
