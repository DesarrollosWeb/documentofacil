<?php
error_reporting(E_ALL);
const CLIENT_ID = 'e2b8f3e6-42cd-44ff-8c87-1a37d9b25c98';
const CLIENT_SECRET = '8.li7.6o-5F2AY-w3neV.b6a~Qcvq1-Od3';
const SECRET_ID = "a6b10a03-4471-4015-99a7-e20ae33ab120";
require_once "gestoria/constants.php";
require_once __DIR__ . "/gestoria/vendor/autoload.php";

use Krizalys\Onedrive\Onedrive;

$client = Onedrive::client(CLIENT_ID);

$url = $client->getLogInUrl([
    'files.read',
    'files.read.all',
    'files.readwrite',
    'files.readwrite.all',
    'offline_access',
], "https://www.documentofacil.com/redirect.php");

session_start();

$_SESSION["onedrive.client.state"] = $client->getState();

header("HTTP/1.1 302 Found", true, 302);
header("Location: $url");

