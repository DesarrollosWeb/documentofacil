<?php

const CLIENT_ID = 'e2b8f3e6-42cd-44ff-8c87-1a37d9b25c98';
const CLIENT_SECRET = '8.li7.6o-5F2AY-w3neV.b6a~Qcvq1-Od3';
const SECRET_ID = "a6b10a03-4471-4015-99a7-e20ae33ab120";
error_reporting(E_ALL);
require_once __DIR__ . "/gestoria/vendor/autoload.php";

use Krizalys\Onedrive\Onedrive;

session_start();
if (array_key_exists("error", $_GET)) {
    echo '<strong>' . $_GET["error"] . '</strong><p>' . $_GET["error_description"] . '</p>';
}

if (!array_key_exists("code", $_GET)) {
    throw new Exception("undefined code in request");
}
krumo($_SESSION, $_GET);
$client = Onedrive::client(CLIENT_ID,
    [
        "state" => $_SESSION["onedrive.client.state"]
    ]
);

try {
    $client->obtainAccessToken(CLIENT_SECRET, $_GET["code"]);
    $_SESSION["onedrive.client.state"] = $client->getState();
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


