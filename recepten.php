<?php
session_start(); // Start de sessie
include 'config.php';

// Haal recepten op
$stmt = $pdo->query("SELECT recipe_id, title, description FROM Recipe");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookZ | Recepten</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary { background-color: #c2ad62; }
        .bg-secondary { background-color: #ffffff; }
        .bg-accent { background-color: #2b9e2a; }
        .text-accent { color: #ffffff; }
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

<main class="container mx-auto mt-10">
    <h1 class="text-5xl font-bold text-center text-accent">Recepten</h1>

    <section class="mt-8 text-center">
        <h2 class="text-3xl font-semibold">Zoek naar een recept</h2>
        <input type="text" id="search-input" placeholder="Typ hier om te zoeken..." class="mt-4 border border-gray-300 rounded-md p-2 w-1/2 mx-auto shadow-md focus:outline-none focus:ring-2 focus:ring-accent">
        <button onclick="searchRecipe()" class="bg-accent text-accent py-2 px-6 rounded mt-4 hover:bg-green-500 transition">Zoek</button>
    </section>

    <section id="search-results" class="mt-8 hidden text-center">
        <h2 class="text-3xl font-semibold">Zoekresultaten</h2>
        <ul id="results-list" class="list-disc list-inside mt-4 text-left w-1/2 mx-auto"></ul>
    </section>

    <section class="mt-10">
        <h2 class="text-4xl font-bold text-center">Populaire Recepten</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
            <?php foreach ($recipes as $recipe): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="https://source.unsplash.com/1600x900/?<?php echo urlencode($recipe['title']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($recipe['description']); ?></p>
                        <a href="recept-detail.php?id=<?php echo $recipe['recipe_id']; ?>" class="mt-4 inline-block bg-accent text-accent py-2 px-4 rounded hover:bg-green-500 transition">Bekijk Recept</a>
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
    const recipes = <?php echo json_encode($recipes); ?>.map(recipe => ({
        name: recipe.title,
        link: "recept-detail.php?id=" + recipe.recipe_id
    }));

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
