<?php
$filename = 'urls.json';

function verifyUrlParams() {
    $searchParams = [];
    $goodParams = [];
    parse_str($_SERVER['QUERY_STRING'], $searchParams);
    $params = array_keys($searchParams);
    foreach ($params as $param) {
        if (isStored($param)) {
            $url = getUrlWithParam($param);
            echo "<script> whindow.location.href = '$url'; </script>";
        } else {
            if ($param == 'none') {
                showForm();
                exit;
            }
        }
    }
}

// Function to generate a unique shortened URL code
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

function isStored($id) {
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
    echo "<script>console.log('showForm');</script>";
}

function showLinks() {
    echo "<script>console.log('showLinks');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the original URL from the form submission
    $originalURL = $_POST['url'];

    // Generate a short code
    $shortID = generateID($originalURL);

    saveURL($originalURL, $shortID);


    // Redirect to the index page with the short URL appended as a query parameter
    header("Location: index.php?$shortID&none");

    exit();
}


fileExist();
verifyUrlParams();