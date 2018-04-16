<?php

require_once 'S3.php';

$config["accessKey"] = 'XXXXXXXXXXXXXXXXXXX';
$config["secretKey"] = 'XXXXXXXXXXXXXXXXXXX';

//create S3 object
$s3 = new S3($config);

if (0 < $_FILES['file']['error']) {
    echo $_FILES['file']['error'];
} else {
    $file_name = $_FILES["file"]["name"];
    if ($s3->putObjectFile($_FILES["file"]["tmp_name"], 'bucket-name', "$file_name", $s3::ACL_PUBLIC_READ)) {
        echo "File uploaded!";
    }
}