<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link type="text/css" rel="stylesheet" href="style.css">

    <title>URL Shortener</title>
    <?php
        include "shorten.php";
    ?>
</head>
<body>
    <h1>URL Shortener</h1>
    <form method="post" action="shorten.php">
        <label for="url">Enter URL to shorten:</label><br>
        <input type="url" id="url" name="url" required><br><br>
        <button type="submit">Shorten</button>
    </form>
    <div id="message"></div>

    <br>

    <h2>Shortened URLs</h2>
    <div id="shortened-urls">
        <?php
            $filename = 'urls.json';
            if (file_exists($filename)) {
                $urls = getFileContent($filename);
                foreach ($urls as $shortID => $urlData) {
                    echo "<p>ID : $shortID - Original URL: {$urlData['originalURL']}</p>";
                }
            }
        ?>
</body>
</html>
