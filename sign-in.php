<?php
error_reporting(E_ALL);
require_once "gestoria/constants.php";
require_once __DIR__ . "/gestoria/vendor/autoload.php";

use Krizalys\Onedrive\Onedrive;

const PROCEDURE_ID = "procedure_id";
$client = Onedrive::client(ONEDRIVE_CLIENT_ID);

if (isset($_GET[PROCEDURE_ID])) {
    $procedure_id = filter_var($_GET[PROCEDURE_ID], FILTER_SANITIZE_NUMBER_INT);
}

if (isset($procedure_id)) {
    $url = $client->getLogInUrl([
        'files.read',
        'files.read.all',
        'files.readwrite',
        'files.readwrite.all',
        'offline_access',
    ], ONEDRIVE_REDIRECT_URI, $procedure_id);
} else {
    $url = $client->getLogInUrl([
        'files.read',
        'files.read.all',
        'files.readwrite',
        'files.readwrite.all',
        'offline_access',
    ], ONEDRIVE_REDIRECT_URI);
}


$_SESSION[ONEDRIVE_CLIENT_STATE] = $client->getState();

header("HTTP/1.1 302 Found", true, 302);
header("Location: $url");

