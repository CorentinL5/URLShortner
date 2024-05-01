<?php
$filename = 'urls.json';
$domain = 'http://localhost:63342/URLShortner/';
$params = getParams();

function getParams(): array {
    $searchParams = [];
    parse_str($_SERVER['QUERY_STRING'], $searchParams);
    return array_keys($searchParams);
}

function verifyUrlParams() {
    $links = [];
    global $params;

    foreach ($params as $param) {
        if (isStored($param)) {
            $links[] = getUrlWithParam($param);
        }
    }
    if (sizeof($params) == 0 || in_array("none", $params)) {
        showLinks($links);
        showForm();
    } else {
        $url = strval($links[0]);
        echo "<script> location.replace('$url'); </script>";
    }
}

function generateID($str) : string {
    $hash = hash('md5', $str); // Using MD5 hash as an example
    // Convert hexadecimal hash to base 36
    $base36 = base_convert($hash, 16, 36);
    // Trim or pad the string to ensure it falls within the desired length range
    return substr($base36, 0, (strlen($str) % 5)+5);
}

function saveURL($originalURL, $shortID) {
    if (!isStored($shortID)) {
        global $filename;

        // Retrieve the existing content of the file
        $urls = getFileContent($filename);

        // Add the new URL and short ID to the array
        $urls[$shortID] = array("originalURL" => $originalURL);

        // Encode the updated array as JSON
        $json = json_encode($urls, JSON_PRETTY_PRINT);

        // Write the JSON data back to the file
        file_put_contents($filename, $json);
    }
}

function isStored($id): bool
{
    global $filename;
    $urls = getFileContent($filename);
    // Check if the ID already exists in the array
    if (isset($urls[$id])) {
        // ID already exists, return false
        return true;
    }
    return false;
}

function getUrlWithParam($id): string {
    global $filename;
    $urls = getFileContent($filename);
    return $urls[$id]['originalURL'];
}

function getFileContent($file) {
    $contents = file_get_contents($file);
    return json_decode($contents, true);
}

function fileExist() {
    global $filename;
    if (!file_exists($filename)) {
        file_put_contents($filename, json_encode([]));
    }
}

function showForm() {
    echo '<form method="post" action="shorten.php">';
    echo '<label for="url">Enter URL to shorten:</label><br>';
    echo '<input type="url" id="url" name="url" required><br><br>';
    echo '<button type="submit">Shorten</button>';
    echo '</form>';
}

function showLinks($links) {
    global $domain;
    foreach ($links as $link) {
        $id = generateID($link);
        echo "<p class='link'> <span>Shortened URL</span> <a href='$link'>$domain?$id</a> </p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the original URL from the form submission
    $originalURL = $_POST['url'];

    // Generate a short code
    $shortID = generateID($originalURL);

    saveURL($originalURL, $shortID);

    $finishParams = "?none&$shortID";

    // Redirect to the index page with the short URL appended as a query parameter
    echo "<script> location.replace('index.php$finishParams'); </script>";

    exit();
}


fileExist();
verifyUrlParams();
