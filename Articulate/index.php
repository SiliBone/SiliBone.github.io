<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articulate</title>
    <link rel="stylesheet" href="index.css">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WCY2TWK4ND"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-WCY2TWK4ND');
    </script>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Articulate</h1>
        
        <section class="recent-articles">
            <h2>Recent Articles</h2>
<?php
function findXmlFiles($dir) {
    $xmlFiles = [];

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            $xmlFiles = array_merge($xmlFiles, findXmlFiles($path));
        } elseif (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'xml') {
            $xmlFiles[] = $path;
        }
    }

    return $xmlFiles;
}

// Specify the directory to search for XML files
$dir = $_SERVER['DOCUMENT_ROOT'];

// Function to find recent XML files using glob
function getRecentArticles($dir, $count = 6) {
    $xmlFiles = findXmlFiles($dir);
    $articles = [];
    // Extract information from XML files
    foreach ($xmlFiles as $xmlFile) {
        $xmlContent = file_get_contents($xmlFile);
        $xmlContent = str_replace('&', '&amp;', $xmlContent); // Encode '&' entity
        $xmlContent = simplexml_load_string($xmlContent);
        if ($xmlContent === false) {
            continue; // Skip files that couldn't be loaded
        }
        $article = [
            'title' => htmlspecialchars((string)$xmlContent->title),
            'img' => htmlspecialchars((string)$xmlContent->img),
            'link' => dirname(substr($xmlFile, strlen($_SERVER['DOCUMENT_ROOT']))),
            'content' => htmlspecialchars((string)$xmlContent->description)
        ];
        $article['date'] = filemtime($xmlFile); // Get file modification time
        $articles[] = $article;
    }

    // Sort articles by modification time in descending order
    usort($articles, function($a, $b) {
        return $b['date'] - $a['date'];
    });

    // Truncate articles if count exceeds the limit
    return array_slice($articles, 0, $count);
}

// Get recent articles
$recent_articles = getRecentArticles($_SERVER['DOCUMENT_ROOT']);

// Display recent articles
foreach ($recent_articles as $article) {
    echo "<article class='article-preview'>";
        echo '<div id="Recent">';
            echo '<a href="' . $article['link'] . '">';
            echo '<img src="'.$article['img'].'"</img>';
            echo '<b><h2>' . $article['title'] . '</h2></b>';
            echo "<p>" . $article['content'] . "</p>";
            echo '</a>';
        echo '</div>';
    echo "</article>";
}
?>
        </section> <!-- Move the closing </section> tag here -->
    </div>
</body>
</html>
