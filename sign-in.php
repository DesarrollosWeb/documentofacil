<?php
error_reporting(E_ALL);

require_once "gestoria/constants.php";
require_once __DIR__ . "/gestoria/vendor/autoload.php";

use Krizalys\Onedrive\Onedrive;

$client = Onedrive::client(ONEDRIVE_CLIENT_ID);

$url = $client->getLogInUrl([
    'files.read',
    'files.read.all',
    'files.readwrite',
    'files.readwrite.all',
    'offline_access',
], ONEDRIVE_REDIRECT_URI);

session_start();

$_SESSION[ONEDRIVE_CLIENT_STATE] = $client->getState();

header("HTTP/1.1 302 Found", true, 302);
header("Location: $url");

