<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Load XML file
        $xmlContent = file_get_contents('article.xml');
        $xmlContent = str_replace('&', '&amp;', $xmlContent); // Encode '&' entity
        $xml = simplexml_load_string($xmlContent);
        $title = htmlspecialchars((string)$xml->title);
    ?>
    <title>Articulate - <?php echo $title; ?> </title>
    <link rel="stylesheet" href="/article.css">
    <link rel="stylesheet" href="/index.css">
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
        <!-- Back Button -->
        <a href="/" class="back-button">
            <svg class="back-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path fill="none" d="M0 0h24v24H0z"/>
                <path d="M16.01 7.99L10.82 13l5.19 5.01L14 19l-7-7 7-7 1.01 1.01z"/>
            </svg>
        </a>

        <?php
        // Print from XML
        $img = htmlspecialchars((string)$xml->img);
        echo '<section id="main" class="section">';
        echo '<img src="'.$img.'"><h1>'.$title.'</h1>
        <p>'.htmlspecialchars((string)$xml->description).'</p>         
        </section><section class="article-content">';
        if ($xml === false) {
            echo "Error loading XML file.";
        } else {
            // Display sections
            echo "<div class='section'>";
            foreach ($xml->sections->section as $section) {
                echo "<h2>" . htmlspecialchars((string)$section->heading) . "</h2>";
                echo "<p>" . htmlspecialchars((string)$section->description) . "</p>";
            }
            echo "</div>";

            // Display FAQs if available
            if (isset($xml->faqs)) {
                echo "<section class='faqs'>";
                echo "<h2>" . htmlspecialchars((string)$xml->faqs->title) . "</h2>";
                echo "<ul>";
                // Iterate through FAQs
                $i = 0;
                foreach ($xml->faqs->faq as $faq) {
                    $artLink = htmlspecialchars((string)$faq->link);
                    echo "<li>";
                    echo "<h3>" . htmlspecialchars((string)$faq->question) . "</h3>";
                    echo "<p>" . htmlspecialchars((string)$faq->answer) . "</p>";
                    if (isset($faq->link) && trim((string)$faq->link) !== '') {
                        echo "<p>[<a href=\"$artLink\">$artLink</a>]</p>";
                    }
                    echo "</li>";
                    $i++;
                }
                echo "</ul>";
                echo "</section>";
            }
        }
        ?>
    </section>
</div>
</body>
</html>
