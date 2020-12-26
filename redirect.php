<?php
error_reporting(E_ALL);
require_once __DIR__ . "/gestoria/vendor/autoload.php";

use Krizalys\Onedrive\Onedrive;

session_start();
if (array_key_exists("error", $_GET)) {
    echo '<strong>'.$_GET["error"].'</strong><p>'.$_GET["error_description"].'</p>';
}

if (!array_key_exists("code", $_GET)) {
    throw new Exception("undefined code in request");
}

$client = Onedrive::client(ONEDRIVE_CLIENT_ID,
    [
        "state" => $_SESSION[ONEDRIVE_CLIENT_STATE]
    ]
);

try {
    $client->obtainAccessToken(ONEDRIVE_CLIENT_SECRET, $_GET["code"]);
    $_SESSION[ONEDRIVE_CLIENT_STATE] = $client->getState();
} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    $file = $client->getRoot()->upload("test.txt", "Hello World!");
    echo $file->download();
    $file->delete();
} catch (Exception $e) {
    echo $e->getMessage();
}


