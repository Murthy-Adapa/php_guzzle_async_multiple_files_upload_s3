<?php

//autoload GuzzleHttp
require_once './vendor/autoload.php';

use GuzzleHttp\Promise;
use GuzzleHttp\Client;

// Create a client 
//Chang the URI to yours
$client = new Client(['base_uri' => 'http://localhost/php_guzzle_async_multiple_files_upload_s3/']);

if (!$_FILES) {
    echo "No files selected";
    exit;
}
$time_start = microtime_float();

foreach ($_FILES['files']['name'] as $key => $value) {

    $file_name = $_FILES['files']['name'][$key];
    $file = $_FILES['files']['tmp_name'][$key];
    $type = $_FILES['files']['type'][$key];

    // Initiate each request but do not block
    $promises[] = $client->requestAsync('POST', 'async_upload.php', [
        'multipart' => [
            [
                'name' => 'file',
                'contents' => file_get_contents($file),
                'filename' => $file_name,
                'Content-Type' => $type
            ]
        ]
    ]);
}

// Wait on all of the requests to complete. Throws a ConnectException
// if any of the requests fail
$results = Promise\unwrap($promises);

// You can access each result using the key provided to the unwrap
// function.
foreach ($results as $result) {
    echo $result->getBody() . "<br>";
}

$time_end = microtime_float();
$time = $time_end - $time_start;
echo "<br>All files were uploaded in " . $time . " seconds<br>";

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
