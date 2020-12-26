<?php

use Krizalys\Onedrive\Client;
use Krizalys\Onedrive\Onedrive;

class OneDriveClient
{
    private string $key_name = "forever";
    private string $key_id = "45ea9697-bcc5-46ee-aac1-181fc8aca887";
    private string $client_value = "v2IhAMhV1uln~9~p_TWJ5xc_4ql.X7h3fa";
    private string $application_id = "dd2de099-4803-452b-a5fa-63d2ad67c47f";
    private string $object_id = "febcecb0-6a28-46e1-9e06-c4a187392c0b";
    private string $directory_id = "ed029a67-13de-4684-b36a-5c624fd3db2f";

    private Client $client;
    private string $url;
    private string $redirect_uri = "https://www.documentofacil.com/registered.php";
    private string $access_token;

    public function __construct()
    {
        $this->client = Onedrive::client($this->application_id);
        $this->url = $this->client->getLogInUrl([
            'files.read',
            'files.read.all',
            'files.readwrite',
            'files.readwrite.all',
            'offline_access',
        ], $this->redirect_uri);
        session_start();
        $_SESSION['onedrive.client.state'] = $this->client->getState();
    }

    public function redirect_sign_in(): void
    {
        header('HTTP/1.1 302 Found', true, 302);
        header("Location: $this->url");
    }

    /**
     * Obtain onedrive sdk token and saves it to the session
     * @param string $code code from query param
     * @return void
     * @throws Exception
     */
    public function getAccessToken(string $code): void
    {
        $this->client->obtainAccessToken($this->client_value, $code);
        $_SESSION['onedrive.client.state'] = $this->client->getState();
    }

    public function upload(string $file): void
    {
        try {
            $this->client->getRoot()->upload($file, "test");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}